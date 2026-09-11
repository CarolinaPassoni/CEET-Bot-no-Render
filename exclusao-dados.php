<?php
require __DIR__.'/includes/bootstrap.php';
$school = setting('school_name','CEET Bot');
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Exclusão de Dados - <?=e($school)?></title>
<link rel="stylesheet" href="<?=e(base_path('assets/style.css'))?>">
</head>
<body>
<div class="main" style="max-width:900px;margin:auto;padding:32px 20px">
  <div class="hero" style="margin-bottom:24px">
    <span class="badge">LGPD</span>
    <h1>Solicitação de Exclusão de Dados</h1>
    <p><?=e($school)?></p>
  </div>
  <div class="public-card">
    <h2>Como solicitar</h2>
    <p>O titular de dados, ou seu responsável legal quando aplicável, pode solicitar a exclusão de dados pessoais tratados pelo <?=e($school)?>.</p>
    <h2>Procedimento</h2>
    <ol>
      <li>Entre em contato com a administração do CEET Giuseppe Altoé por um canal institucional oficialmente divulgado pela escola.</li>
      <li>Informe que deseja exercer o direito de exclusão de dados relacionado ao CEET Bot.</li>
      <li>Informe apenas os dados necessários para localizar o cadastro, como nome e número de WhatsApp utilizado no bot.</li>
      <li>A instituição poderá solicitar confirmação de identidade para evitar exclusão indevida de dados de terceiros.</li>
    </ol>
    <h2>Prazo e limitações</h2>
    <p>A solicitação será analisada e atendida conforme a LGPD e as normas aplicáveis. Alguns dados poderão ser mantidos quando houver obrigação legal, regulatória, necessidade de exercício regular de direitos ou outra hipótese legal que justifique a retenção.</p>
    <h2>O que pode ser excluído</h2>
    <p>Quando aplicável, poderão ser excluídos ou anonimizados registros de mensagens, número de telefone associado ao atendimento, dados de interação e outros dados pessoais mantidos exclusivamente para o funcionamento do bot.</p>
    <h2>Revogação do uso do WhatsApp</h2>
    <p>O usuário também pode interromper a interação com o bot a qualquer momento, bloqueando o número no WhatsApp ou deixando de utilizar o serviço.</p>
    <p style="margin-top:28px">
      <a class="btn" href="<?=e(base_path('privacidade.php'))?>">Ver Política de Privacidade</a>
      <a class="btn" style="margin-left:8px" href="<?=e(base_path())?>">Voltar ao portal</a>
    </p>
  </div>
</div>
</body>
</html>
