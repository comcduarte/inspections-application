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
                'dsn' => 'mysql:host=it-webdb01.midnet.cityofmiddletown.com;dbname=complaints_dev',
                'username' => 'complaints',
                'password' => '3U4eREtcIR11F4E6O92T',
            ],
            'access-token-config' => [
                'client_id' => 'k251wtdz16q0dt37mi4lnt6bosa9skh4',
                'client_secret' => 'wyr1c47Q3iljqdcMyqtD40lAaJhxWHyx',
                'grant_type' => 'client_credentials',
                'box_subject_type' => 'enterprise',
                'box_subject_id' => '563960266',
            ],
        ],
    ],
];
