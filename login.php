<?php
require __DIR__.'/includes/bootstrap.php';
if (is_logged()) { header('Location: '.base_path('admin/index.php')); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $login=trim($_POST['login']??''); $pass=$_POST['password']??'';
    try {
        $st=db()->prepare('SELECT * FROM users WHERE email=? LIMIT 1'); $st->execute([$login]); $u=$st->fetch();
        if ($u && password_verify($pass,$u['password'])) { set_auth_cookie($u); header('Location: '.base_path('admin/index.php')); exit; }
        $error='Login ou senha inválidos.';
    } catch(Throwable $e) { $error='Banco de dados ainda não configurado/inicializado.'; }
}
?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login CEET Bot</title><link rel="stylesheet" href="<?=e(base_path('assets/style.css'))?>"></head><body><div class="login-wrap"><div class="login-card"><h1>🎓 CEET Bot</h1><p class="muted">Painel administrativo do Curso Técnico</p><?php if($error):?><div class="flash err"><?=e($error)?></div><?php endif;?><form method="post"><div class="field"><label>Usuário</label><input name="login" value="admin" required></div><br><div class="field"><label>Senha</label><input type="password" name="password" required></div><br><button class="btn">Entrar</button> <a class="btn secondary" href="<?=e(base_path())?>">Ver portal</a></form></div></div></body></html>
