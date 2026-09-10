<?php
require __DIR__.'/../includes/bootstrap.php';
// Verificação do webhook pela Meta
if($_SERVER['REQUEST_METHOD']==='GET'){
    $mode=$_GET['hub_mode']??$_GET['hub.mode']??'';
    $token=$_GET['hub_verify_token']??$_GET['hub.verify_token']??'';
    $challenge=$_GET['hub_challenge']??$_GET['hub.challenge']??'';
    if($mode==='subscribe' && hash_equals(setting('wa_verify_token',''),$token)){echo $challenge;exit;}
    http_response_code(403);echo 'Token inválido';exit;
}
$raw=file_get_contents('php://input');
$payload=json_decode($raw,true);
$msg=$payload['entry'][0]['changes'][0]['value']['messages'][0]??null;
if(!$msg){http_response_code(200);echo 'EVENT_RECEIVED';exit;}
$from=$msg['from']??'';
$text=trim($msg['text']['body']??'');
if(!$from){http_response_code(200);echo 'IGNORED';exit;}
try{db()->prepare("INSERT INTO messages(phone,direction,message) VALUES(?,'in',?)")->execute([$from,$text]);}catch(Throwable $e){}
$norm=mb_strtolower($text,'UTF-8');
$reply='';
if($norm==='' || preg_match('/^(oi|olá|ola|menu|inicio|início)$/u',$norm)){
    $reply="🎓 *".setting('school_name','Curso Técnico')."*\n\nEscolha uma opção:\n1️⃣ Horários\n2️⃣ Atividades\n3️⃣ Materiais\n4️⃣ Projeto Interdisciplinar\n5️⃣ Estágio\n6️⃣ Eventos\n7️⃣ Professores\n\nEnvie o número da opção.";
} elseif($norm==='1' || str_contains($norm,'hor')){
    $rows=db()->query("SELECT sc.weekday,sc.start_time,sc.end_time,s.name subject,c.name class_name FROM schedules sc LEFT JOIN subjects s ON s.id=sc.subject_id LEFT JOIN classes c ON c.id=sc.class_id ORDER BY CASE sc.weekday WHEN 'Segunda' THEN 1 WHEN 'Terça' THEN 2 WHEN 'Quarta' THEN 3 WHEN 'Quinta' THEN 4 WHEN 'Sexta' THEN 5 WHEN 'Sábado' THEN 6 ELSE 7 END, sc.start_time LIMIT 20")->fetchAll();
    $reply="🕒 *Horários*\n"; foreach($rows as $r){$reply.="\n• {$r['weekday']} ".substr($r['start_time']??'',0,5)." - ".substr($r['end_time']??'',0,5)." | {$r['subject']}";} if(!$rows)$reply.="\nNenhum horário cadastrado.";
} elseif($norm==='2' || str_contains($norm,'ativ')){
    $rows=db()->query("SELECT a.title,a.due_date,s.name subject FROM activities a LEFT JOIN subjects s ON s.id=a.subject_id WHERE a.due_date IS NULL OR a.due_date>=CURRENT_DATE ORDER BY a.due_date LIMIT 10")->fetchAll();
    $reply="📝 *Atividades*\n"; foreach($rows as $r){$d=$r['due_date']?date('d/m/Y',strtotime($r['due_date'])):'sem prazo';$reply.="\n• {$r['title']} — {$r['subject']} — {$d}";} if(!$rows)$reply.="\nNenhuma atividade pendente cadastrada.";
} elseif($norm==='3' || str_contains($norm,'material')){
    $rows=db()->query("SELECT m.title,m.url,s.name subject FROM materials m LEFT JOIN subjects s ON s.id=m.subject_id ORDER BY m.id DESC LIMIT 10")->fetchAll();
    $reply="📚 *Materiais*\n"; foreach($rows as $r){$reply.="\n• {$r['title']}".($r['subject']?" — {$r['subject']}":'').($r['url']?"\n{$r['url']}":'');} if(!$rows)$reply.="\nNenhum material cadastrado.";
} elseif($norm==='4' || str_contains($norm,'projeto')){
    $rows=db()->query("SELECT title,stage,due_date,points FROM projects ORDER BY due_date LIMIT 10")->fetchAll();
    $reply="💡 *Projeto Interdisciplinar*\n"; foreach($rows as $r){$d=$r['due_date']?date('d/m/Y',strtotime($r['due_date'])):'sem prazo';$reply.="\n• {$r['title']} — {$r['stage']} — {$d}";} if(!$rows)$reply.="\nNenhuma etapa cadastrada.";
} elseif($norm==='5' || str_contains($norm,'estágio') || str_contains($norm,'estagio')){
    $rows=db()->query("SELECT title,company,deadline,contact FROM internships ORDER BY deadline LIMIT 10")->fetchAll();
    $reply="🏢 *Estágio*\n"; foreach($rows as $r){$d=$r['deadline']?date('d/m/Y',strtotime($r['deadline'])):'sem prazo';$reply.="\n• {$r['title']} — {$r['company']} — {$d}";} if(!$rows)$reply.="\nNenhuma oportunidade/informação cadastrada.";
} elseif($norm==='6' || str_contains($norm,'evento') || str_contains($norm,'calend')){
    $rows=db()->query("SELECT title,event_date,location FROM events WHERE event_date IS NULL OR event_date>=NOW() ORDER BY event_date LIMIT 10")->fetchAll();
    $reply="📅 *Eventos*\n"; foreach($rows as $r){$d=$r['event_date']?date('d/m/Y H:i',strtotime($r['event_date'])):'data a definir';$reply.="\n• {$r['title']} — {$d}".($r['location']?" — {$r['location']}":'');} if(!$rows)$reply.="\nNenhum evento cadastrado.";
} elseif($norm==='7' || str_contains($norm,'prof')){
    $rows=db()->query("SELECT p.name,STRING_AGG(s.name, ', ' ORDER BY s.name) AS subjects FROM professors p LEFT JOIN subjects s ON s.professor_id=p.id GROUP BY p.id, p.name ORDER BY p.name LIMIT 20")->fetchAll();
    $reply="👨‍🏫 *Professores*\n"; foreach($rows as $r){$reply.="\n• {$r['name']}".($r['subjects']?" — {$r['subjects']}":'');} if(!$rows)$reply.="\nNenhum professor cadastrado.";
} else {
    $reply="Não entendi essa opção. 🙂\nEnvie *menu* para ver as opções disponíveis.";
}
$result=whatsapp_send($from,$reply);
try{db()->prepare("INSERT INTO messages(phone,direction,message) VALUES(?,'out',?)")->execute([$from,$reply]);}catch(Throwable $e){}
http_response_code(200); echo 'EVENT_RECEIVED';
