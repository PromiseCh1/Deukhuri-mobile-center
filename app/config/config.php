<?php
define('DMC_APP', true);

$envFile = __DIR__ . '/../../.env';
$env = is_file($envFile) ? parse_ini_file($envFile, false, INI_SCANNER_TYPED) : [];

define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
define('DB_NAME', $env['DB_NAME'] ?? 'deukhuri_shop');
define('DB_USER', $env['DB_USER'] ?? 'root');
define('DB_PASS', $env['DB_PASS'] ?? '');
define('APP_ENV', $env['APP_ENV'] ?? 'development');
define('APP_DEBUG', APP_ENV === 'development');
define('APP_ROOT', dirname(__DIR__, 2));