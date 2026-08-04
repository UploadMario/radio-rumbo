<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\View;
use App\Services\NewsService;
use Throwable;

final class NewsController
{
    public function __construct(private ?NewsService $news = null)
    {
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function index(array $mock): void
    {
        try {
            $items = $this->newsService()->published();
            $error = null;
        } catch (Throwable) {
            $items = [];
            $error = 'Las noticias dinámicas estarán disponibles cuando la base de datos esté configurada.';
        }

        View::render('layouts.public', [
            'active' => 'noticias',
            'contentView' => 'news',
            'mock' => $mock,
            'newsItems' => $items,
            'newsLoadMessage' => $error,
            'pageTitle' => 'Radio Rumbo - Noticias',
        ]);
    }

    private function newsService(): NewsService
    {
        if (!$this->news instanceof NewsService) {
            $this->news = new NewsService();
        }

        return $this->news;
    }
}
