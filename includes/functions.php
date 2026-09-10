<?php
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }

function base_path(string $path=''): string {
    global $config;
    $base = rtrim($config['app']['base_url'] ?? '', '/');
    if ($base === '') {
        $proto = ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') ?: ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($host) $base = $proto . '://' . $host;
    }
    return $base . '/' . ltrim($path, '/');
}

function app_secret(): string {
    global $config;
    $secret = (string)($config['app']['secret'] ?? '');
    if ($secret === '') throw new RuntimeException('APP_SECRET não configurado no Render.');
    return $secret;
}

function b64url_encode(string $data): string { return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); }
function b64url_decode(string $data): string|false {
    $pad = strlen($data) % 4;
    if ($pad) $data .= str_repeat('=', 4 - $pad);
    return base64_decode(strtr($data, '-_', '+/'), true);
}
function sign_payload(array $payload): string {
    $body = b64url_encode(json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    $sig = b64url_encode(hash_hmac('sha256', $body, app_secret(), true));
    return $body . '.' . $sig;
}
function verify_payload(?string $token): ?array {
    if (!$token || !str_contains($token, '.')) return null;
    [$body,$sig] = explode('.', $token, 2);
    $expected = b64url_encode(hash_hmac('sha256', $body, app_secret(), true));
    if (!hash_equals($expected, $sig)) return null;
    $decoded = b64url_decode($body);
    if ($decoded === false) return null;
    $data = json_decode($decoded, true);
    if (!is_array($data)) return null;
    if (!empty($data['exp']) && time() > (int)$data['exp']) return null;
    return $data;
}
function cookie_opts(int $expires): array {
    return [
        'expires' => $expires,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}
function set_auth_cookie(array $user): void {
    $payload = ['id'=>(int)$user['id'],'name'=>(string)$user['name'],'exp'=>time()+60*60*12];
    setcookie('ceet_auth', sign_payload($payload), cookie_opts(time()+60*60*12));
    $_COOKIE['ceet_auth'] = sign_payload($payload);
}
function clear_auth_cookie(): void {
    setcookie('ceet_auth', '', cookie_opts(time()-3600));
    unset($_COOKIE['ceet_auth']);
}
function current_user(): ?array { return verify_payload($_COOKIE['ceet_auth'] ?? null); }
function current_user_id(): int { return (int)(current_user()['id'] ?? 0); }
function current_user_name(): string { return (string)(current_user()['name'] ?? 'Administrador'); }
function is_logged(): bool { return current_user_id() > 0; }
function require_login(): void {
    if (!is_logged()) { header('Location: ' . base_path('login.php')); exit; }
}

function csrf_token(): string {
    $existing = verify_payload($_COOKIE['ceet_csrf'] ?? null);
    if ($existing && !empty($existing['token'])) return (string)$existing['token'];
    $token = bin2hex(random_bytes(24));
    $signed = sign_payload(['token'=>$token,'exp'=>time()+60*60*4]);
    setcookie('ceet_csrf', $signed, cookie_opts(time()+60*60*4));
    $_COOKIE['ceet_csrf'] = $signed;
    return $token;
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $submitted = (string)($_POST['csrf'] ?? '');
        $stored = verify_payload($_COOKIE['ceet_csrf'] ?? null);
        $expected = (string)($stored['token'] ?? '');
        if ($submitted === '' || $expected === '' || !hash_equals($expected, $submitted)) {
            http_response_code(419); exit('Sessão de segurança expirada. Recarregue a página e tente novamente.');
        }
    }
}

function flash(string $msg, string $type='ok'): void {
    $type = in_array($type,['ok','err'],true) ? $type : 'ok';
    $signed = sign_payload(['msg'=>$msg,'type'=>$type,'exp'=>time()+120]);
    setcookie('ceet_flash', $signed, cookie_opts(time()+120));
    $_COOKIE['ceet_flash'] = $signed;
}
function render_flash(): void {
    $data = verify_payload($_COOKIE['ceet_flash'] ?? null);
    if ($data && isset($data['msg'])) {
        setcookie('ceet_flash', '', cookie_opts(time()-3600));
        unset($_COOKIE['ceet_flash']);
        echo '<div class="flash '.e($data['type'] ?? 'ok').'">'.e($data['msg']).'</div>';
    }
}

function setting(string $key, string $default=''): string {
    try { $st=db()->prepare('SELECT setting_value FROM settings WHERE setting_key=?'); $st->execute([$key]); $v=$st->fetchColumn(); return $v!==false ? (string)$v : $default; } catch(Throwable $e){ return $default; }
}
function save_setting(string $key, string $value): void {
    $st=db()->prepare('INSERT INTO settings(setting_key,setting_value,updated_at) VALUES(?,?,CURRENT_TIMESTAMP) ON CONFLICT(setting_key) DO UPDATE SET setting_value=EXCLUDED.setting_value, updated_at=CURRENT_TIMESTAMP');
    $st->execute([$key,$value]);
}
function whatsapp_send(string $to, string $text): array {
    $token = setting('wa_access_token');
    $phoneId = setting('wa_phone_number_id');
    $apiVersion = setting('wa_api_version','v23.0');
    if (!$token || !$phoneId) return ['ok'=>false,'error'=>'WhatsApp ainda não configurado.'];
    $url = "https://graph.facebook.com/{$apiVersion}/{$phoneId}/messages";
    $payload = json_encode(['messaging_product'=>'whatsapp','to'=>$to,'type'=>'text','text'=>['body'=>$text]], JSON_UNESCAPED_UNICODE);
    $ch=curl_init($url);
    curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$token,'Content-Type: application/json'],CURLOPT_POSTFIELDS=>$payload,CURLOPT_TIMEOUT=>20]);
    $resp=curl_exec($ch); $err=curl_error($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
    return ['ok'=>$code>=200 && $code<300,'code'=>$code,'response'=>$resp,'error'=>$err];
}
