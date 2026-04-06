<?php
/**
 * Script de deploy para ambientes sem SSH.
 * IMPORTANTE: Apague este arquivo após executar.
 */

// ── Token de segurança — troque antes de fazer upload ──────────────────────
define('DEPLOY_TOKEN', 'atrio-deploy-2026');

if (($_GET['token'] ?? '') !== DEPLOY_TOKEN) {
    http_response_code(403);
    die('<h2>403 — Token inválido.</h2>');
}

// ── Bootstrap do Laravel ───────────────────────────────────────────────────
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// ── Executa os comandos ────────────────────────────────────────────────────
$results = [];

function run(string $label, callable $fn): void {
    global $results;
    try {
        $out = $fn();
        $results[] = ['ok' => true,  'label' => $label, 'output' => $out ?? ''];
    } catch (\Throwable $e) {
        $results[] = ['ok' => false, 'label' => $label, 'output' => $e->getMessage()];
    }
}

// 1. Migrations
run('migrate', function () {
    $exitCode = Artisan::call('migrate', ['--force' => true]);
    return Artisan::output();
});

// 2. Seeder de permissões
run('RolesAndPermissionsSeeder', function () {
    $exitCode = Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder', '--force' => true]);
    return Artisan::output();
});

// 3. Remove registros de terminologia vazios
run('Limpar term_ vazios', function () {
    $deleted = DB::table('school_settings')
        ->where('key', 'like', 'term_%')
        ->where('value', '')
        ->delete();
    return "{$deleted} registro(s) removido(s).";
});

// 4. Limpar caches
run('permission:cache-reset', function () {
    Artisan::call('permission:cache-reset');
    return Artisan::output();
});

run('view:clear', function () {
    Artisan::call('view:clear');
    return Artisan::output();
});

run('cache:clear', function () {
    Artisan::call('cache:clear');
    return Artisan::output();
});

run('config:clear', function () {
    Artisan::call('config:clear');
    return Artisan::output();
});

// ── Saída HTML ─────────────────────────────────────────────────────────────
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Deploy — Átrio</title>
<style>
  body { font-family: monospace; background: #0f172a; color: #e2e8f0; padding: 40px; margin: 0; }
  h1   { color: #60a5fa; margin-bottom: 32px; }
  .item { margin-bottom: 16px; padding: 16px; border-radius: 8px; }
  .ok   { background: #052e16; border-left: 4px solid #22c55e; }
  .err  { background: #2d0a0a; border-left: 4px solid #ef4444; }
  .label { font-weight: bold; font-size: 15px; margin-bottom: 6px; }
  .out  { font-size: 13px; color: #94a3b8; white-space: pre-wrap; }
  .warn { margin-top: 32px; padding: 16px; background: #451a03; border-left: 4px solid #f97316; border-radius: 8px; color: #fed7aa; }
</style>
</head>
<body>
<h1>Deploy — Átrio</h1>

<?php foreach ($results as $r): ?>
<div class="item <?= $r['ok'] ? 'ok' : 'err' ?>">
    <div class="label"><?= $r['ok'] ? '✓' : '✗' ?> <?= htmlspecialchars($r['label']) ?></div>
    <?php if ($r['output']): ?>
    <div class="out"><?= htmlspecialchars(trim($r['output'])) ?></div>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<div class="warn">
    ⚠️ <strong>Apague o arquivo <code>public/deploy-run.php</code> do servidor agora.</strong>
</div>
</body>
</html>
