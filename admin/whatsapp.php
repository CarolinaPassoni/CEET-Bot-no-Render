<?php
$pageTitle='WhatsApp'; require __DIR__.'/../includes/admin_header.php'; verify_csrf();
if($_SERVER['REQUEST_METHOD']==='POST'){
    foreach(['wa_verify_token','wa_access_token','wa_phone_number_id','wa_api_version','school_name'] as $k) save_setting($k,trim($_POST[$k]??''));
    flash('Configurações do WhatsApp salvas.'); header('Location: '.base_path('admin/whatsapp.php')); exit;
}
$webhook=base_path('webhook/whatsapp.php');
?>
<div class="top"><div class="title"><h1>WhatsApp</h1><p>Conecte a Cloud API oficial da Meta ao bot.</p></div></div><?php render_flash();?>
<div class="card"><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><div class="form-grid">
<div class="field"><label>Nome da escola/curso</label><input name="school_name" value="<?=e(setting('school_name','Curso Técnico'))?>"></div>
<div class="field"><label>Versão Graph API</label><input name="wa_api_version" value="<?=e(setting('wa_api_version','v23.0'))?>"></div>
<div class="field"><label>Phone Number ID</label><input name="wa_phone_number_id" value="<?=e(setting('wa_phone_number_id'))?>"></div>
<div class="field"><label>Verify Token</label><input name="wa_verify_token" value="<?=e(setting('wa_verify_token','ceet_bot_2026'))?>"></div>
<div class="field span2"><label>Access Token</label><textarea name="wa_access_token"><?=e(setting('wa_access_token'))?></textarea></div>
</div><br><button class="btn">Salvar configurações</button></form></div><br>
<div class="card"><h2>Webhook para cadastrar na Meta</h2><div class="code"><?=e($webhook)?></div><p class="muted">Use o mesmo Verify Token informado acima. Assine o evento <strong>messages</strong>.</p></div>
