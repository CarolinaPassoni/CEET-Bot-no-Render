CEET BOT - CURSO TÉCNICO - HOSTINGER
====================================

TECNOLOGIA
- PHP 8+
- MySQL/MariaDB
- WhatsApp Business Platform / Cloud API da Meta
- Sem framework obrigatório

INSTALAÇÃO NA HOSTINGER
1. No hPanel, crie um novo site/subdomínio, por exemplo: bot.seudominio.com.br
2. Em Bancos de Dados, crie um banco MySQL e anote: host, nome, usuário e senha.
3. Envie TODO o conteúdo desta pasta para public_html do novo site.
4. Acesse https://seu-dominio/setup.php
5. Preencha os dados do MySQL e a URL do site.
6. O instalador cria as tabelas automaticamente.
7. Login inicial:
   usuário: admin
   senha: Admin@123
8. Após entrar, crie outro administrador com senha forte e exclua/altere o acesso inicial conforme sua política.

CONFIGURAÇÃO DO WHATSAPP
1. Crie um app em Meta for Developers e adicione WhatsApp.
2. Obtenha Phone Number ID e Access Token.
3. No CEET Bot > WhatsApp, informe os dados e defina um Verify Token.
4. Na Meta, configure Callback URL como:
   https://seu-dominio/webhook/whatsapp.php
5. Use o mesmo Verify Token e assine o campo "messages".
6. Teste enviando "menu" para o número conectado.

FUNCIONALIDADES
- Dashboard
- Cursos
- Turmas
- Professores
- Disciplinas
- Horários
- Atividades e prazos
- Materiais com links
- Projeto Interdisciplinar
- Estágio
- Eventos/calendário
- Usuários administradores
- Portal público responsivo
- Configuração da Cloud API
- Webhook com menu automático
- Histórico técnico de mensagens no banco

IMPORTANTE
- O Access Token do WhatsApp é um segredo. Restrinja o painel e use HTTPS.
- Em produção, prefira token permanente e revise permissões/segurança.
- Mensagens iniciadas pela escola podem exigir templates aprovados pela Meta conforme as regras vigentes.
- Para dados pessoais de alunos/notas/frequência, adicione autenticação forte e regras de LGPD antes de disponibilizar.
