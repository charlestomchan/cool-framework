<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 14:16
 */

// Console应用配置
return [

    // 应用名称
    'appName'          => 'cool-httpd',

    // 应用版本
    'appVersion'       => '1.0.0',

    // 应用调试
    'appDebug'         => env('APP_DEBUG'),

    // 基础路径
    'basePath'         => dirname(__DIR__),

    // 运行目录路径
    'runtimePath'      => '',

    // 命令命名空间
    'commandNamespace' => 'Cool\Http\Daemon\Commands',

    // 命令
    'commands'         => [

        'start' => [
            'Start',
            'description' => "\tStart the mix-httpd service.",
            'options'     => [
                [['c', 'configuration'], 'description' => 'FILENAME -- configuration file path'],
                [['d', 'daemon'], 'description' => "\t" . 'Run in the background'],
                [['u', 'update'], 'description' => "\tEnable code hot update (only sync available"],
            ],
        ],

        'stop' => [
            'Stop',
            'description' => "\tStop the mix-httpd service.",
            'options'     => [
                [['c', 'configuration'], 'description' => 'FILENAME -- configuration file path'],
            ],
        ],

        'restart' => [
            'Restart',
            'description' => 'Restart the mix-httpd service.',
            'options'     => [
                [['c', 'configuration'], 'description' => 'FILENAME -- configuration file path'],
                [['d', 'daemon'], 'description' => "\t" . 'Run in the background'],
                [['u', 'update'], 'description' => "\tEnable code hot update (only sync available"],
            ],
        ],

        'reload' => [
            'Reload',
            'description' => 'Reload the worker process of the mix-httpd service.',
            'options'     => [
                [['c', 'configuration'], 'description' => 'FILENAME -- configuration file path'],
            ],
        ],

        'status' => [
            'Status',
            'description' => 'Check the status of the mix-httpd service.',
            'options'     => [
                [['c', 'configuration'], 'description' => 'FILENAME -- configuration file path'],
            ],
        ],

    ],

    // 组件配置
    'components'       => [

        // 错误
        'error' => [
            // 依赖引用
            'ref' => beanname(\Cool\Console\Error::class),
        ],

        // 日志
        'log'   => [
            // 依赖引用
            'ref' => beanname(\Cool\Log\Logger::class),
        ],

    ],

    // 依赖配置
    'beans'            => [

        // 错误
        [
            // 类路径
            'class'      => \Cool\Console\Error::class,
            // 属性
            'properties' => [
                // 错误级别
                'level' => E_ALL,
            ],
        ],

        // 日志
        [
            // 类路径
            'class'      => \Cool\Log\Logger::class,
            // 属性
            'properties' => [
                // 日志记录级别
                'levels'  => ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'],
                // 处理器
                'handler' => [
                    // 依赖引用
                    'ref' => beanname(Cool\Log\MultiHandler::class),
                ],
            ],
        ],

        // 日志处理器
        [
            // 类路径
            'class'      => Cool\Log\MultiHandler::class,
            // 属性
            'properties' => [
                // 日志处理器集合
                'handlers' => [
                    // 标准输出处理器
                    [
                        // 依赖引用
                        'ref' => beanname(Cool\Log\StdoutHandler::class),
                    ],
                    // 文件处理器
                    [
                        // 依赖引用
                        'ref' => beanname(Cool\Log\FileHandler::class),
                    ],
                ],
            ],
        ],

        // 日志标准输出处理器
        [
            // 类路径
            'class' => Cool\Log\StdoutHandler::class,
        ],

        // 日志文件处理器
        [
            // 类路径
            'class' => Cool\Log\FileHandler::class,
        ],

    ],

];
