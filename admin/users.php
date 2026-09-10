<?php
$pageTitle='Usuários'; require __DIR__.'/../includes/admin_header.php'; verify_csrf();
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=trim($_POST['name']??'');$email=trim($_POST['email']??'');$pass=$_POST['password']??'';
    if($name&&$email&&$pass){$st=db()->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,\'admin\')');$st->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT)]);flash('Usuário criado.');}
    header('Location: '.base_path('admin/users.php'));exit;
}
if(isset($_GET['delete'])){$id=(int)$_GET['delete'];if($id!==current_user_id()){db()->prepare('DELETE FROM users WHERE id=?')->execute([$id]);flash('Usuário excluído.');}header('Location: '.base_path('admin/users.php'));exit;}
$rows=db()->query('SELECT id,name,email,role,created_at FROM users ORDER BY id')->fetchAll();
?>
<div class="top"><div class="title"><h1>Usuários administradores</h1><p>Crie acessos para coordenação e equipe.</p></div></div><?php render_flash();?><div class="card"><h2>Novo usuário</h2><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><div class="form-grid"><div class="field"><label>Nome</label><input name="name" required></div><div class="field"><label>Login</label><input name="email" required></div><div class="field"><label>Senha</label><input type="password" name="password" required></div></div><br><button class="btn">Criar usuário</button></form></div><br><div class="card"><table><tr><th>Nome</th><th>Login</th><th>Perfil</th><th></th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['name'])?></td><td><?=e($r['email'])?></td><td><?=e($r['role'])?></td><td><?php if((int)$r['id']!==current_user_id()):?><a class="btn danger" onclick="return confirm('Excluir usuário?')" href="?delete=<?=e($r['id'])?>">Excluir</a><?php endif;?></td></tr><?php endforeach;?></table></div>
<?php require __DIR__.'/../includes/admin_footer.php';?>
