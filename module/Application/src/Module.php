<?php

declare(strict_types=1);

namespace Application;

class Module
{
    const TITLE = 'Inspections';
    const VERSION = '1.0.3';
    
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }
}
