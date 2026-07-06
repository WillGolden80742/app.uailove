=== Wordfence Activator ===
Contributors: UltraPack
Tags: security, firewall, malware, scanner, protection
Requires at least: 5.9
Tested up to: 6.4
Wordfence tested up to: 8.1.4
Stable tag: 2.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Este plugin ativa os recursos do nível Response no Wordfence Security.


== Descrição ==

O Wordfence Activator aprimora as capacidades do plugin Wordfence Security ativando os recursos do nível Response para proteger seu site WordPress contra ameaças.

Principais recursos incluem:
* Capacidades de varredura de malware do nível Response (nível mais alto disponível)
* Acesso a todas as regras premium do firewall
* Recursos avançados de bloqueio e proteção
* Opções de varredura estendidas
* Ativação de licença Response por 10 anos

Basta instalar junto com o plugin oficial Wordfence Security para proteção aprimorada.


== Instalação ==

1. Instale e ative o plugin oficial Wordfence Security do repositório WordPress.
2. Envie os arquivos do plugin para o diretório `/wp-content/plugins/wordfence-activator`, ou instale o plugin diretamente pela tela de plugins do WordPress.
3. Ative o plugin pela tela 'Plugins' no WordPress.
4. Os recursos do nível Response serão ativados automaticamente sem necessidade de configuração adicional.


== Tutorial de instalação de licença gratuita ==
Observação: A partir da 2.2.0 o ativador tenta ativar a licença gratuita automaticamente.

Para manter a instalação, clique na mensagem de aviso "Retomar instalação".

    Em primeiro lugar, você DEVE instalar uma licença gratuita em seu site, caso contrário, o produto NÃO funcionará!

Como instalar a licença gratuita:

    Ao instalar o plug-in ativador, certifique-se de ter o plug-in Wordfence ativado.
    Depois de instalar o Wordfence e o ativador, retome a instalação do Wordfence:
    Instalação do Wordfence
    Clique para obter uma licença. Você será redirecionado para a página do plugin onde será solicitado que você adquira uma licença, escolha a opção Free .
    Depois de obter uma licença, verifique seu e-mail para obter a chave e retorne ao WordPress.
    Agora instale a licença em Instalar uma licença existente, insira os dados e ative como mostrado na imagem:
    Instalar licença existente
    Depois que seu site for ativado conforme mostrado abaixo, o ativador ativará automaticamente a ativação premium para o item:
    Site ativado

O que fazer depois de instalar a versão de ativação gratuita:

    Depois de instalar a licença gratuita, mantenha o plug-in ativador ativado em seu site o tempo todo para que os recursos premium funcionem.


== Perguntas Frequentes ==

= Posso usar este plugin com a versão gratuita do Wordfence? =

Sim, este plugin é compatível com a versão gratuita do Wordfence. Ele aprimora a versão gratuita com os recursos do nível Response.

= É compatível com a versão mais recente do Wordfence? =

Sim, este plugin foi atualizado para funcionar com as versões recentes do Wordfence, incluindo a 8.0.5.

= Preciso configurar algo após a ativação? =

Não, o plugin funciona automaticamente após a ativação. Nenhuma configuração é necessária.

= O que devo fazer se a ativação não funcionar? =

Se você tiver problemas, tente desativar e reativar ambos os plugins nesta ordem:
1. Desative o Wordfence Activator
2. Desative o Wordfence Security
3. Reative o Wordfence Security
4. Reative o Wordfence Activator

== Como ocultar o plugin ==

Nosso ativador consta com uma funcionalidade para ocultar o plugin de aparecer na lista de plugins instalados, para ativar esta funcionalidade siga os seguintes passos:

== Como mudo meu email para receber notificações? ==

O ativador pode causar bypass desta opção, para alterar navegue até Wordfence -> All options -> General Wordfence Options e mude "Where to email alerts".

== Como faço para o ativador não inserir uma licença free sempre? ==

Na página de configurações/tutorial do plugin, desative a função "Auto License".


== Alterações ==

= 2.3.0 =
* Adicionado verificação de rate limit

= 2.2.1 =
* Alteração no nome do autor e traduções

= 2.2.0 =
* Correção de banco de dados inexistente
* Adicionado tentativa de registro de licença free automática
* Controle de auto tentativa de registro de licença free automática

= 2.1.0 =
* Correção erro strpos
* Correção página de tutorial com erro crítico
* Correção tradução não corretamente sendo carregada
* Adição opção para esconder o plugin

= 2.0.3 = 
* Correção auto remove para licença inválida

= 2.0.2 = 
* Correção para scan
* Inclusão de tutorial de ativação

= 2.0.1 =
* Correção no exclusor de scan

= 2.0.0 =
* Atualização de versão principal com melhorias significativas
* Alterado para usar a licença do nível Response (nível mais alto disponível)
* Adicionada geração e validação adequada de chave de API
* Melhorada a persistência da licença entre sessões
* Adicionada interceptação de chamadas de API para manter a verificação da licença
* Configuração do WAF aprimorada para regras premium
* Corrigida compatibilidade com o Wordfence 8.0.4
* Script escrito com classe

= 1.4.3 =
* Plugin WF Activator excluído de ser sinalizado pelo Wordfence

= 1.4.2 =
* Reformulado por UltraPack

= 1.0.0 =
* Lançamento inicial
