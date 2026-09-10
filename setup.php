<?php
require __DIR__.'/includes/bootstrap.php';
$error=''; $success='';
$ready = (!empty($config['db']['url']) || (!empty($config['db']['host']) && !empty($config['db']['name']) && !empty($config['db']['user']))) && !empty($config['app']['secret']) && !empty($config['app']['setup_key']);
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $provided = (string)($_POST['setup_key'] ?? '');
    try {
        if (!$ready) throw new RuntimeException('Configure primeiro DATABASE_URL, APP_SECRET e SETUP_KEY no Render.');
        if (!hash_equals((string)$config['app']['setup_key'], $provided)) throw new RuntimeException('SETUP_KEY inválida.');
        $pdo=db();
        $sql=file_get_contents(__DIR__.'/sql/schema.sql');
        $pdo->exec($sql);
        $exists=$pdo->prepare('SELECT id FROM users WHERE email=? LIMIT 1');
        $exists->execute(['admin']);
        if(!$exists->fetchColumn()){
            $hash=password_hash('Admin@123',PASSWORD_DEFAULT);
            $st=$pdo->prepare("INSERT INTO users(name,email,password,role) VALUES('Administrador','admin',?,'admin')");
            $st->execute([$hash]);
        }
        $success='Banco inicializado. Login inicial: admin | Senha: Admin@123. Troque a senha criando outro administrador e removendo o padrão.';
    } catch(Throwable $e){ $error=$e->getMessage(); }
}
?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Inicializar CEET Bot</title><link rel="stylesheet" href="<?=e(base_path('assets/style.css'))?>"></head><body><div class="login-wrap"><div class="login-card"><h1>🚀 Inicializar CEET Bot</h1><p class="muted">Esta edição usa PostgreSQL e variáveis de ambiente do Render.</p><?php if($error):?><div class="flash err"><?=e($error)?></div><?php endif;?><?php if($success):?><div class="flash ok"><?=e($success)?></div><a class="btn" href="<?=e(base_path('login.php'))?>">Entrar no painel</a><?php else:?><form method="post"><div class="field"><label>SETUP_KEY</label><input name="setup_key" type="password" required></div><br><button class="btn">Criar tabelas e administrador</button></form><br><p class="muted">Variáveis obrigatórias: DATABASE_URL, APP_SECRET e SETUP_KEY. O Blueprint do Render configura DATABASE_URL e APP_SECRET automaticamente.</p><?php endif;?></div></div></body></html>
