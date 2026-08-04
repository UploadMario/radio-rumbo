<?php

declare(strict_types=1);

return [
    'station' => [
        'name' => 'Radio Rumbo',
        'tagline' => 'La radio que se ve',
        'location' => 'Huancayo, Perú',
        'stream_status' => 'Stream simulado',
        'whatsapp_cabina' => 'WhatsApp de cabina',
        'whatsapp_publicidad' => 'WhatsApp de publicidad',
        'email' => 'Correo institucional pendiente',
        'address' => 'Dirección pendiente de confirmar',
        'social' => 'Redes sociales oficiales',
    ],
    'current_program' => [
        'name' => 'Programa matinal',
        'host' => 'Conductor principal',
        'schedule' => '06:00 - 09:00',
        'category' => 'Actualidad local',
        'status' => 'EN VIVO',
        'summary' => 'Información útil, compañía y música para iniciar el día en Huancayo.',
    ],
    'today_schedule' => [
        ['time' => '06:00', 'name' => 'Programa matinal', 'host' => 'Conductor principal', 'category' => 'Actualidad', 'live' => true],
        ['time' => '09:00', 'name' => 'Rumbo Noticias', 'host' => 'Equipo informativo', 'category' => 'Noticias', 'live' => false],
        ['time' => '12:00', 'name' => 'Mediodía local', 'host' => 'Conductor placeholder', 'category' => 'Comunidad', 'live' => false],
        ['time' => '16:00', 'name' => 'Tarde musical', 'host' => 'Conductor principal', 'category' => 'Música', 'live' => false],
        ['time' => '19:00', 'name' => 'Resumen Rumbo', 'host' => 'Equipo informativo', 'category' => 'Análisis', 'live' => false],
    ],
    'news' => [
        ['category' => 'Huancayo', 'date' => 'Fase 1.1', 'title' => 'Últimas noticias de Huancayo', 'summary' => 'Espacio preparado para notas locales verificadas en fases futuras.', 'featured' => true],
        ['category' => 'Comunidad', 'date' => 'Placeholder', 'title' => 'Agenda local en preparación', 'summary' => 'Contenido mock para validar jerarquía visual y lectura.', 'featured' => false],
        ['category' => 'Cultura', 'date' => 'Placeholder', 'title' => 'Voces de la ciudad', 'summary' => 'Módulo listo para conectar a noticias reales con MySQL.', 'featured' => false],
        ['category' => 'Servicio', 'date' => 'Placeholder', 'title' => 'Información útil para oyentes', 'summary' => 'Card de ejemplo sin datos sensibles ni información real inventada.', 'featured' => false],
    ],
    'podcasts' => [
        ['title' => 'Resumen del día', 'program' => 'Rumbo Noticias', 'duration' => '24 min', 'status' => 'Módulo en preparación'],
        ['title' => 'Entrevista local', 'program' => 'Programa matinal', 'duration' => '18 min', 'status' => 'Próximamente'],
        ['title' => 'Archivo musical', 'program' => 'Tarde musical', 'duration' => '32 min', 'status' => 'Disponible en próxima fase'],
    ],
    'team' => [
        ['name' => 'Conductor principal', 'role' => 'Voz principal', 'program' => 'Programa matinal'],
        ['name' => 'Equipo informativo', 'role' => 'Noticias locales', 'program' => 'Rumbo Noticias'],
        ['name' => 'Admin', 'role' => 'Gestión administrativa', 'program' => 'Panel interno'],
        ['name' => 'Super Admin', 'role' => 'Administración general', 'program' => 'Configuración'],
    ],
    'advertising_services' => [
        ['title' => 'Spots radiales', 'summary' => 'Paquetes de audio para campañas locales.'],
        ['title' => 'Menciones en vivo', 'summary' => 'Presencia de marca durante programas seleccionados.'],
        ['title' => 'Banners web', 'summary' => 'Espacios visuales preparados para fases futuras.'],
        ['title' => 'Cobertura de eventos', 'summary' => 'Acompañamiento radial para actividades locales.'],
    ],
    'planned_modules' => [
        ['name' => 'Podcasts', 'status' => 'Módulo en preparación'],
        ['name' => 'Ranking Top 20', 'status' => 'Próximamente'],
        ['name' => 'La Radio que se ve', 'status' => 'Funcionalidad planificada'],
        ['name' => 'Avisos urgentes', 'status' => 'Disponible en próxima fase'],
        ['name' => 'Estadísticas avanzadas', 'status' => 'Próximamente'],
    ],
    'dashboard' => [
        'stats' => [
            ['label' => 'Oyentes en línea', 'value' => 'Placeholder', 'hint' => 'Estadística ficticia'],
            ['label' => 'Noticias listas', 'value' => '4', 'hint' => 'Mock Fase 1.1'],
            ['label' => 'Mensajes recientes', 'value' => '3', 'hint' => 'Mock Fase 1.1'],
            ['label' => 'Estado stream', 'value' => 'Simulado', 'hint' => 'Sin conexión real'],
        ],
        'messages' => [
            ['from' => 'Oyente placeholder', 'subject' => 'Saludo a cabina', 'status' => 'Nuevo'],
            ['from' => 'Comercio local', 'subject' => 'Consulta publicidad local', 'status' => 'Pendiente'],
            ['from' => 'Contacto web', 'subject' => 'Mensaje desde formulario visual', 'status' => 'Mock'],
        ],
    ],
];
