<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Wordfence Security Activator
 * Plugin URI:        https://ultrapackv2.com
 * Description:       This plugin automatically activates Wordfence Security without free license required
 * Version:           2.3.0
 * Requires at least: 5.9.0
 * Requires PHP:      7.2
 * Author:            UltraPack Team
 * Author URI:        https://ultrapackv2.com
 * Text Domain:       wordfence-activator
 * Domain Path:       /languages
 **/

defined('ABSPATH') || exit;

class ULTRAPACK_wf_ac {

    private $remaining_days = 365 * 10;
    private $plugin_domain = 'wordfence-activator';
    private $hide_plugin_option = 'up_wf_ac_phide';
    private $auto_license_option = 'up_wf_ac_auto_license';
    private $page_slug = 'WordfenceActivatorPageTutorial';

    public function __construct() {
        add_action('init', array($this, 'load_translations'), 1); 
        add_action('admin_menu', array($this, 'register_tutorial_page'));
        add_action('plugins_loaded', array($this, 'initialize_plugin'));
        add_action('wp_ajax_ultrapack_hide_plugin', array($this, 'hide_plugin'));
        add_action('wp_ajax_ultrapack_toggle_auto_license', array($this, 'toggle_auto_license'));
        add_action('wp_ajax_ultrapack_manual_license', array($this, 'manual_install_license'));
        add_action('admin_init', array($this, 'start_waf_monitoring'), 1);

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links'));
        $this->exclude_from_scans();

        add_filter('all_plugins', array($this, 'filter_hide_plugin'));
        if (is_multisite()) {
            add_filter('all_plugins', array($this, 'filter_hide_plugin'));
        }
    }

    // Load the plugin text domain for translations
    public function load_translations() {
        load_plugin_textdomain(
            $this->plugin_domain,
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    // Check if the free license is installed
    public function check_free_license_installed() {
        $license_installed = false;
        if (
            get_option('wordfenceActivated') == 1
            && class_exists('wfOnboardingController')
            && class_exists('wfConfig')
            && method_exists('wfOnboardingController', 'shouldShowAttempt3')
            && method_exists('wfConfig', 'get')
            && wfOnboardingController::shouldShowAttempt3(!self::isWordfencePage(false)) == false
            && !empty(wfConfig::get('apiKey'))
            && strlen(wfConfig::get('apiKey')) >= 128
        ) {
            $license_installed = true;
        }

        return $license_installed;
    }

    // Add action links to the plugin page
    public function add_action_links($links) {

        // Tutorial action link
        $links[] = '<a href="' . admin_url('admin.php?page=') . $this->page_slug . '">' . esc_html__('Tutorial', $this->plugin_domain) . '</a>';

        // License status action link
        if ($this->check_free_license_installed()) {
            $links[] = '<span style="color:green;"> ' . esc_html__('Free License Installed.', $this->plugin_domain) . '</span>';
        } else {
            $links[] = '<span style="color:red;">' . esc_html__('Free License Not Installed!', $this->plugin_domain) . '</span>';
        }

        return $links;
    }

    // Exclude the Wordfence Activator from scans
    private function exclude_from_scans() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wfconfig';

        if (!$this->is_wordfence_active()) {
            return;
        }

        $table_exists = $wpdb->get_var($wpdb->prepare(
            "SHOW TABLES LIKE %s",
            $table_name
        ));

        if (!$table_exists) {
            return;
        }

        $data = array(
            'name' => 'scan_exclude',
            'val' => '/wordfence-activator/*',
            'autoload' => 'yes'
        );

        try {
            $existing_entry = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE name = %s",
                $data['name']
            ));

            if ($existing_entry == 0) {
                $wpdb->insert($table_name, $data, array('%s', '%s', '%s'));
            } else {
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE $table_name SET val = CONCAT(val, %s) WHERE name = %s",
                        ',' . $data['val'],
                        $data['name']
                    )
                );
            }
        } catch (Exception $e) {
            return;
        }
    }

    // Check if the current page is a Wordfence page
    private static function isWordfencePage($includeWfls = true) {
		$page = wfUtils::array_get($_GET, 'page', '');
		return (!empty($page) && (preg_match('/^Wordfence/', $page) || ($includeWfls && $page == 'WFLS' && wfOnboardingController::shouldShowNewTour(wfOnboardingController::TOUR_LOGIN_SECURITY))));
	}

    // Initialize the plugin
    public function initialize_plugin() {
        if (!$this->is_wordfence_active() || !class_exists('wfLicense')) {
            add_action('admin_notices', array($this, 'show_admin_notice'));
            return;
        }

        // Try get free license if not installed and auto license is enabled
        if ($this->is_wordfence_active() && $this->check_free_license_installed() === false && $this->is_auto_license_enabled()) {
            $this->try_install_free_license();
        }

        if(get_option('wordfenceActivated') != 1 || wfOnboardingController::shouldShowAttempt3(!self::isWordfencePage(false))) {
            add_action('admin_notices', array($this, 'show_admin_tutorial_notice'));
            return;
        }

        try {
            wfOnboardingController::_markAttempt1Shown();
            wfConfig::set('onboardingAttempt3', wfOnboardingController::ONBOARDING_LICENSE);

            if (empty(wfConfig::get('apiKey')) || (is_string(wfConfig::get('apiKey')) && strlen(wfConfig::get('apiKey')) < 127)) {
                $downgrade = Wordfence::ajax_downgradeLicense_callback();
                if (!isset($downgrade['ok']) || !$downgrade['ok']) {
                    wfLicense::current()->downgradeToFree($downgrade['apiKey'])->save();
                    wfConfig::set_ser('twoFactorUsers', array());
                    wfConfig::remove('premiumAutoRenew');
                    wfConfig::remove('premiumNextRenew');
                    wfConfig::remove('premiumPaymentExpiring');
                    wfConfig::remove('premiumPaymentExpired');
                    wfConfig::remove('premiumPaymentMissing');
                    wfConfig::remove('premiumPaymentHold');
                    if (method_exists('Wordfence', 'licenseStatusChanged')) {
                        Wordfence::licenseStatusChanged();
                    }
                }
                
            }

            wfConfig::set('isPaid', true);
            wfConfig::set('keyType', wfLicense::KEY_TYPE_PAID_CURRENT);
            wfConfig::set('premiumNextRenew', time() + $this->remaining_days * 86400);

            if (class_exists('wfWAF')) {
                wfWAF::getInstance()->getStorageEngine()->setConfig('wafStatus', wfFirewall::FIREWALL_MODE_ENABLED);
            }

            $this->configure_license();
        } catch (Exception $exception) {
            // Handle exceptions if needed
        }
    }

    // Configure the license settings
    private function configure_license() {
        if (class_exists('wfLicense')) {
            $license = wfLicense::current();
            $license->setType(wfLicense::TYPE_RESPONSE);
            $license->setPaid(true);
            $license->setRemainingDays($this->remaining_days);
            $license->setConflicting(false);
            $license->setDeleted(false);
        }
    }

    // Check if auto license installation is enabled
    private function is_auto_license_enabled() {
        return get_option($this->auto_license_option, '1') === '1'; // Default is enabled
    }

    // Try to install free license automatically
    private function try_install_free_license() {
        // Try connect ultrapack to retrieve a free license
        // Enjoy https://ultrapackv2.com :-)
        $response = wp_remote_get('https://activations.ultrapackv2.com/wp-json/wordfence-api/v1/get_free_valid_license', array('timeout' => 15, 'sslverify' => false));
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $lic = json_decode($body, true);
        $lic = is_array($lic) && isset($lic['license']) ? trim($lic['license']) : '';
        
        if (!empty($lic) && is_string($lic) && strlen($lic) >= 128) {
            // Save license key
            if (class_exists('wfConfig')) {
                try {
                    // Define a chave da API
                    wfConfig::set('apiKey', $lic);
                    wfConfig::set('isPaid', false);
                    wfConfig::set('keyType', 'free');

                    if (!class_exists('wordfence')) {
                        include_once WP_PLUGIN_DIR . '/wordfence/lib/wordfence.php';
                    }
                    wordfence::licenseStatusChanged();
                    wfConfig::set('touppPromptNeeded', true);
                    update_option('wordfenceActivated', 1);
                    
                    return true;
                } catch (Exception $e) {
                    // Handle exception if needed
                    return false;
                }
            }
        }
        
        return false;
    }

    // Check if Wordfence is active before trying to access its tables
    public function is_wordfence_active() {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        return is_plugin_active('wordfence/wordfence.php');
    }

    // Add admin notice if Wordfence is not active
    public function show_admin_notice() {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong><?php esc_html_e('Wordfence Security Activator', $this->plugin_domain); ?></strong>
                <?php
                echo esc_html__(' requires the ', $this->plugin_domain)
                    . '<a href="https://www.ultrapackv2.com/item/plugins-premium-wordfence-premium-wordpress-security-plugin-activator/">'
                    . esc_html__('Wordfence Security plugin', $this->plugin_domain)
                    . '</a>'
                    . esc_html__(' to be installed and activated.', $this->plugin_domain)
                    . esc_html__(' Please after that follow the tutorial to install the first free license.', $this->plugin_domain);
                ?>
            </p>
        </div>
        <?php
    }

    // Show admin notice if the tutorial is not completed
    public function show_admin_tutorial_notice() {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong><?php esc_html_e('Wordfence Security Activator', $this->plugin_domain); ?></strong>
                <?php
                echo esc_html__(' cannot be activated because you need to ', $this->plugin_domain)
                    . '<a href="' . esc_url(admin_url('admin.php?page=')) . $this->page_slug .  '">'
                    . esc_html__('follow the tutorial', $this->plugin_domain)
                    . '</a>'
                    . esc_html__(' to install the first free license.', $this->plugin_domain);
                ?>
            </p>
        </div>
        <?php
    }

    // Register the tutorial page
    public function register_tutorial_page() {
        add_submenu_page(
            '',
            __('Tutorial de Ativação', $this->plugin_domain),
            __('Tutorial', $this->plugin_domain),
            'manage_options',
            $this->page_slug,
            array($this, 'render_tutorial_content')
        );
    }

    // Process the AJAX request to hide or unhide the plugin
    public function hide_plugin() {
        if (isset($_POST['action']) && $_POST['action'] == 'ultrapack_hide_plugin') {
            $unhide = isset($_POST['unhide']) ? intval($_POST['unhide']) : 0;
            if ($unhide === 1) {
                update_option($this->hide_plugin_option, '0');
            } else {
                update_option($this->hide_plugin_option, '1');
            }
            wp_send_json_success();
        }
    }

    // Process the AJAX request to toggle auto license installation
    public function toggle_auto_license() {
        if (isset($_POST['action']) && $_POST['action'] == 'ultrapack_toggle_auto_license') {
            $enable = isset($_POST['enable']) ? intval($_POST['enable']) : 0;
            if ($enable === 1) {
                update_option($this->auto_license_option, '1');
            } else {
                update_option($this->auto_license_option, '0');
            }
            wp_send_json_success();
        }
    }

    // Process the AJAX request to manually install free license
    public function manual_install_license() {
        if (isset($_POST['action']) && $_POST['action'] == 'ultrapack_manual_license') {
            if (!$this->is_wordfence_active()) {
                wp_send_json_error(array('message' => __('Wordfence plugin is not active!', $this->plugin_domain)));
                return;
            }
            
            $result = $this->try_install_free_license();
            if ($result) {
                wp_send_json_success(array('message' => __('Free license installed successfully!', $this->plugin_domain)));
            } else {
                wp_send_json_error(array('message' => __('Failed to install free license. Please try again later.', $this->plugin_domain)));
            }
        }
    }

    // Hide the plugin action
    public function filter_hide_plugin($plugins) {
        if (get_option($this->hide_plugin_option) === '1') {
            $plugin_file = plugin_basename(__FILE__);
            if (isset($plugins[$plugin_file])) {
                unset($plugins[$plugin_file]);
            }
        }
        return $plugins;
    }
    
    public function force_refresh_license() {
        if ($this->is_auto_license_enabled()) {
             if ($this->try_install_free_license()) {
                 // Re-apply paid status immediately
                 wfConfig::set('isPaid', true);
                 wfConfig::set('keyType', wfLicense::KEY_TYPE_PAID_CURRENT);
                 wfConfig::set('premiumNextRenew', time() + $this->remaining_days * 86400);
                 $this->configure_license();
             }
        }
    }

    public function start_waf_monitoring() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'wordfence_updateWAFRules') {
            ob_start(array($this, 'check_waf_response'));
        }
    }

    public function check_waf_response($buffer) {
        if ($buffer) {
            $data = json_decode($buffer, true);
            if (is_array($data) && isset($data['failure']) && $data['failure'] === 'ratelimit') {
                $this->force_refresh_license();
            }
        }
        return $buffer;
    }

    // Render the tutorial content
    public function render_tutorial_content() {
        ?>
        <script>
            jQuery(document).ready(function($) {
            $('#wf-toupp-required-overlay').remove();
            $('#wf-toupp-required-message').remove();

            $('#ultrapack-hide-plugin').on('click', function(e) {
                e.preventDefault();
                var isHidden = $(this).data('hidden') === 1;
                var confirmMsg = isHidden
                ? "<?php _e('Are you sure you want to unhide the plugin and show it in the plugins list?', $this->plugin_domain); ?>"
                : "<?php _e('Are you sure you want to hide the plugin from the plugins list?', $this->plugin_domain); ?>";
                if(confirm(confirmMsg)) {
                $.post(ajaxurl, {
                    action: 'ultrapack_hide_plugin',
                    unhide: isHidden ? 1 : 0
                }, function(response) {
                    if (isHidden) {
                    alert("<?php _e('Plugin unhidden successfully!', $this->plugin_domain); ?>");
                    } else {
                    alert("<?php _e('Plugin hidden successfully!', $this->plugin_domain); ?>");
                    alert("<?php _e('Your link to access the page is:', $this->plugin_domain); ?> " + "<?php echo admin_url('admin.php?page=') . $this->page_slug; ?>");
                    }
                    location.reload();
                });
                }
            });

            $('#ultrapack-toggle-auto-license').on('click', function(e) {
                e.preventDefault();
                var isEnabled = $(this).data('enabled') === 1;
                var confirmMsg = isEnabled
                ? "<?php _e('Are you sure you want to disable automatic free license installation?', $this->plugin_domain); ?>"
                : "<?php _e('Are you sure you want to enable automatic free license installation?', $this->plugin_domain); ?>";
                if(confirm(confirmMsg)) {
                $.post(ajaxurl, {
                    action: 'ultrapack_toggle_auto_license',
                    enable: isEnabled ? 0 : 1
                }, function(response) {
                    if (isEnabled) {
                    alert("<?php _e('Automatic license installation disabled successfully!', $this->plugin_domain); ?>");
                    } else {
                    alert("<?php _e('Automatic license installation enabled successfully!', $this->plugin_domain); ?>");
                    }
                    location.reload();
                });
                }
            });

            $('#ultrapack-manual-license').on('click', function(e) {
                e.preventDefault();
                var $button = $(this);
                var originalText = $button.text();
                
                $button.prop('disabled', true).text('<?php _e('Installing...', $this->plugin_domain); ?>');
                
                $.post(ajaxurl, {
                    action: 'ultrapack_manual_license'
                }, function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(response.data.message);
                        $button.prop('disabled', false).text(originalText);
                    }
                });
            });
            });
        </script>

        <div class="wrap">
            <h1><?php _e('Wordfence Security Activator', $this->plugin_domain); ?></h1>
            <p><?php _e('Please follow the tutorial below to install the free license.', $this->plugin_domain); ?></p>
            <hr>

            <h2><?php _e('Hide plugin:', $this->plugin_domain); ?></h2>
            <div class="ultrapack-hide-plugin">
            <p><?php _e('If you need to hide this plugin from appearing in the plugins list, click the button below.', $this->plugin_domain); ?></p>

            <div class="ultrapack-hide-section">
                <span class="ultrapack-actual-status">
                <strong><?php _e('Current Status:', $this->plugin_domain); ?></strong>
                <?php
                if (get_option($this->hide_plugin_option) === '1') {
                    echo '<span style="color:green;">' . esc_html__('Hidden', $this->plugin_domain) . '</span>';
                    $text_hide = esc_html__('Unhide Plugin', $this->plugin_domain);
                    $is_hidden = 1;
                } else {
                    echo '<span style="color:red;">' . esc_html__('Visible', $this->plugin_domain) . '</span>';
                    $text_hide = esc_html__('Hide Plugin', $this->plugin_domain);
                    $is_hidden = 0;
                }
                ?>
                </span>
                </div>
                <button class="button button-primary" id="ultrapack-hide-plugin" data-hidden="<?php echo esc_attr($is_hidden); ?>"><?php echo $text_hide; ?></button>
            <p><?php _e('If you hide the plugin, it will not be visible in the plugins list, but you can still access this page using the link below.', $this->plugin_domain); ?></p>
            <p><?php _e('Save this link to access the page even when the plugin is hidden:', $this->plugin_domain); ?>
                <br><code><?php echo admin_url('admin.php?page=') . $this->page_slug; ?></code>
            </p>
            </div>
            <hr>

            <h2><?php _e('Auto License Settings:', $this->plugin_domain); ?></h2>
            <div class="ultrapack-auto-license">
            <p><?php _e('Control automatic free license installation when no license is detected.', $this->plugin_domain); ?></p>

            <div class="ultrapack-auto-license-section">
                <span class="ultrapack-actual-status">
                <strong><?php _e('Auto License Status:', $this->plugin_domain); ?></strong>
                <?php
                if (get_option($this->auto_license_option, '1') === '1') {
                    echo '<span style="color:green;">' . esc_html__('Enabled', $this->plugin_domain) . '</span>';
                    $text_auto = esc_html__('Disable Auto License', $this->plugin_domain);
                    $is_enabled = 1;
                } else {
                    echo '<span style="color:red;">' . esc_html__('Disabled', $this->plugin_domain) . '</span>';
                    $text_auto = esc_html__('Enable Auto License', $this->plugin_domain);
                    $is_enabled = 0;
                }
                ?>
                </span>
                </div>
                <button class="button button-secondary" id="ultrapack-toggle-auto-license" data-enabled="<?php echo esc_attr($is_enabled); ?>"><?php echo $text_auto; ?></button>
            <p><?php _e('When enabled, the plugin will automatically try to install a free license when none is detected. When disabled, you will need to manually install the license following the tutorial below.', $this->plugin_domain); ?></p>
            
            <?php if (!$this->check_free_license_installed() && $this->is_wordfence_active()): ?>
            <div class="ultrapack-manual-license-section" style="margin-top: 15px;">
                <p><strong><?php _e('No free license detected!', $this->plugin_domain); ?></strong></p>
                <button class="button button-primary" id="ultrapack-manual-license"><?php _e('Try Install Free License Now', $this->plugin_domain); ?></button>
                <p><small><?php _e('Click this button to manually attempt to install a free license from UltraPack servers.', $this->plugin_domain); ?></small></p>
            </div>
            <?php endif; ?>
            
            </div>
            <hr>

            <h2><?php _e('Instalation Status:', $this->plugin_domain); ?></h2>
            <div class="ultrapack-tutorial-status">
                <div class="ultrapack-tutorial-status-item">
                    <span class="ultrapack-tutorial-status-label"><?php _e('Wordfence Security Free License Status:', $this->plugin_domain); ?></span><br>
                    <span class="wf-free-license-status"><?php
                        $instance = new self();
                        if ($instance->is_wordfence_active() && class_exists('wfOnboardingController') && class_exists('wfConfig')) {
                            $option_activated = get_option('wordfenceActivated');
                            if ($option_activated == 1 
                            && wfOnboardingController::shouldShowAttempt3(!self::isWordfencePage(false)) == false 
                            && !empty(wfConfig::get('apiKey'))
                            && strlen(wfConfig::get('apiKey')) >= 128) {
                                echo '<span style="color:green;"><span class="dashicons dashicons-yes-alt"></span> ';
                                _e('Free License Installed.', $this->plugin_domain);
                                echo '</span>';
                            } else {
                                echo '<span style="color:red;"><span class="dashicons dashicons-dismiss"></span> ';
                                _e('Free License Not Installed!', $this->plugin_domain);
                                echo '</span>';
                            }
                        } else {
                            echo '<span style="color:orange;"><span class="dashicons dashicons-warning"></span> ';
                            _e('Wordfence Security plugin not installed!', $this->plugin_domain);
                            echo ' ';
                            echo '</span><br><span>';
                            echo esc_html__('Please download and install the plugin from the link: ', $this->plugin_domain);
                            echo '<a href="https://www.ultrapackv2.com/item/plugins-premium-wordfence-premium-wordpress-security-plugin-activator/">' . esc_html__('Wordfence Security plugin', $this->plugin_domain) . '</a>';
                            echo '</span>';
                        }
                    ?></span>
                </div>
            </div>
            <hr>

            <div class="ultrapack-tutorial">
                <h2><?php _e('Free License Installation Tutorial', $this->plugin_domain); ?></h2>
                <p><?php _e('To keep instalation click on "Resume Installation" warning message.', $this->plugin_domain); ?></p>
                <ol>
                    <li>
                        <strong><?php _e('First of all, you MUST install a free license on your site, otherwise the product will NOT work!', $this->plugin_domain); ?></strong>
                    </li>
                </ol>
                <h3><?php _e('How to install the free license:', $this->plugin_domain); ?></h3>
                <ol>
                    <li><?php _e('When installing the activator plugin, make sure you have the Wordfence plugin activated.', $this->plugin_domain); ?></li>
                    <li><?php _e('After installing Wordfence and the activator, resume the Wordfence installation:', $this->plugin_domain); ?><br>
                        <img src="<?php echo plugins_url('assets/img/img1.png', __FILE__); ?>" alt="<?php _e('Wordfence Installation', $this->plugin_domain); ?>" style="max-width:800px;height:auto;border-style:solid;">
                    </li>
                    <li><?php _e('Click to get a license. You will be redirected to the plugin page where you will be asked to acquire a license, choose the <strong>Free</strong> option.', $this->plugin_domain); ?></li>
                    <li><?php _e('After obtaining a license, check your email for the key and return to WordPress.', $this->plugin_domain); ?></li>
                    <li><?php _e('Now install the license in <strong>Install an existing license</strong>, enter the data and activate as shown in the image:', $this->plugin_domain); ?><br>
                        <img src="<?php echo plugins_url('assets/img/img2.png', __FILE__); ?>" alt="<?php _e('Install existing license', $this->plugin_domain); ?>" style="max-width:550px;height:auto;border-style:solid;">
                    </li>
                    <li><?php _e('Once your site is activated as shown below, the activator will automatically enable premium activation for the item:', $this->plugin_domain); ?><br>
                        <img src="<?php echo plugins_url('assets/img/img3.png', __FILE__); ?>" alt="<?php _e('Site activated', $this->plugin_domain); ?>" style="max-width:300px;height:auto;border-style:solid;">
                    </li>
                </ol>
                <h3><?php _e('What to do after installing the free activation version:', $this->plugin_domain); ?></h3>
                <ul>
                    <li><?php _e('After installing the free license, keep the activator plugin enabled on your site at all times for premium features to work.', $this->plugin_domain); ?></li>
                </ul>

            </div>
        </div>
        <?php
    }
}

new ULTRAPACK_wf_ac();