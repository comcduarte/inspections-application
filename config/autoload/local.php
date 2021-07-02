<?php

/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * NOTE: This file is ignored from Git by default with the .gitignore included
 * in laminas-mvc-skeleton. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */

return [
    'service_manager' => [
        'services' => [
            'model-adapter-config' => [
                'driver' => 'PDO',
                'dsn' => 'mysql:host=it-webdb01.midnet.cityofmiddletown.com;dbname=inspections_dev',
                'username' => 'inspections',
                'password' => 'YA13HKxvYxIM6DHx96ce',
            ],
        ],
    ],
];
