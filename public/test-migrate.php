<?php
define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Running migration...<br>";
    $exitCode = \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "Exit Code: " . $exitCode . "<br>";
    echo "Artisan output: " . \Illuminate\Support\Facades\Artisan::output() . "<br>";
    
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    echo "Users Table Columns: " . implode(', ', $columns) . "<br>";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
