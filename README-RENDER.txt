CEET BOT - RENDER.COM
======================

Esta versão foi preparada para Render com:
- PHP 8.3 + Apache via Docker
- PostgreSQL nativo do Render
- Blueprint render.yaml
- Painel administrativo
- Webhook oficial do WhatsApp/Meta

DEPLOY RECOMENDADO
1. Crie um repositório GitHub e envie todo o conteúdo desta pasta para a raiz.
2. No Render, escolha New > Blueprint.
3. Selecione o repositório.
4. O Render lerá render.yaml e criará:
   - Web Service: ceet-bot
   - PostgreSQL: ceet-bot-db
5. Quando solicitado, informe SETUP_KEY (crie uma senha longa só para instalação).
6. Aguarde o deploy.
7. Abra: https://SEU-ENDERECO.onrender.com/setup.php
8. Digite a mesma SETUP_KEY e inicialize o banco.
9. Entre em /login.php.

LOGIN INICIAL
Usuário: admin
Senha: Admin@123
Troque o acesso padrão assim que possível.

WHATSAPP
No painel > WhatsApp, informe:
- Phone Number ID
- Access Token
- Verify Token
- versão da Graph API

Webhook:
https://SEU-ENDERECO.onrender.com/webhook/whatsapp.php

Variáveis do Render:
- DATABASE_URL: criada automaticamente pelo Blueprint
- APP_SECRET: gerada automaticamente pelo Blueprint
- SETUP_KEY: definida por você
- PORT: 10000

Observação:
O plano gratuito do Render pode ter limitações e políticas de disponibilidade/expiração. Consulte os planos atuais antes de uso institucional em produção.
