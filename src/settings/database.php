<?php
// Database connection (datos en settings/.env)
$env = parse_ini_file(__DIR__ . '/.env');

// Local connection
$local_conn = pg_connect("
    host={$env['LOCAL_HOST']}
    port={$env['LOCAL_PORT']}
    dbname={$env['LOCAL_DB']}
    user={$env['LOCAL_USER']}
    password='{$env['LOCAL_PASSWORD']}'
");

// Supa connection
$supa_conn = pg_connect("
    host={$env['SUPA_HOST']}
    port={$env['SUPA_PORT']}
    dbname={$env['SUPA_DB']}
    user={$env['SUPA_USER']}
    password='{$env['SUPA_PASSWORD']}'
");

if (!$local_conn) {
    die("Error local connection");
}

if (!$supa_conn) {
    die("Error supa connection");
}
?>