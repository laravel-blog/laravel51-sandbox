<?php
/**
 * User: Stefan Riedel <sr@laravel-blog.de>
 * Date: 30.07.15
 * Time: 10:54
 * Project: sandbox
 */

use Laravelblog\Sandbox\User;

return [
    'user_model' => User::class,
    'elasticsearch' => [
        'config' => [
            'hosts'     => [env('EL_HOST1', '127.0.0.1:9200')],
            'logging'   => env('EL_LOGGING', true),
            'logPath'   => storage_path() . '/logs/elasticsearch.log',
            'logLevel'  => Monolog\Logger::WARNING,
        ],


        /**
         * your default es index
         */
        'default_index' => env('EL_INDEX', 'sandbox'),
    ]
];