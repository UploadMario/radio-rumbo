<?php

declare(strict_types=1);

return [
    'station' => [
        'name' => 'Radio Rumbo',
        'tagline' => 'Moderna, clara, local y para todos.',
        'location' => 'Huancayo, Peru',
        'stream_status' => 'Stream simulado',
        'whatsapp_cabina' => 'WhatsApp de cabina',
        'whatsapp_publicidad' => 'WhatsApp de publicidad',
    ],
    'current_program' => [
        'name' => 'Programa Matinal',
        'host' => 'Conductor Principal',
        'schedule' => '06:00 - 09:00',
        'category' => 'Actualidad local',
        'status' => 'EN VIVO',
        'summary' => 'Informacion util, compania y musica para iniciar el dia en Huancayo.',
    ],
    'today_schedule' => [
        ['time' => '06:00', 'name' => 'Programa Matinal', 'host' => 'Conductor Principal', 'category' => 'Actualidad', 'live' => true],
        ['time' => '09:00', 'name' => 'Rumbo Noticias', 'host' => 'Equipo Informativo', 'category' => 'Noticias', 'live' => false],
        ['time' => '12:00', 'name' => 'Mediodia Local', 'host' => 'Conductor Placeholder', 'category' => 'Comunidad', 'live' => false],
        ['time' => '16:00', 'name' => 'Tarde Musical', 'host' => 'Conductor Principal', 'category' => 'Musica', 'live' => false],
        ['time' => '19:00', 'name' => 'Resumen Rumbo', 'host' => 'Equipo Informativo', 'category' => 'Analisis', 'live' => false],
    ],
    'news' => [
        ['category' => 'Huancayo', 'date' => 'Fase 1', 'title' => 'Ultimas noticias de Huancayo', 'summary' => 'Espacio preparado para notas locales verificadas en fases futuras.', 'featured' => true],
        ['category' => 'Comunidad', 'date' => 'Placeholder', 'title' => 'Agenda local en preparacion', 'summary' => 'Contenido mock para validar jerarquia visual y lectura.', 'featured' => false],
        ['category' => 'Cultura', 'date' => 'Placeholder', 'title' => 'Voces de la ciudad', 'summary' => 'Modulo listo para conectar a noticias reales con MySQL.', 'featured' => false],
        ['category' => 'Servicio', 'date' => 'Placeholder', 'title' => 'Informacion util para oyentes', 'summary' => 'Card de ejemplo sin datos sensibles ni informacion real inventada.', 'featured' => false],
    ],
    'podcasts' => [
        ['title' => 'Resumen del dia', 'program' => 'Rumbo Noticias', 'duration' => '24 min', 'status' => 'Modulo en preparacion'],
        ['title' => 'Entrevista local', 'program' => 'Programa Matinal', 'duration' => '18 min', 'status' => 'Proximamente'],
        ['title' => 'Archivo musical', 'program' => 'Tarde Musical', 'duration' => '32 min', 'status' => 'Disponible en proxima fase'],
    ],
    'team' => [
        ['name' => 'Conductor Principal', 'role' => 'Voz principal', 'program' => 'Programa Matinal'],
        ['name' => 'Equipo Informativo', 'role' => 'Noticias locales', 'program' => 'Rumbo Noticias'],
        ['name' => 'Admin', 'role' => 'Gestion administrativa', 'program' => 'Panel interno'],
        ['name' => 'Super Admin', 'role' => 'Administracion general', 'program' => 'Configuracion'],
    ],
    'advertising_services' => [
        ['title' => 'Spots radiales', 'summary' => 'Paquetes de audio para campanas locales.'],
        ['title' => 'Menciones en vivo', 'summary' => 'Presencia de marca durante programas seleccionados.'],
        ['title' => 'Banners web', 'summary' => 'Espacios visuales preparados para fases futuras.'],
        ['title' => 'Cobertura de eventos', 'summary' => 'Acompanamiento radial para actividades locales.'],
    ],
    'dashboard' => [
        'stats' => [
            ['label' => 'Oyentes online', 'value' => 'Placeholder', 'hint' => 'Estadistica ficticia'],
            ['label' => 'Noticias listas', 'value' => '4', 'hint' => 'Mock Fase 1'],
            ['label' => 'Mensajes recientes', 'value' => '3', 'hint' => 'Mock Fase 1'],
            ['label' => 'Estado stream', 'value' => 'Simulado', 'hint' => 'Sin conexion real'],
        ],
        'messages' => [
            ['from' => 'Oyente placeholder', 'subject' => 'Saludo a cabina', 'status' => 'Nuevo'],
            ['from' => 'Comercio local', 'subject' => 'Consulta publicidad local', 'status' => 'Pendiente'],
            ['from' => 'Contacto web', 'subject' => 'Mensaje desde formulario visual', 'status' => 'Mock'],
        ],
    ],
];
