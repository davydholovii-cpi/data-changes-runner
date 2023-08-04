<?php

require_once __DIR__ . '/bootstrap.php';

connect();

$sourcesDir = dirname(__DIR__) . '/sources/mob407_eu';

$analyzer = new \App\Mob407\FixAnalyzer(
    rootPath: dirname(__DIR__),
    sourcesDir: $sourcesDir,
);
$analyzer->run([
    'force_recreate_users_table',
    'force_recreate_payments_history_table',
]);
