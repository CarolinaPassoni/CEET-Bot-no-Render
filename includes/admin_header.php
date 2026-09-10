<?php
require_once __DIR__.'/bootstrap.php'; require_login();
$current=basename($_SERVER['PHP_SELF']);
$nav=[
 'index.php'=>'Dashboard','crud.php?e=courses'=>'Cursos','crud.php?e=classes'=>'Turmas','crud.php?e=professors'=>'Professores','crud.php?e=subjects'=>'Disciplinas','crud.php?e=schedules'=>'Horários','crud.php?e=activities'=>'Atividades','crud.php?e=materials'=>'Materiais','crud.php?e=projects'=>'Projeto Interdisciplinar','crud.php?e=internships'=>'Estágio','crud.php?e=events'=>'Eventos','whatsapp.php'=>'WhatsApp','users.php'=>'Usuários'
];
?><!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($pageTitle??'CEET Bot')?></title><link rel="stylesheet" href="<?=e(base_path('assets/style.css'))?>"></head><body><div class="layout"><aside class="side"><div class="brand">🎓 CEET Bot<small>Curso Técnico</small></div><nav class="nav"><?php foreach($nav as $href=>$label):?><a href="<?=e(base_path('admin/'.$href))?>"><?=e($label)?></a><?php endforeach;?><a href="<?=e(base_path())?>">Portal público</a><a href="<?=e(base_path('logout.php'))?>">Sair</a></nav></aside><main class="main">
