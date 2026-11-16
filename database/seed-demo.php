<?php

declare(strict_types=1);

use App\Core\Database;
use Dotenv\Dotenv;

$root = dirname(__DIR__);
require_once $root . '/vendor/autoload.php';
if (is_file($root . '/.env')) {
    Dotenv::createImmutable($root)->safeLoad();
}

$apply = in_array('--apply', $argv, true);
$preview = !$apply;
$env = static fn (string $key, string $default = ''): string => trim((string) (getenv($key) ?: ($_ENV[$key] ?? $_SERVER[$key] ?? $default)));

if ($env('APP_ENV') !== 'local') {
    fwrite(STDERR, "Abortado: seed-demo.php exige APP_ENV=local.\n");
    exit(1);
}

$records = [
    'programs' => [
        ['name' => '[DEMO] Rumbo Mañanero', 'slug' => 'demo-rumbo-mananero', 'description' => 'Contenido de demostración para validar la agenda.', 'host_name' => 'Equipo de demostración', 'day_of_week' => 'monday', 'start_time' => '06:00:00', 'end_time' => '09:00:00', 'category' => 'Demostración', 'is_active' => 1, 'display_order' => 9001],
        ['name' => '[DEMO] Tarde de Radio', 'slug' => 'demo-tarde-radio', 'description' => 'Contenido de demostración para validar el programa siguiente.', 'host_name' => 'Equipo de demostración', 'day_of_week' => 'monday', 'start_time' => '16:00:00', 'end_time' => '18:00:00', 'category' => 'Demostración', 'is_active' => 1, 'display_order' => 9002],
        ['name' => '[DEMO] Programa archivado', 'slug' => 'demo-programa-archivado', 'description' => 'Registro inactivo para validar filtros administrativos.', 'host_name' => 'Equipo de demostración', 'day_of_week' => 'sunday', 'start_time' => '20:00:00', 'end_time' => '21:00:00', 'category' => 'Demostración', 'is_active' => 0, 'display_order' => 9003],
    ],
    'news' => [
        ['title' => '[DEMO] Noticias de prueba', 'slug' => 'demo-noticias-prueba', 'summary' => 'Publicación ficticia identificada para validación local.', 'body' => 'Este contenido existe únicamente para probar el flujo editorial de Radio Rumbo.', 'category' => 'Demostración', 'status' => 'published', 'published_at' => '2026-01-15 09:00:00'],
        ['title' => '[DEMO] Borrador editorial', 'slug' => 'demo-borrador-editorial', 'summary' => 'No debe aparecer en el frontend público.', 'body' => 'Borrador local para probar estados de publicación.', 'category' => 'Demostración', 'status' => 'draft', 'published_at' => null],
        ['title' => '[DEMO] Título largo para pruebas de reflujo y lectura', 'slug' => 'demo-titulo-largo-reflujo', 'summary' => 'Contenido para comprobar composición responsive.', 'body' => 'Texto de demostración para revisar longitudes y espacios.', 'category' => 'Demostración', 'status' => 'published', 'published_at' => '2026-01-16 09:00:00'],
    ],
    'team_members' => [
        ['name' => '[DEMO] Voz de prueba', 'role' => 'Perfil ficticio de validación', 'program_name' => '[DEMO] Rumbo Mañanero', 'bio' => 'Registro local de demostración; no representa personal real.', 'photo_path' => null, 'is_active' => 1, 'display_order' => 9001],
        ['name' => '[DEMO] Producción de prueba', 'role' => 'Perfil ficticio archivado', 'program_name' => '', 'bio' => 'Registro inactivo para validar administración.', 'photo_path' => null, 'is_active' => 0, 'display_order' => 9002],
    ],
    'banners' => [
        ['title' => '[DEMO] Banner principal', 'subtitle' => 'Pieza local para validar banners.', 'image_path' => '/assets/img/radio-rumbo-claim.png', 'link_url' => '/en-vivo/', 'position' => 'home_hero', 'status' => 'active', 'display_order' => 9001],
        ['title' => '[DEMO] Banner secundario', 'subtitle' => 'Pieza local de demostración.', 'image_path' => '/assets/img/radio-rumbo-isotype.png', 'link_url' => '/programacion/', 'position' => 'home_middle', 'status' => 'active', 'display_order' => 9002],
        ['title' => '[DEMO] Banner inactivo', 'subtitle' => 'No debe aparecer públicamente.', 'image_path' => null, 'link_url' => '', 'position' => 'footer', 'status' => 'inactive', 'display_order' => 9003],
    ],
];

$scheduleDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
$scheduleSlots = [
    ['00:00:00', '04:00:00', 'Recuerdos de Medianoche'], ['04:00:00', '07:00:00', 'Amanecer con Rumbo'],
    ['07:00:00', '10:00:00', 'Mananas Romanticas'], ['10:00:00', '13:00:00', 'Clasicos del Corazon'],
    ['13:00:00', '16:00:00', 'Rumbo Contigo'], ['16:00:00', '19:00:00', 'Tardes para Recordar'],
    ['19:00:00', '22:00:00', 'Noches de Romance'], ['22:00:00', '23:59:59', 'Melodias de Medianoche'],
];
$demoReconciliation = [
    'demo-rumbo-mananero' => ['day_of_week' => 'monday', 'start_time' => '04:00:00', 'end_time' => '07:00:00'],
    'demo-tarde-radio' => ['day_of_week' => 'monday', 'start_time' => '16:00:00', 'end_time' => '19:00:00'],
];
foreach ($records['programs'] as &$program) {
    $slug = (string) ($program['slug'] ?? '');
    if (isset($demoReconciliation[$slug])) {
        $program = array_merge($program, $demoReconciliation[$slug]);
    }
}
unset($program);
foreach ($scheduleDays as $dayIndex => $day) {
    foreach ($scheduleSlots as $slotIndex => [$start, $end, $name]) {
        $alreadyPresent = false;
        foreach ($records['programs'] as $existing) {
            if (($existing['day_of_week'] ?? '') === $day && ($existing['start_time'] ?? '') === $start) {
                $alreadyPresent = true;
                break;
            }
        }
        if ($alreadyPresent) {
            continue;
        }
        $slug = 'demo-parrilla-' . $day . '-' . $slotIndex;
        $records['programs'][] = ['name' => '[DEMO] ' . $name, 'slug' => $slug, 'description' => 'Franja semanal de demostracion; sustituible desde Administracion.', 'host_name' => 'Equipo Radio Rumbo', 'day_of_week' => $day, 'start_time' => $start, 'end_time' => $end, 'category' => 'Demostracion', 'is_active' => 1, 'display_order' => 9100 + ($dayIndex * 10) + $slotIndex];
    }
}

$coverage = [];
$coverageSafe = true;
foreach ($scheduleDays as $day) {
    $intervals = [];
    foreach ($records['programs'] as $program) {
        if (($program['day_of_week'] ?? '') !== $day || (int) ($program['is_active'] ?? 0) !== 1) {
            continue;
        }
        $start = ((int) substr((string) $program['start_time'], 0, 2) * 60) + (int) substr((string) $program['start_time'], 3, 2);
        $end = ((int) substr((string) $program['end_time'], 0, 2) * 60) + (int) substr((string) $program['end_time'], 3, 2);
        if ((string) $program['end_time'] === '23:59:59' || $end <= $start) {
            $end = 1440;
        }
        $intervals[] = [$start, $end, (string) $program['slug']];
    }
    usort($intervals, static fn (array $a, array $b): int => $a[0] <=> $b[0] ?: $a[1] <=> $b[1]);
    $covered = 0;
    $overlaps = 0;
    $cursor = 0;
    foreach ($intervals as [$start, $end]) {
        if ($start < $cursor) {
            $overlaps++;
        }
        if ($start > $cursor) {
            $coverageSafe = false;
        }
        $covered += max(0, $end - max($start, $cursor));
        $cursor = max($cursor, $end);
    }
    if ($cursor < 1440) {
        $coverageSafe = false;
    }
    $coverage[$day] = ['hours' => round($covered / 60, 2), 'gaps' => max(0, 1440 - $cursor), 'overlaps' => $overlaps];
    if ($overlaps > 0) {
        $coverageSafe = false;
    }
}
for ($index = 4; $index <= 12; $index++) {
    $records['news'][] = ['title' => '[DEMO] Nota editorial ' . $index, 'slug' => 'demo-nota-editorial-' . $index, 'summary' => 'Contenido local para validar listados y reflujo.', 'body' => 'Texto de demostracion para el flujo editorial.', 'category' => 'Demostracion', 'status' => 'published', 'published_at' => '2026-02-' . str_pad((string) $index, 2, '0', STR_PAD_LEFT) . ' 09:00:00'];
}
for ($index = 3; $index <= 7; $index++) {
    $records['team_members'][] = ['name' => '[DEMO] Perfil ' . $index, 'role' => 'Colaborador de demostracion', 'program_name' => '', 'bio' => 'Registro local sin representar personal real.', 'photo_path' => null, 'is_active' => 1, 'display_order' => 9010 + $index];
}
for ($index = 4; $index <= 7; $index++) {
    $records['banners'][] = ['title' => '[DEMO] Banner ' . $index, 'subtitle' => 'Pieza local de demostracion.', 'image_path' => null, 'link_url' => '/en-vivo/', 'position' => 'home_middle', 'status' => 'active', 'display_order' => 9010 + $index];
}
$records['urgent_notices'] = [
    ['title' => '[DEMO] Aviso de prueba', 'message' => 'Aviso local para verificar la barra informativa.', 'type' => 'info', 'status' => 'active', 'start_at' => '2026-01-01 00:00:00', 'end_at' => '2030-01-01 00:00:00', 'display_order' => 9001],
    ['title' => '[DEMO] Aviso archivado', 'message' => 'No debe aparecer en el frontend publico.', 'type' => 'event', 'status' => 'archived', 'start_at' => null, 'end_at' => null, 'display_order' => 9002],
];

foreach ($records as $table => $items) {
    printf("%s: %d registros previstos\n", $table, count($items));
}
echo "Cobertura prevista:\n";
foreach ($coverage as $day => $result) {
    printf("  %s: %.2f horas, %d minutos sin cubrir, %d solapamientos\n", $day, $result['hours'], $result['gaps'], $result['overlaps']);
}
echo $coverageSafe ? "Parrilla demo sin huecos ni solapamientos previstos.\n" : "ADVERTENCIA: la parrilla demo presenta huecos o solapamientos.\n";
if ($preview) {
    try {
        $schemaDatabase = Database::connection()->query('SELECT DATABASE()')->fetchColumn();
        if ($schemaDatabase !== 'radio_rumbo') {
            echo "Validacion de esquema no realizada: la conexion no apunta a radio_rumbo.\n";
        } else {
            $required = ['programs' => ['name', 'slug', 'description', 'host_name', 'day_of_week', 'start_time', 'end_time', 'category', 'is_active', 'display_order'], 'news' => ['title', 'slug', 'summary', 'body', 'category', 'status', 'published_at'], 'team_members' => ['name', 'role', 'program_name', 'bio', 'photo_path', 'is_active', 'display_order'], 'banners' => ['title', 'image_path', 'status'], 'urgent_notices' => ['title', 'message', 'type', 'status', 'start_at', 'end_at', 'display_order']];
            foreach ($required as $table => $columns) {
                $check = Database::connection()->prepare('SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = :table');
                $check->execute(['table' => $table]);
                $available = array_map('strval', $check->fetchAll(PDO::FETCH_COLUMN));
                $missing = array_diff($columns, $available);
                if ($available === [] && $table === 'urgent_notices') { echo "Validacion: urgent_notices ausente; se omitira.\n"; continue; }
                if ($available === [] || $missing !== []) { throw new RuntimeException('Esquema incompatible en ' . $table . '; faltan: ' . implode(', ', $missing) . '.'); }
            }
            echo "Validacion de esquema MySQL: compatible.\n";
        }
    } catch (Throwable $exception) {
        echo "Validacion de esquema no realizada: " . $exception->getMessage() . "\n";
        echo "La vista previa de datos no implica compatibilidad estructural.\n";
    }
    echo "Vista previa completada; no se modificó la base de datos.\n";
    exit(0);
}

if ($env('DEMO_SEED_BACKUP_CONFIRMED') !== '1') {
    fwrite(STDERR, "Abortado: confirma un respaldo verificable con DEMO_SEED_BACKUP_CONFIRMED=1.\n");
    exit(1);
}

$pdo = Database::connection();
$database = (string) $pdo->query('SELECT DATABASE()')->fetchColumn();
if ($database !== 'radio_rumbo') {
    throw new RuntimeException('Abortado: la conexión no apunta a radio_rumbo.');
}

$pdo->beginTransaction();
try {
    $inserted = 0;
    $skipped = 0;
    $reconciled = 0;
    $reconcile = $pdo->prepare('UPDATE programs SET day_of_week = :day_of_week, start_time = :start_time, end_time = :end_time WHERE slug = :slug AND name LIKE :demo_name');
    foreach ($demoReconciliation as $slug => $schedule) {
        $reconcile->execute($schedule + ['slug' => $slug, 'demo_name' => '[DEMO]%']);
        $reconciled += $reconcile->rowCount();
    }
    $program = $pdo->prepare('INSERT INTO programs (name, slug, description, host_name, day_of_week, start_time, end_time, category, is_active, display_order) SELECT :name, :slug, :description, :host_name, :day_of_week, :start_time, :end_time, :category, :is_active, :display_order FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM programs WHERE slug = :slug_check)');
    foreach ($records['programs'] as $row) {
        $params = $row + ['slug_check' => $row['slug']];
        $program->execute($params);
        $program->rowCount() === 1 ? $inserted++ : $skipped++;
    }
    $news = $pdo->prepare('INSERT INTO news (title, slug, summary, body, category, status, published_at) SELECT :title, :slug, :summary, :body, :category, :status, :published_at FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM news WHERE slug = :slug_check)');
    foreach ($records['news'] as $row) {
        $news->execute($row + ['slug_check' => $row['slug']]);
        $news->rowCount() === 1 ? $inserted++ : $skipped++;
    }
    $team = $pdo->prepare('INSERT INTO team_members (name, role, program_name, bio, photo_path, is_active, display_order) SELECT :name, :role, :program_name, :bio, :photo_path, :is_active, :display_order FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM team_members WHERE name = :name_check)');
    foreach ($records['team_members'] as $row) {
        $team->execute($row + ['name_check' => $row['name']]);
        $team->rowCount() === 1 ? $inserted++ : $skipped++;
    }
    $banner = $pdo->prepare('INSERT INTO banners (title, image_path, status) SELECT :title, :image_path, :status FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM banners WHERE title = :title_check)');
    foreach ($records['banners'] as $row) {
        $banner->execute([
            'title' => $row['title'],
            'image_path' => $row['image_path'],
            'status' => $row['status'],
            'title_check' => $row['title'],
        ]);
        $banner->rowCount() === 1 ? $inserted++ : $skipped++;
    }
    $tableExists = (bool) $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'urgent_notices'")->fetchColumn();
    if ($tableExists) {
        $notice = $pdo->prepare('INSERT INTO urgent_notices (title, message, type, status, start_at, end_at, display_order) SELECT :title, :message, :type, :status, :start_at, :end_at, :display_order FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM urgent_notices WHERE title = :title_check)');
        foreach ($records['urgent_notices'] as $row) {
            $notice->execute($row + ['title_check' => $row['title']]);
            $notice->rowCount() === 1 ? $inserted++ : $skipped++;
        }
    } else {
        echo "Avisos urgentes omitidos: la tabla urgent_notices no existe en la base conectada.\n";
    }
    $pdo->commit();
    printf("Aplicación completada: %d insertados, %d omitidos.\n", $inserted, $skipped);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $exception;
}
