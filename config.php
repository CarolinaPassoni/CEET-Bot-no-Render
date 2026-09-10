<?php
return [
    'db' => [
        'url' => getenv('DATABASE_URL') ?: '',
        'host' => getenv('DB_HOST') ?: '',
        'name' => getenv('DB_NAME') ?: '',
        'user' => getenv('DB_USER') ?: '',
        'pass' => getenv('DB_PASSWORD') ?: '',
        'port' => getenv('DB_PORT') ?: '5432',
    ],
    'app' => [
        'base_url' => rtrim(getenv('APP_URL') ?: '', '/'),
        'secret' => getenv('APP_SECRET') ?: '',
        'setup_key' => getenv('SETUP_KEY') ?: '',
    ],
];
