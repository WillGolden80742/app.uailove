# UaiLove

Tema WordPress para a plataforma de encontros **UaiLove**, com suporte a perfis de usuário, matches, chat em tempo real, pontos de encontro e loja de mimos.

- **Autor**: William Dourado
- **GitHub**: https://github.com/WillGolden80742/uailove
- **Licença**: GNU General Public License v2 or later

---

## Screenshots

Todas as screenshots em `app/assets/images/`.

### Desktop (1920×1080)

| Rota | Screenshot |
|------|-----------|
| `/discovery` | ![Discovery](app/assets/images/screenshot-discovery-landscape-1920.png) |
| `/matches` | ![Matches](app/assets/images/screenshot-matches-landscape-1920.png) |
| `/messages` | ![Messages](app/assets/images/screenshot-messages-landscape-1920.png) |
| `/messages/reno` | ![Conversa](app/assets/images/screenshot-messages-conv-landscape-1920.png) |
| `/messages/reno` + Emoji | ![Emoji](app/assets/images/screenshot-messages-conv-emoji-landscape-1920.png) |
| `/messages/reno` + GIF | ![GIF](app/assets/images/screenshot-messages-conv-gif-landscape-1920.png) |
| `/point` | ![Point](app/assets/images/screenshot-point-landscape-1920.png) |
| `/profile` | ![Profile](app/assets/images/screenshot-profile-landscape-1920.png) |
| `/notifications` | ![Notifications](app/assets/images/screenshot-notifications-landscape-1920.png) |
| `/admin` | ![Admin](app/assets/images/screenshot-admin-landscape-1920.png) |
| `/shop` | ![Shop](app/assets/images/screenshot-shop-landscape-1920.png) |
| `/acessar` | ![Acessar](app/assets/images/screenshot-acessar-landscape-1920.png) |

### Mobile Portrait (375×812)

| Rota | Screenshot |
|------|-----------|
| `/discovery` | ![Discovery](app/assets/images/screenshot-discovery-mobile-portrait.png) |
| `/matches` | ![Matches](app/assets/images/screenshot-matches-mobile-portrait.png) |
| `/messages` | ![Messages](app/assets/images/screenshot-messages-mobile-portrait.png) |
| `/messages/reno` | ![Conversa](app/assets/images/screenshot-messages-conv-mobile-portrait.png) |
| `/messages/reno` + Emoji | ![Emoji](app/assets/images/screenshot-messages-conv-emoji-mobile-portrait.png) |
| `/messages/reno` + GIF | ![GIF](app/assets/images/screenshot-messages-conv-gif-mobile-portrait.png) |
| `/point` | ![Point](app/assets/images/screenshot-point-mobile-portrait.png) |
| `/profile` | ![Profile](app/assets/images/screenshot-profile-mobile-portrait.png) |
| `/notifications` | ![Notifications](app/assets/images/screenshot-notifications-mobile-portrait.png) |
| `/admin` | ![Admin](app/assets/images/screenshot-admin-mobile-portrait.png) |
| `/shop` | ![Shop](app/assets/images/screenshot-shop-mobile-portrait.png) |
| `/acessar` | ![Acessar](app/assets/images/screenshot-acessar-mobile-portrait.png) |

---

## Índice

1. [Arquitetura](#arquitetura)
2. [Estrutura de Diretórios](#estrutura-de-diretórios)
3. [Bootstrap e Inicialização](#bootstrap-e-inicialização)
4. [Roteamento](#roteamento)
5. [REST API](#rest-api)
6. [Recursos Principais](#recursos-principais)
7. [MVC — Classes PHP](#mvc--classes-php)
8. [AJAX Actions](#ajax-actions)
9. [WebSocket](#websocket)
10. [LoveMedia — Gerenciamento de Mídia](#lovemedia--gerenciamento-de-mídia)
11. [Autenticação](#autenticação)
12. [Admin](#admin)
13. [WooCommerce / Loja de Mimos](#woocommerce--loja-de-mimos)
14. [Pontos de Encontro](#pontos-de-encontro)
15. [PWA](#pwa)
16. [Assets JavaScript](#assets-javascript)
17. [Assets CSS](#assets-css)
18. [Custom Post Types](#custom-post-types)
19. [Campos de Perfil](#campos-de-perfil)
20. [Scripts de Desenvolvimento](#scripts-de-desenvolvimento)
21. [Configuração de Desenvolvimento](#configuração-de-desenvolvimento)
22. [Variáveis de Ambiente](#variáveis-de-ambiente)
23. [Dependências Externas](#dependências-externas)
24. [Requisitos](#requisitos)
25. [Créditos](#créditos)

---

## Arquitetura

O tema segue uma arquitetura **MVC inspirada em Ruby on Rails**, organizando código de forma modular e escalável dentro do sistema de templates do WordPress.

- **Namespace PHP**: `UaiLove\MVC`
- **Autoloader**: PSR-4 customizado em `app/autoloader.php`
- **Entry point**: `functions.php` → `app/bootstrap.php` → `Application::get_instance()`
- **Roteamento frontend**: `RouterService` lê `REQUEST_URI` e determina a seção ativa via `config/routes.php`
- **Roteamento de templates**: `MainController` (via `template_include`) despacha para o template correto
- **REST API**: `RestRouteService` registra rotas definidas em `config/routes.php`
- **WebSocket**: cliente Node.js externo com fallback para long polling
- **Carregamento de assets**: `AssetsController` centraliza todos os enqueues de CSS/JS

---

## Estrutura de Diretórios

```
|-- .git
|-- app
|   |-- assets
|   |   |-- images
|   |   |   |-- logo.png
|   |   |   \-- logo.svg
|   |   |-- javascripts
|   |   |   |-- admin-switch.js
|   |   |   |-- admin.js
|   |   |   |-- auth.js
|   |   |   |-- back-button.js
|   |   |   |-- chat-redirect.js
|   |   |   |-- chat.js
|   |   |   |-- customizer.js
|   |   |   |-- discovery.js
|   |   |   |-- file-upload.js
|   |   |   |-- gallery-upload.js
|   |   |   |-- image-upload.js
|   |   |   |-- lovemedia.js
|   |   |   |-- navigation.js
|   |   |   |-- notifications.js
|   |   |   |-- points.js
|   |   |   |-- profile.js
|   |   |   |-- realpoint-data.js
|   |   |   |-- script.js
|   |   |   |-- section-back.js
|   |   |   |-- session-check.js
|   |   |   |-- shop.js
|   |   |   |-- ui-feedback.js
|   |   |   |-- websocket-admin.js
|   |   |   \-- websocket-service.js
|   |   \-- stylesheets
|   |       |-- admin.css
|   |       |-- auth.css
|   |       |-- base.css
|   |       |-- chat.css
|   |       |-- checkout-modal-box.css
|   |       |-- desktop.css
|   |       |-- discovery.css
|   |       |-- error.css
|   |       |-- layout.css
|   |       |-- lovemedia.css
|   |       |-- notifications.css
|   |       |-- points.css
|   |       |-- profile-tabs.css
|   |       |-- profile.css
|   |       |-- shop.css
|   |       |-- style.css
|   |       \-- toast.css
|   |-- config
|   |   \-- initializers
|   |       |-- custom-header.php
|   |       |-- customizer.php
|   |       |-- jetpack.php
|   |       |-- template-functions.php
|   |       \-- template-tags.php
|   |-- controllers
|   |   |-- AdminController.php
|   |   |-- AdminSettingsController.php
|   |   |-- AssetsController.php
|   |   |-- AuthController.php
|   |   |-- DiscoveryController.php
|   |   |-- GifController.php
|   |   |-- LoveMediaController.php
|   |   |-- MainController.php
|   |   |-- MatchesController.php
|   |   |-- MessagesController.php
|   |   |-- PointsController.php
|   |   |-- ProfileController.php
|   |   |-- PwaController.php
|   |   \-- ShopController.php
|   |-- lib
|   |   |-- traits
|   |   |   \-- Singleton.php
|   |   \-- TemplateHelper.php
|   |-- models
|   |   |-- AdminModel.php
|   |   |-- AdminSettingsModel.php
|   |   |-- AuthModel.php
|   |   |-- ChatModel.php
|   |   |-- ConversationModel.php
|   |   |-- DiscoveryModel.php
|   |   |-- GifModel.php
|   |   |-- LikeModel.php
|   |   |-- LoveMediaModel.php
|   |   |-- LoveMediaSettingsModel.php
|   |   |-- MatchModel.php
|   |   |-- MessagesModel.php
|   |   |-- NotificationModel.php
|   |   |-- NotificationQueueModel.php
|   |   |-- PhotoModel.php
|   |   |-- PointModel.php
|   |   |-- ProductModel.php
|   |   |-- ProfileFieldDefinitionModel.php
|   |   |-- ProfileModel.php
|   |   |-- PwaModel.php
|   |   |-- ShopModel.php
|   |   \-- UserProfileModel.php
|   |-- services
|   |   |-- AddressCacheService.php
|   |   |-- AuthFormService.php
|   |   |-- CptFactoryService.php
|   |   |-- EnvService.php
|   |   |-- RestRouteService.php
|   |   |-- RouterService.php
|   |   |-- SystemSetupService.php
|   |   |-- TemplateDataService.php
|   |   \-- UserMetaFactoryService.php
|   |-- views
|   |   |-- home
|   |   |   |-- app-header.php
|   |   |   |-- bottom-nav.php
|   |   |   |-- point-overlay.php
|   |   |   |-- preloader.php
|   |   |   |-- section-discovery.php
|   |   |   |-- section-matches.php
|   |   |   |-- section-messages.php
|   |   |   |-- section-notifications.php
|   |   |   |-- section-points.php
|   |   |   |-- section-profile.php
|   |   |   \-- section-shop.php
|   |   |-- layouts
|   |   |   \-- application.php
|   |   |-- shared
|   |   |   |-- content-none.php
|   |   |   |-- content-page.php
|   |   |   |-- content-search.php
|   |   |   |-- content.php
|   |   |   |-- page-header.php
|   |   |   \-- section-admin.php
|   |   \-- landingpage.php
|   |-- 404.php
|   |-- Application.php
|   |-- archive.php
|   |-- autoloader.php
|   |-- bootstrap.php
|   |-- comments.php
|   |-- footer.php
|   |-- header.php
|   |-- index.php
|   |-- page.php
|   |-- search.php
|   |-- sidebar.php
|   \-- single.php
|-- config
|   \-- routes.php
|-- languages
|   |-- readme.txt
|   \-- uailove.pot
|-- mvc
|   \-- WebSocket
|       |-- logs
|       \-- server.php
|-- .env
|-- .eslintrc
|-- .gitattributes
|-- .gitignore
|-- .mergeignore
|-- .stylelintrc.json
|-- 404.php
|-- archive.php
|-- comments.php
|-- composer.json
|-- composer.lock
|-- dir.md
|-- footer.php
|-- functions.php
|-- header.php
|-- index.php
|-- LICENSE
|-- merged_output.txt
|-- package.json
|-- page.php
|-- phpcs.xml.dist
|-- README.MD
|-- readme.txt
|-- screenshot.png
|-- search.php
|-- sidebar.php
|-- single.php
\-- style.css
```

---

## Bootstrap e Inicialização

O fluxo de inicialização ao carregar o tema:

```
functions.php (1 linha: include app/bootstrap.php)
  └── app/bootstrap.php
        ├── define('_S_VERSION', '1.0.0')
        ├── vendor/autoload.php               ← Composer: OpenCage Geocoder
        ├── app/autoloader.php                ← Autoloader PSR-4 customizado
        ├── Application::get_instance()       ← Instancia todos Controllers e Services
        ├── add_filter('show_admin_bar', '__return_false')
        ├── uailove_setup()                   ← add_action('after_setup_theme')
        │     ├── load_theme_textdomain
        │     ├── add_theme_support (title-tag, post-thumbnails, html5, etc.)
        │     └── register_nav_menus
        ├── uailove_widgets_init()            ← add_action('widgets_init')
        └── config/initializers/
              ├── custom-header.php
              ├── template-tags.php
              ├── template-functions.php
              ├── customizer.php
              └── jetpack.php                 ← apenas se JETPACK__VERSION definido
```

### Application::init()

Instancia todos os controllers e services:
- **Controllers**: `MainController`, `MatchesController`, `MessagesController`, `ProfileController`, `DiscoveryController`, `PointsController`, `AdminController`, `AuthController`, `GifController`, `PwaController`, `ShopController`, `AdminSettingsController`, `LoveMediaController`, `AssetsController`
- **Services**: `SystemSetupService`, `RestRouteService`

### Autoloader

O autoloader em `app/autoloader.php` mapeia o namespace `UaiLove\MVC\` para os diretórios dentro de `app/`:

| Namespace               | Diretório físico         |
|-------------------------|--------------------------|
| `UaiLove\MVC\Controllers\` | `app/controllers/`    |
| `UaiLove\MVC\Models\`       | `app/models/`         |
| `UaiLove\MVC\Services\`     | `app/services/`       |
| `UaiLove\MVC\Traits\`       | `app/lib/traits/`     |
| `UaiLove\MVC\Config\`       | `app/config/`         |
| `UaiLove\MVC\Lib\`          | `app/lib/`            |

---

## Roteamento

### Rotas SPA (frontend)

Definidas em `config/routes.php`. O `RouterService` lê `$_SERVER['REQUEST_URI']` e determina a seção ativa. O `MainController` registra rewrite rules no `init` para capturar as URLs amigáveis.

| URL                  | Controller / View          | Seção ativa     |
|----------------------|----------------------------|-----------------|
| `/discovery`         | `DiscoveryController`      | `discovery`     |
| `/matches`           | `MatchesController`        | `matches`       |
| `/messages[/:id]`    | `MessagesController`       | `messages`      |
| `/point`             | `PointsController`         | `points`        |
| `/profile`           | `ProfileController`        | `profile`       |
| `/notifications`     | `NotificationsController`  | `notifications` |
| `/admin`             | `AdminController`          | `admin`         |
| `/shop`              | `ShopController`           | `shop`          |
| `/acessar`           | `AcessarController`        | `acessar`       |
| `/token/:token`      | `MainController`           | token login     |
| *(qualquer outra)*   | `DiscoveryController`      | `discovery`     |

Usuários não logados são redirecionados para a seção `profile` (tela de login/cadastro). Usuários logados na raiz (`/`) são redirecionados para `/discovery`.

### Roteamento de Templates WordPress

`MainController` intercepta o hook `template_include`, verifica a query var `spa_route` e serve o layout SPA para todas as rotas, ignorando o sistema de templates padrão do WordPress.

### Token Login

`MainController::handle_token_login()` captura tokens de login via URL nos formatos:
- `/token/{token}/`
- `?uailove_token={token}`

Os tokens são gerados por `AdminModel::generate_login_token()` com validade de 15 minutos.

---

## REST API

Rotas registradas via `RestRouteService` a partir de `config/routes.php`:

| Method | Route                      | Controller              | Descrição                  |
|--------|----------------------------|-------------------------|----------------------------|
| GET    | `uailove/v1/points`        | `PointsController`       | Lista pública de pontos    |
| POST   | `uailove/v1/points/geocode`| `PointsController`       | Geocodifica um endereço    |
| GET    | `uailove/v1/shop/products` | `ShopController`         | Lista pública de produtos  |

---

## Recursos Principais

### Perfis de Usuário
- Cadastro e edição de perfil com foto principal e galeria
- Campos personalizados via `ProfileFieldDefinitionModel` e `UserMetaFactoryService`
- Maioria dos campos renderizados como meta boxes no admin
- Upload múltiplo de fotos com crop via Cropper.js
- 40+ campos de perfil organizados em grupos (Básico, Localização, Sobre Mim, Perfil Físico, Perfil Psicológico, Outras Informações)

### Sistema de Matches
- Algoritmo de matching baseado em likes mútuos (`LikeModel` + `MatchModel`)
- Suporte a like, super like, dislike, block, unmatch
- Pontuação de afinidade e distância registradas no momento da interação
- Notificações em tempo real via WebSocket ao ocorrer um match
- Lista de matches com fotos, nomes e acesso direto ao chat

### Chat em Tempo Real
- Conversas exclusivas entre usuários com match
- WebSocket via `WPWebSocketService` conectado a servidor externo
- Reconexão automática (até 5 tentativas com intervalo de 3s)
- Heartbeat keepalive a cada 30s
- Fallback para long polling quando WebSocket indisponível
- Histórico persistente no banco WordPress (CPT `chat`)
- Suporte a envio de GIFs (via Tenor API)
- Notificações push de mensagem para destinatário

### Descoberta (Discovery)
- Feed de perfis compatíveis com filtros de preferência
- Sistema de likes e super likes com animações
- Feedback visual de match imediato

### Pontos de Encontro
- Cadastro de locais físicos para encontros
- Taxonomias: `point_category` (hierárquica) e `point_vibe` (não hierárquica)
- Mapa interativo via Leaflet.js
- Geocoding via OpenCage API
- Metadados: endereço, coordenadas, data, ícone Font Awesome, imagem de destaque
- Funcionalidades sociais: curtidas, nome de quem curtiu

### Loja de Mimos
- Integração com WooCommerce
- Produtos renomeados para "Mimos" (`SystemSetupService`)
- Pedidos renomeados para "Mimos Enviados"
- Seleção de destinatário entre matches
- Notificação no chat quando pagamento é confirmado
- Histórico de mimos enviados e recebidos
- Role `lojista` para gerenciamento de produtos

### Gerenciamento de Mídia (LoveMedia)
- Biblioteca de mídia do usuário com query, upload, delete
- Filtros por ano/mês
- Pesquisa por título
- Configurações de dimensões mín/máx, qualidade e crop
- Cropper.js integrado para recorte de imagens

### Autenticação
- Login e cadastro via AJAX
- Google OAuth via Google Identity Services (`google_client_id`)
- Recuperação de senha por e-mail
- Auto-login após cadastro (configurável)
- Token login para acesso administrativo remoto

### Admin
- CRUD de usuários (criar, editar, deletar)
- CRUD de pontos de encontro
- Impersonação de usuário com session switch
- Configurações PWA
- Configurações de autenticação (Google OAuth, redirects)
- Configurações LoveMedia
- Painel WebSocket com logs e estatísticas
- Admin switch (cookie/session para voltar ao admin original)

### PWA (Progressive Web App)
- Manifest dinâmico via `PwaController` e `PwaModel`
- Service worker registration
- Meta tags: `theme_color`, `apple-touch-icon`
- Configurável pelo painel admin

---

## MVC — Classes PHP

### Controllers

Todos os controllers usam o trait `Singleton` (exceto `PwaController` que implementa o padrão manualmente) e registram seus hooks no construtor.

| Controller | Responsabilidade |
| --- | --- |
| `AdminController` |  |
| `AdminSettingsController` |  |
| `AssetsController` | fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600 |
| `AuthController` |  |
| `DiscoveryController` |  |
| `GifController` |  |
| `LoveMediaController` |  |
| `MainController` |  |
| `MatchesController` |  |
| `MessagesController` |  |
| `PointsController` |  |
| `ProfileController` | Faz upload de uma imagem para a biblioteca de mídia do WordP |
| `PwaController` |  |
| `ShopController` | Prepara e retorna os dados necessários para a view section-s |

### Models

| Model | Responsabilidade |
| --- | --- |
| `AdminModel` | 15 minutes |
| `AdminSettingsModel` |  |
| `AuthModel` | oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($cred |
| `ChatModel` | Chat — Persistência do post-type "chat" e suas mensagens. |
| `ConversationModel` | Conversation — Formatação e montagem de conversas/mensagens  |
| `DiscoveryModel` |  |
| `GifModel` | tenor.com/search/{$search_term}-gifs"; |
| `LikeModel` | Like — Persistência e consulta de likes/matches entre usuári |
| `LoveMediaModel` |  |
| `LoveMediaSettingsModel` |  |
| `MatchModel` |  |
| `MessagesModel` |  |
| `NotificationModel` |  |
| `NotificationQueueModel` | NotificationQueue — Gerencia filas de notificação via WordPr |
| `PhotoModel` |  |
| `PointModel` | maps.googleapis.com/maps/api/geocode/json'); |
| `ProductModel` |  |
| `ProfileFieldDefinitionModel` |  |
| `ProfileModel` | Profile — Persistência e sanitização dos metadados de perfil |
| `PwaModel` |  |
| `ShopModel` | Retorna os pedidos enviados pelo usuário como mimos. |
| `UserProfileModel` | UserProfile — Leitura e normalização de dados de perfil do u |

### Services

| Service | Responsabilidade |
| --- | --- |
| `AddressCacheService` |  |
| `AuthFormService` |  |
| `CptFactoryService` | ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothne |
| `EnvService` |  |
| `RestRouteService` |  |
| `RouterService` |  |
| `SystemSetupService` | instagram.com/seu_usuario', |
| `TemplateDataService` |  |
| `UserMetaFactoryService` | ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothne |

## AJAX Actions

### Chat / Mensagens

| Action                          | Descrição                                  |
|---------------------------------|--------------------------------------------|
| `uailove_get_chat_conversations`| Lista todas as conversas do usuário        |
| `uailove_open_chat`             | Abre (ou cria) uma conversa específica     |
| `uailove_send_chat_message`     | Envia mensagem de texto ou GIF             |

### Descoberta

| Action                           | Descrição                                  |
|----------------------------------|--------------------------------------------|
| `uailove_get_discovery_profiles` | Retorna perfis para o feed de descoberta   |
| `uailove_send_like`              | Registra like/super like em um perfil      |

### Matches

| Action                   | Descrição                                  |
|--------------------------|--------------------------------------------|
| `uailove_get_user_matches` | Lista matches confirmados do usuário     |
| `uailove_send_match`       | Registra match manualmente (admin/debug) |

### Perfil

| Action                        | Descrição                                  |
|-------------------------------|--------------------------------------------|
| `uailove_update_profile`      | Salva dados do perfil (bio, interesses, etc.) |
| `uailove_update_photos_array` | Reordena ou atualiza array de fotos        |
| `uailove_upload_photo`        | Faz upload de nova foto de perfil          |
| `uailove_remove_photo`        | Remove foto do perfil                      |

### Notificações

| Action                           | Descrição                                  |
|----------------------------------|--------------------------------------------|
| `uailove_get_notifications`      | Lista notificações do usuário              |
| `uailove_clear_notifications`    | Remove todas as notificações               |
| `uailove_delete_notification`    | Remove notificação específica              |
| `uailove_mark_notifications_read`| Marca notificações como lidas              |

### Long Polling

| Action                  | Descrição                                               |
|-------------------------|---------------------------------------------------------|
| `uailove_long_polling`  | Aguarda novos eventos (mensagens, matches, notificações)|

### Loja

| Action             | Descrição                              |
|--------------------|----------------------------------------|
| `uailove_add_to_cart` | Adiciona mimo ao carrinho com destinatário |
| `uailove_get_recipients` | Lista matches disponíveis para receber mimo |
| `uailove_buy_gift` | Processa compra e envio de mimo        |

### Sessão

| Action                        | Descrição                                |
|-------------------------------|------------------------------------------|
| `uailove_check_session`       | Verifica se usuário está logado (JS)     |
| `uailove_send_message`        | Envio simples de mensagem (legado)       |

### Mídia (LoveMedia)

| Action                 | Descrição                              |
|------------------------|----------------------------------------|
| `lovemedia_query`      | Query de mídia do usuário              |
| `lovemedia_upload`     | Upload de nova mídia                   |
| `lovemedia_delete`     | Delete de mídia                        |
| `lovemedia_filters`    | Filtros de data para mídia             |

### Admin

| Action                             | Descrição                                   |
|------------------------------------|---------------------------------------------|
| `uailove_admin_get_users`          | Lista todos os usuários                     |
| `uailove_admin_save_user`          | Cria/atualiza usuário                       |
| `uailove_admin_delete_user`        | Deleta usuário                              |
| `uailove_admin_get_user_details`   | Detalhes de um usuário                      |
| `uailove_admin_impersonate`        | Impersona um usuário                        |
| `uailove_admin_stop_impersonating` | Retorna ao admin original                   |
| `uailove_admin_generate_login_token` | Gera token de login para usuário         |
| `uailove_admin_save_settings`      | Salva configurações admin                   |
| `uailove_admin_get_settings`       | Obtém configurações admin                   |
| `uailove_admin_save_pwa`           | Salva configurações PWA                     |
| `uailove_admin_get_pwa`            | Obtém configurações PWA                     |
| `uailove_admin_restore_pwa`        | Restaura configurações PWA padrão           |
| `uailove_admin_bulk_create_users`  | Cria múltiplos usuários em lote             |

---

## WebSocket

### Arquitetura

O sistema WebSocket usa um **servidor Node.js externo** com SSL, acessível via URL configurável. O Nginx atua como reverse proxy com terminação SSL, repassando conexões para o processo Node.js na porta local.

### Cliente (`websocket-service.js`)

A classe `WPWebSocketService` é instanciada como singleton global em `window.UaiLoveWS`.

```javascript
window.UaiLoveWS.init({ userId: 42 });
window.UaiLoveWS.on('message', (data) => { /* ... */ });
window.UaiLoveWS.send({ type: 'chat_message', text: 'Olá!' });
```

**Configurações:**

| Parâmetro            | Valor padrão | Descrição                              |
|----------------------|--------------|----------------------------------------|
| `RECONNECT_DELAY`    | 3000ms       | Intervalo entre tentativas de reconexão |
| `MAX_RECONNECTS`     | 5            | Máximo de tentativas antes de ir offline |
| `HEARTBEAT_INTERVAL` | 30000ms      | Intervalo do ping keepalive            |

**Estados do modo:**
- `disconnected` — sem conexão ativa
- `websocket` — conexão ativa e funcionando
- `offline` — máximo de tentativas atingido

**Handshake de autenticação:**
```json
{ "type": "init", "userId": "42" }
```

**Fallback:** Quando o WebSocket falha, o sistema usa long polling via `uailove_long_polling` para receber novas mensagens e matches.

### Painel Admin (`websocket-admin.js`)

Exibe logs e estatísticas do servidor WebSocket em tempo real:
- Conexões ativas, uptime, total de conexões
- Filtros de log por nível: `INFO`, `WARN`, `ERROR`, `SUCCESS`, `RAW`
- Auto-scroll com opção de pausa
- Fallback para polling AJAX se WebSocket indisponível

### PHP

A URL do WebSocket é configurável via filter WordPress:

```php
add_filter('uailove_ws_url', function() {
    return 'wss://websocket.moedadetroka.com.br/';
});
```

O modo de operação (`websocket` ou `polling`) é configurado via `get_option('uailove_ws_mode')`.

---

## LoveMedia — Gerenciamento de Mídia

Sistema de gerenciamento de mídia do usuário com interface AJAX.

### Funcionalidades
- Query paginada de imagens do usuário (20 por página)
- Upload via `media_handle_upload` do WordPress
- Delete com verificação de proprietário
- Filtros por ano/mês
- Pesquisa por título
- Cropper.js integrado para recorte antes do upload

### Configurações (`LoveMediaSettingsModel`)

| Parâmetro       | Padrão | Descrição                     |
|-----------------|--------|-------------------------------|
| `min_width`     | 400    | Largura mínima da imagem      |
| `min_height`    | 400    | Altura mínima da imagem       |
| `max_width`     | 4096   | Largura máxima da imagem      |
| `max_height`    | 4096   | Altura máxima da imagem       |
| `min_quality`   | 70     | Qualidade mínima (compressão) |
| `enable_crop`   | yes    | Habilitar recorte             |

---

## Autenticação

### Login / Cadastro

Processado via `AuthController` e `AuthModel`:
- Login com e-mail ou nome de usuário
- Cadastro com e-mail, nome opcional e senha
- Geração automática de username a partir do nome ou e-mail
- Auto-login após cadastro (configurável)
- Recuperação de senha por e-mail com link de redefinição

### Google OAuth

- Integração com Google Identity Services (`/gsi/client`)
- Configurado via `google_client_id` nas opções de autenticação
- Criação automática de conta se e-mail não existir
- Vinculação do `google_id` ao usuário

### Token Login

- Admins podem gerar tokens de login para qualquer usuário
- Tokens expiram em 15 minutos
- Consumidos automaticamente ao acessar `/token/{token}/`

---

## Admin

### Funcionalidades
- **Usuários**: Lista, detalhes, criar, editar, deletar, impersonar
- **Pontos**: Lista, criar, editar, deletar
- **Configurações**: Autenticação (Google Client ID, auto-login, redirects), GIFs
- **PWA**: Nome, short name, cores, ícone
- **LoveMedia**: Dimensões, qualidade, crop
- **WebSocket**: Logs filtrados, estatísticas, conexões ativas
- **Admin Switch**: Impersona qualquer usuário e retorna ao admin original via session

### Impersonação

O admin pode assumir a identidade de qualquer usuário:
1. Gera sessão com `uailove_original_admin` contendo o ID do admin
2. Cookie `uailove_original_admin` é definido para o JS detectar
3. Script `admin-switch.js` exibe botão "Voltar ao Admin"
4. `stop_impersonating()` restaura a sessão do admin original

---

## WooCommerce / Loja de Mimos

### Setup
- `SystemSetupService` renomeia labels de `product` para "Mimo" e `shop_order` para "Mimo Enviado"
- Role `lojista` criada com permissões para gerenciar produtos

### Fluxo de Compra
1. Usuário seleciona mimo e destinatário (match)
2. Destinatário é armazenado na sessão do WooCommerce
3. Produto adicionado ao carrinho com metadado do destinatário
4. No checkout, destinatário é salvo como meta do pedido
5. Ao confirmar pagamento, notificação é enviada:
   - Mensagem no chat entre comprador e destinatário
   - Notificação push via `NotificationQueueModel`
   - Post de notificação criado

### Modal de Checkout
CSS específico em `checkout-modal-box.css` para customizar a experiência de compra de mimos.

---

## Pontos de Encontro

### Estrutura
- CPT: `point` com suporte a title, editor, thumbnail, author
- Taxonomias: `point_category` (hierárquica) e `point_vibe` (não hierárquica)

### Metadados
| Campo                    | Tipo     | Descrição                              |
|--------------------------|----------|----------------------------------------|
| `event_short_description`| textarea | Descrição curta do ponto               |
| `event_location_name`    | text     | Nome amigável do local                 |
| `event_address`          | text     | Endereço físico completo               |
| `event_date_display`     | text     | Data de exibição (ex: "24 Fev")        |
| `event_full_date`        | date     | Data real do evento                    |
| `event_hero_image`       | image    | Imagem de destaque                     |
| `event_icon`             | text     | Classe Font Awesome (ex: fa-music)     |
| `event_latitude`         | text     | Latitude para mapa                     |
| `event_longitude`        | text     | Longitude para mapa                    |
| `event_main_liked_name`  | text     | Nome de quem curtiu o ponto            |
| `event_extra_liked_count`| number   | Contagem extra de curtidas             |

### Mapa
- Leaflet.js via CDN (`unpkg.com/leaflet@1.9.4`)
- REST API para geocoding via OpenCage

---

## PWA

Gerenciado via `PwaController` e `PwaModel`:

### Funcionalidades
- Manifest JSON dinâmico via rota `/manifest.json`
- Service Worker registration via `/sw.js`
- Meta tag `theme_color`
- `apple-touch-icon` para iOS
- Configurável pelo admin (nome, cores, ícone)

### Configurações Padrão
Gerenciadas por `PwaModel::get_defaults()`, editáveis no painel admin com opção de restore.

---

## Assets JavaScript

Localizados em `app/assets/javascripts/`:

| Arquivo                | Descrição                                                        |
|------------------------|------------------------------------------------------------------|
| `admin-switch.js`      | Botão de retorno ao admin após impersonação                      |
| `admin.js`             | Painel administrativo (usuários, pontos, stats, ações em massa)  |
| `auth.js`              | Autenticação: login, cadastro, Google OAuth, recuperação de senha|
| `back-button.js`       | Botão voltar da SPA com suporte a history API                    |
| `chat-redirect.js`     | Redireciona para chat específico quando recebe notificação       |
| `chat.js`              | Interface de chat: renderização, scroll, GIFs                    |
| `customizer.js`        | Preview em tempo real no Customizer do WordPress                 |
| `discovery.js`         | Feed de descoberta: swipe, like, animações de cartão             |
| `file-upload.js`       | Upload genérico com preview e validação                          |
| `gallery-upload.js`    | Upload para galeria de fotos do perfil                           |
| `image-upload.js`      | Upload de foto de perfil com crop                                |
| `lovemedia.js`         | Gerenciador de mídia: query, upload, delete, crop                |
| `navigation.js`        | Navegação entre seções da SPA, gestão do histórico (pushState)   |
| `notifications.js`     | Renderização e gestão de notificações em tempo real              |
| `points.js`            | Mapa (Leaflet) e listagem de pontos de encontro                  |
| `profile.js`           | Formulário de edição de perfil, interesses, galeria de fotos     |
| `realpoint-data.js`    | Dados de pontos reais para exibição no mapa                      |
| `script.js`            | Inicialização global da aplicação (depende de todos os outros)   |
| `section-back.js`      | Navegação entre subseções do perfil                              |
| `session-check.js`     | Verificação periódica de sessão (AJAX)                           |
| `shop.js`              | Loja de mimos: listagem, compra, seleção de destinatário         |
| `ui-feedback.js`       | Notificações toast não-intrusivas (success, error, info)         |
| `websocket-admin.js`   | Painel admin do WebSocket: logs filtrados, estatísticas          |
| `websocket-service.js` | `WPWebSocketService` — cliente WebSocket singleton               |

### Dependências JS Externas

| Biblioteca    | CDN                                          | Versão  |
|---------------|----------------------------------------------|---------|
| Leaflet       | `unpkg.com/leaflet@1.9.4/dist/leaflet.js`    | 1.9.4   |
| Cropper.js    | `cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js` | 1.6.2 |
| Google Identity | `accounts.google.com/gsi/client`           | latest  |

---

## Assets CSS

Localizados em `app/assets/stylesheets/`:

| Arquivo             | Descrição                                                   |
|---------------------|-------------------------------------------------------------|
| `admin.css`         | Layout e estilos do painel administrativo                   |
| `auth.css`          | Formulários de autenticação (login, cadastro)               |
| `base.css`          | Variáveis CSS, reset, tipografia base                       |
| `chat.css`          | Bolhas de mensagem, avatares, input de chat                 |
| `checkout-modal-box.css` | Modal de checkout de mimos (WooCommerce)              |
| `desktop.css`       | Adaptações de layout para telas maiores (≥ 768px)           |
| `discovery.css`     | Cards de perfil, animações de swipe, botões de ação         |
| `error.css`         | Páginas de erro 404 e afins                                 |
| `layout.css`        | Grid principal da SPA, containers, seções                   |
| `lovemedia.css`     | Gerenciador de mídia do usuário (grid, modal)               |
| `notifications.css` | Lista de notificações, badges, estados de leitura           |
| `points.css`        | Cards de pontos de encontro, mapa                           |
| `profile-tabs.css`  | Tabs de navegação da seção de perfil                        |
| `profile.css`       | Formulário de perfil, galeria de fotos, campos              |
| `shop.css`          | Grid de mimos, cards de produto, histórico de pedidos       |
| `style.css`         | Estilos globais da aplicação (theme root)                   |
| `toast.css`         | Posicionamento e animação de toasts                         |

### Dependências CSS Externas

| Biblioteca    | CDN                                                       | Versão  |
|---------------|-----------------------------------------------------------|---------|
| Google Fonts  | `fonts.googleapis.com/css2?family=Outfit:wght@300..700`   | -       |
| Font Awesome  | `cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css` | 6.5.1 |
| MDI Icons     | `cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css` | 7.4.47 |
| Leaflet       | `unpkg.com/leaflet@1.9.4/dist/leaflet.css`                | 1.9.4   |
| Cropper.js    | `cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css` | 1.6.2 |

---

## Custom Post Types

Registrados por `SystemSetupService` via `CptFactoryService`:

| CPT                  | Label                 | Descrição                                         |
|----------------------|-----------------------|---------------------------------------------------|
| `point`              | Pontos de Encontro    | Locais físicos para encontros (com meta boxes)    |
| `notification`       | Notificações          | Notificações persistentes por usuário             |
| `user_like`          | Curtidas de Usuários  | Likes/matches com metadados (score, distância)    |
| `chat`               | Chats                 | Conversas entre usuários com mensagens em JSON    |
| `uailove_gif_cache`  | Cache de GIFs         | Cache de resultados da Tenor API                  |

### Taxonomias

| Taxonomy          | CPT    | Tipo           | Descrição                  |
|-------------------|--------|----------------|----------------------------|
| `point_category`  | `point`| Hierárquica    | Categorias de pontos       |
| `point_vibe`      | `point`| Não hierárquica| Vibes/atmosfera dos pontos |

### WooCommerce
Os tipos `product` e `shop_order` têm seus labels renomeados para "Mimo" e "Mimo Enviado" respectivamente pelo `SystemSetupService`.

---

## Campos de Perfil

Gerenciados por `ProfileFieldDefinitionModel` e registrados via `UserMetaFactoryService`. Organizados em 6 grupos:

### Básico
- `uailove_photos_array` — Galeria de Fotos (gallery, JSON)
- `uailove_name` — Nome
- `uailove_gender` — Sexo (Masculino/Feminino)
- `uailove_birthdate` — Data de Nascimento
- `uailove_interest_gender` — Interesse em (Mulheres/Homens/Ambos)

### Localização
- `uailove_location` — Cidade (ex: Belo Horizonte, MG)
- `uailove_regional` — Regional de BH (Barreiro, Centro-Sul, Leste, etc.)
- `uailove_bairro` — Bairro (ex: Savassi)

### Sobre Mim
- `uailove_bio` — Bio curta
- `uailove_bio_long` — Bio detalhada
- `uailove_zodiac` — Signo do Zodíaco (12 signos)

### Perfil Físico
- `uailove_height` — Altura (cm)
- `uailove_weight` — Peso (kg)
- `uailove_body_type` — Tipo Físico (Magro, Atlético, Médio, Acima do peso)
- `uailove_eye_color` — Cor dos Olhos
- `uailove_hair_color` — Cor do Cabelo
- `uailove_hair_type` — Tipo de Cabelo
- `uailove_skin_tone` — Tom de Pele
- `uailove_makeup` — Uso de Maquiagem (feminino)
- `uailove_beard` — Barba (masculino)
- `uailove_tattoos` — Tatuagens
- `uailove_piercings` — Piercings
- `uailove_fitness_activity` — Pratica atividade física?
- `uailove_fitness_type` — Tipo de Atividade
- `uailove_clothing_style` — Estilo de Roupa

### Perfil Psicológico
- `uailove_personality_traits` — Traços de Personalidade
- `uailove_social_interaction` — Interação Social
- `uailove_temperament` — Temperamento
- `uailove_communication_style` — Estilo de Comunicação
- `uailove_conflict_resolution` — Resolução de Conflitos
- `uailove_affection_display` — Demonstração de Afeto
- `uailove_romance_level` — Nível de Romantismo
- `uailove_attachment_style` — Tipo de Vínculo
- `uailove_jealousy_level` — Ciúmes
- `uailove_core_values` — Valores Principais
- `uailove_life_vision` — Visão de Vida
- `uailove_religion` — Espiritualidade / Religião
- `uailove_routine` — Rotina
- `uailove_humor_type` — Tipo de Humor
- `uailove_future_plans` — Planos Futuros
- `uailove_current_phase` — Momento Atual

### Outras Informações
- `uailove_education` — Educação
- `uailove_smoking` — Fuma?
- `uailove_instagram_link` — Link do Instagram
- `uailove_interests` — Interesses (checkbox_group: 16 opções como Viagens, Café, Pets, Música, etc.)
- `uailove_liked_profiles` — Perfis Curtidos (IDs, uso interno)

---

## Scripts de Desenvolvimento

Scripts utilitários em `app/lib/tasks/`. Executados via WP-CLI ou include no wp-admin:

| Script                      | Descrição                                                    |
|-----------------------------|--------------------------------------------------------------|
| `generate-mock-users.php`   | Gera N usuários fictícios com perfis completos               |
| `generate-more-matches.php` | Gera matches adicionais entre usuários existentes            |
| `generate-mock-mimos.php`   | Cria produtos de mimo no WooCommerce para testes             |
| `generate-points.php`       | Insere pontos de encontro com dados fictícios                |
| `assign-coords.php`         | Atribui coordenadas geográficas a pontos sem lat/lng         |
| `attach-images.php`         | Associa imagens de placeholder a usuários mock               |
| `import-gifts.php`          | Importa lista de gifts para a loja WooCommerce               |
| `cleanup-mock-data.php`     | Remove todos os dados mock do banco (usuários, matches, etc.)|
| `update_assets_path.php`    | Corrige caminhos de assets após migração de estrutura        |
| `update-interests.php`      | Atualiza/normaliza interesses de usuários existentes         |
| `test-images.php`           | Testa o pipeline de upload e redimensionamento de imagens    |
| `insert-kinoko-marvic.php`  | Insere usuários de teste específicos (Kinoko e Marvic)       |

---

## Configuração de Desenvolvimento

### Pré-requisitos

- [Local by Flywheel](https://localwp.com/) ou similar para ambiente WordPress local
- Node.js ≥ 14 e npm
- PHP ≥ 8.0 com Composer

### Instalação

```bash
# 1. Clone dentro do diretório de temas do WordPress local
cd /caminho/para/local-sites/uailove/app/public/wp-content/themes/
git clone https://github.com/WillGolden80742/uailove uailove

# 2. Instale dependências PHP (OpenCage Geocoder)
cd uailove
composer install

# 3. Instale dependências npm (para compilar SCSS)
npm install
```

### Scripts npm

| Comando             | Descrição                                         |
|---------------------|---------------------------------------------------|
| `npm run watch`     | Compila SCSS e observa alterações (modo dev)      |
| `npm run compile:css` | Compila SCSS para CSS final                     |
| `npm run compile:rtl` | Gera versão RTL do style.css                   |
| `npm run lint:scss`   | Lint dos arquivos SCSS                          |
| `npm run lint:js`     | Lint dos arquivos JavaScript                    |
| `npm run bundle`      | Gera `.zip` do tema para distribuição           |

---

## Variáveis de Ambiente

O `EnvService` lê o arquivo `.env` na raiz do tema:

```env
# Google OAuth
GOOGLE_CLIENT_ID=seu-client-id.apps.googleusercontent.com

# OpenCage Geocoding
OPENCAGE_API_KEY=sua-chave-aqui
```

Constantes WordPress (definidas em `wp-config.php`):

```php
// URL do servidor WebSocket
define('UAILOVE_WS_URL', 'wss://websocket.moedadetroka.com.br/');

// Modo do WebSocket (websocket|polling)
define('UAILOVE_WS_MODE', 'websocket');
```

---

## Dependências Externas

### PHP (Composer)
| Pacote                    | Versão | Descrição                          |
|---------------------------|--------|------------------------------------|
| `opencage/geocode`        | 2.1.0  | Geocoding via OpenCage API         |

### JavaScript (CDN)
| Biblioteca        | Versão | Descrição                          |
|-------------------|--------|------------------------------------|
| Leaflet           | 1.9.4  | Mapas interativos                  |
| Cropper.js        | 1.6.2  | Recorte de imagens                 |
| Google Identity   | latest | Login com Google                   |

### CSS (CDN)
| Biblioteca        | Versão | Descrição                          |
|-------------------|--------|------------------------------------|
| Google Fonts      | -      | Fonte Outfit (300-700)             |
| Font Awesome      | 6.5.1  | Ícones                             |
| MDI Icons         | 7.4.47 | Material Design Icons              |
| Leaflet           | 1.9.4  | Estilos do mapa                    |
| Cropper.js        | 1.6.2  | Estilos do recorte                 |

### APIs Externas
| API              | Uso                                      |
|------------------|------------------------------------------|
| Google OAuth     | Login social com Google                  |
| OpenCage         | Geocoding de endereços para pontos       |
| Tenor            | Busca de GIFs para o chat                |
| WooCommerce      | Loja de mimos (plugin obrigatório)       |

---

## Requisitos

| Requisito            | Versão mínima | Observação                                    |
|----------------------|---------------|-----------------------------------------------|
| PHP                  | 8.0+          |                                               |
| WordPress            | 5.4+          |                                               |
| WooCommerce          | 5.0+          | Obrigatório para a loja de mimos              |
| Node.js (servidor)   | 16+           | Para o servidor WebSocket externo             |
| Nginx                | qualquer      | Reverse proxy com SSL para o WebSocket        |
| Composer             | qualquer      | Para dependência OpenCage Geocode             |

---

## Créditos

- **Tema Base**: Underscores (_s) — https://underscores.me/
- **Normalize CSS**: Nicolas Gallagher e Jonathan Neal — https://necolas.github.io/normalize.css/
- **Autor**: William Dourado — https://github.com/WillGolden80742/uailove
- **Licença**: GNU General Public License v2 or later — ver arquivo `LICENSE`
