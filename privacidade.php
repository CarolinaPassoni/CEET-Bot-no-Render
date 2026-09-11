<?php
require __DIR__.'/includes/bootstrap.php';
$school = setting('school_name','CEET Bot');
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Política de Privacidade - <?=e($school)?></title>
<link rel="stylesheet" href="<?=e(base_path('assets/style.css'))?>">
</head>
<body>
<div class="main" style="max-width:960px;margin:auto;padding:32px 20px">
  <div class="hero" style="margin-bottom:24px">
    <span class="badge">Privacidade e LGPD</span>
    <h1>Política de Privacidade do <?=e($school)?></h1>
    <p>Última atualização: 11 de setembro de 2026.</p>
  </div>
  <div class="public-card">
    <h2>1. Objetivo</h2>
    <p>Esta Política de Privacidade explica como o <?=e($school)?> trata dados pessoais utilizados para disponibilizar atendimento e informações acadêmicas por meio do portal e do WhatsApp Business Platform.</p>
    <h2>2. Dados que podem ser tratados</h2>
    <p>Dependendo do uso do serviço, poderão ser tratados: número de telefone, nome informado pelo usuário, conteúdo das mensagens enviadas ao bot, registros técnicos de interação, dados de curso, turma, atividades, horários, materiais, eventos e outras informações acadêmicas cadastradas pela instituição.</p>
    <p>O sistema não solicita, por padrão, senhas bancárias, dados de cartão, documentos pessoais sensíveis ou informações de saúde pelo WhatsApp. Usuários não devem enviar dados desnecessários ao atendimento.</p>
    <h2>3. Finalidades</h2>
    <p>Os dados são utilizados para responder solicitações, disponibilizar informações acadêmicas, apresentar horários, atividades, materiais, projetos, estágio e eventos, manter o funcionamento e a segurança do sistema, registrar interações necessárias ao suporte e cumprir obrigações legais ou institucionais aplicáveis.</p>
    <h2>4. Base legal e LGPD</h2>
    <p>O tratamento será realizado de acordo com a Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 - LGPD) e demais normas aplicáveis, utilizando a base legal adequada a cada finalidade e ao vínculo do usuário com a instituição.</p>
    <h2>5. Compartilhamento e operadores</h2>
    <p>Os dados poderão ser processados por fornecedores necessários à operação do serviço, incluindo a Meta/WhatsApp, serviços de hospedagem e infraestrutura em nuvem e provedores de banco de dados. Esses fornecedores recebem apenas os dados necessários para executar seus serviços, de acordo com seus respectivos termos e políticas.</p>
    <h2>6. Armazenamento e segurança</h2>
    <p>São adotadas medidas técnicas e administrativas razoáveis para reduzir riscos de acesso não autorizado, perda, alteração ou divulgação indevida. Os dados são mantidos apenas pelo período necessário às finalidades descritas, às rotinas institucionais e às obrigações legais aplicáveis.</p>
    <h2>7. Direitos do titular</h2>
    <p>Nos termos da LGPD, o titular poderá solicitar confirmação de tratamento, acesso, correção, atualização, informação sobre compartilhamento, anonimização, bloqueio ou eliminação quando aplicável, além de outros direitos previstos em lei.</p>
    <h2>8. Exclusão de dados</h2>
    <p>As instruções para solicitar exclusão de dados estão disponíveis em <a href="<?=e(base_path('exclusao-dados.php'))?>">Solicitação de exclusão de dados</a>.</p>
    <h2>9. Dados de crianças e adolescentes</h2>
    <p>Quando o serviço for utilizado por estudantes menores de idade, o tratamento deverá observar as regras institucionais e legais aplicáveis, priorizando o melhor interesse da criança ou do adolescente e restringindo o uso aos fins educacionais necessários.</p>
    <h2>10. Contato</h2>
    <p>Solicitações relacionadas à privacidade e aos dados pessoais devem ser encaminhadas à administração do CEET Giuseppe Altoé pelos canais institucionais oficialmente divulgados pela escola.</p>
    <h2>11. Alterações desta política</h2>
    <p>Esta política poderá ser atualizada para refletir mudanças no sistema, na legislação ou nas práticas institucionais. A versão vigente permanecerá disponível nesta página.</p>
    <p style="margin-top:28px"><a class="btn" href="<?=e(base_path())?>">Voltar ao portal</a></p>
  </div>
</div>
</body>
</html>
