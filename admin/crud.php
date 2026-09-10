<?php
$pageTitle='Cadastro'; require __DIR__.'/../includes/admin_header.php'; verify_csrf();
$entity=$_GET['e']??'courses';
$defs=[
'courses'=>['title'=>'Cursos','fields'=>['name'=>['Nome','text'],'workload'=>['Carga horária','text'],'description'=>['Descrição','textarea']]],
'classes'=>['title'=>'Turmas','fields'=>['name'=>['Nome','text'],'course_id'=>['Curso','select','courses','name'],'module'=>['Módulo','text'],'shift'=>['Turno','text'],'year'=>['Ano','text']]],
'professors'=>['title'=>'Professores','fields'=>['name'=>['Nome','text'],'email'=>['E-mail','email'],'phone'=>['Telefone','text'],'bio'=>['Observações','textarea']]],
'subjects'=>['title'=>'Disciplinas','fields'=>['name'=>['Nome','text'],'class_id'=>['Turma','select','classes','name'],'professor_id'=>['Professor','select','professors','name'],'workload'=>['Carga horária','text'],'description'=>['Descrição','textarea']]],
'schedules'=>['title'=>'Horários','fields'=>['class_id'=>['Turma','select','classes','name'],'subject_id'=>['Disciplina','select','subjects','name'],'weekday'=>['Dia da semana','text'],'start_time'=>['Início','time'],'end_time'=>['Fim','time'],'room'=>['Sala','text']]],
'activities'=>['title'=>'Atividades','fields'=>['title'=>['Título','text'],'subject_id'=>['Disciplina','select','subjects','name'],'class_id'=>['Turma','select','classes','name'],'description'=>['Descrição','textarea'],'due_date'=>['Prazo','date'],'points'=>['Pontos','number']]],
'materials'=>['title'=>'Materiais','fields'=>['title'=>['Título','text'],'subject_id'=>['Disciplina','select','subjects','name'],'class_id'=>['Turma','select','classes','name'],'material_type'=>['Tipo','text'],'url'=>['Link/URL','url'],'description'=>['Descrição','textarea']]],
'projects'=>['title'=>'Projeto Interdisciplinar','fields'=>['title'=>['Título','text'],'class_id'=>['Turma','select','classes','name'],'stage'=>['Etapa','text'],'due_date'=>['Prazo','date'],'points'=>['Pontos','number'],'description'=>['Descrição','textarea']]],
'internships'=>['title'=>'Estágio','fields'=>['title'=>['Título','text'],'class_id'=>['Turma','select','classes','name'],'company'=>['Empresa','text'],'deadline'=>['Prazo','date'],'contact'=>['Contato','text'],'description'=>['Descrição','textarea']]],
'events'=>['title'=>'Eventos','fields'=>['title'=>['Título','text'],'event_date'=>['Data e hora','datetime-local'],'location'=>['Local','text'],'audience'=>['Público','text'],'description'=>['Descrição','textarea']]],
'students'=>['title'=>'Alunos','fields'=>['name'=>['Nome','text'],'registration'=>['Matrícula','text'],'phone'=>['WhatsApp','text'],'class_id'=>['Turma','select','classes','name'],'active'=>['Ativo','select_static',['1'=>'Sim','0'=>'Não']]]]
];
if(!isset($defs[$entity])) exit('Cadastro inválido.'); $def=$defs[$entity]; $pageTitle=$def['title'];
$action=$_GET['action']??'list'; $id=(int)($_GET['id']??0);
if($_SERVER['REQUEST_METHOD']==='POST'){
    $data=[]; foreach($def['fields'] as $k=>$meta){$data[$k]=($_POST[$k]??'')!==''?$_POST[$k]:null;}
    if(!empty($_POST['id'])){ $sets=[];$vals=[];foreach($data as $k=>$v){$sets[]="$k=?";$vals[]=$v;}$vals[]=(int)$_POST['id'];$st=db()->prepare("UPDATE $entity SET ".implode(',',$sets).' WHERE id=?');$st->execute($vals);flash('Registro atualizado.');}
    else{$cols=array_keys($data);$st=db()->prepare("INSERT INTO $entity (".implode(',',$cols).") VALUES (".implode(',',array_fill(0,count($cols),'?')).")");$st->execute(array_values($data));flash('Registro cadastrado.');}
    header('Location: '.base_path('admin/crud.php?e='.$entity));exit;
}
if($action==='delete'&&$id){ db()->prepare("DELETE FROM $entity WHERE id=?")->execute([$id]); flash('Registro excluído.'); header('Location: '.base_path('admin/crud.php?e='.$entity)); exit; }
$row=[]; if(($action==='edit'||$action==='new')&&$id){$st=db()->prepare("SELECT * FROM $entity WHERE id=?");$st->execute([$id]);$row=$st->fetch()?:[];}
function optionsFor($meta,$value){
 if(($meta[1]??'')==='select'){ $rows=db()->query("SELECT id, {$meta[3]} AS label FROM {$meta[2]} ORDER BY {$meta[3]}")->fetchAll(); echo '<option value="">Selecione</option>'; foreach($rows as $o){$sel=(string)$value===(string)$o['id']?' selected':''; echo '<option value="'.e($o['id']).'"'.$sel.'>'.e($o['label']).'</option>';}}
 elseif(($meta[1]??'')==='select_static'){foreach($meta[2] as $k=>$lab){$sel=(string)$value===(string)$k?' selected':'';echo '<option value="'.e($k).'"'.$sel.'>'.e($lab).'</option>';}}
}
?>
<div class="top"><div class="title"><h1><?=e($def['title'])?></h1><p>Gerencie as informações usadas no portal e no bot.</p></div><?php if($action==='list'):?><a class="btn" href="<?=e(base_path('admin/crud.php?e='.$entity.'&action=new'))?>">+ Cadastrar</a><?php else:?><a class="btn secondary" href="<?=e(base_path('admin/crud.php?e='.$entity))?>">Voltar</a><?php endif;?></div><?php render_flash();?>
<?php if($action==='new'||$action==='edit'):?><div class="card"><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=e($row['id']??'')?>"><div class="form-grid"><?php foreach($def['fields'] as $k=>$m):$type=$m[1];$val=$row[$k]??'';$span=$type==='textarea'?' span2':'';?><div class="field<?=$span?>"><label><?=e($m[0])?></label><?php if($type==='textarea'):?><textarea name="<?=e($k)?>"><?=e($val)?></textarea><?php elseif($type==='select'||$type==='select_static'):?><select name="<?=e($k)?>"><?php optionsFor($m,$val);?></select><?php else:?><input type="<?=e($type)?>" step="0.01" name="<?=e($k)?>" value="<?=e($type==='datetime-local'&&$val?date('Y-m-d\TH:i',strtotime($val)):$val)?>"><?php endif;?></div><?php endforeach;?></div><br><button class="btn">Salvar</button></form></div>
<?php else:$rows=db()->query("SELECT * FROM $entity ORDER BY id DESC LIMIT 300")->fetchAll();?><div class="card"><?php if(!$rows):?><p class="muted">Nenhum registro cadastrado.</p><?php else:?><table><tr><th>ID</th><?php foreach(array_slice($def['fields'],0,4,true) as $k=>$m):?><th><?=e($m[0])?></th><?php endforeach;?><th>Ações</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['id'])?></td><?php foreach(array_slice($def['fields'],0,4,true) as $k=>$m):?><td><?=e(mb_strimwidth((string)($r[$k]??''),0,55,'…'))?></td><?php endforeach;?><td><div class="actions"><a class="btn secondary" href="<?=e(base_path('admin/crud.php?e='.$entity.'&action=edit&id='.$r['id']))?>">Editar</a><a class="btn danger" onclick="return confirm('Excluir este registro?')" href="<?=e(base_path('admin/crud.php?e='.$entity.'&action=delete&id='.$r['id']))?>">Excluir</a></div></td></tr><?php endforeach;?></table><?php endif;?></div><?php endif;?>
<?php require __DIR__.'/../includes/admin_footer.php';?>
