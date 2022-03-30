# Package

Adds the ability to create tasks, and task logging.
Adds a section to horizon to allow TaskLogs to be visible
with task processing.

## Install

Add this repository to your composer.json

```shell
composer require jdlxnl/task
```

Run the following commands to setup the database
```shell
php artisan vendor:publish --provider="Jdlx\Slack\JdlxSlackServiceProvider"
php artisan vendor:publish --provider="Jdlx\Task\JdlxTaskServiceProvider"

php artisan migrate
```

## Install horizon

```shell
composer require laravel/horizon
php artisan horzion:publish
php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"
```
Make sure to change horizon config to

```php
[
...

    'defaults' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'maxProcesses' => 1,
            'memory' => 256,
            'tries' => 1,
            'nice' => 0,
            'timeout' => 900 // set very high, have jobs control their own timeout
        ],
    ],

    'environments' => [
        'production' => [
            'supervisor-1' => [
                'maxProcesses' => 2,
                'balanceMaxShift' => 1,
                'balanceCooldown' => 3,
                'sleep' => 1
            ],
        ],

        'staging' => [
            'supervisor-1' => [
                'maxProcesses' => 1,
            ],
        ],

        'local' => [
            'supervisor-1' => [
                'maxProcesses' => 3,
            ],
        ],
    ]

    ...

 ]

```
Make sure to add route to the API

```php
Route::apiResource('task-log', \Jdlx\Task\Http\Controllers\Api\TaskLogController::class);
```

## Slack
To support slack make sure to add the following keys to .env
```ini
SLACK_WEBHOOK=https://hooks.slack.com/services/asd/B01HB2M20asdf4V/s8GCasfsaDJesxH3ZyZuHUx
SLACK_DEFAULT_CHANNEL=my-channel
```

## Admin UI

If you have the admin UI installed, you can add the tasklog using
```sh
php artisan adminui:module:task
````

## Client
Building the client app

Add following to webpack.mix.js
```js
mix.js('resources/js/tasklog/index.js', 'public/vendor/jdlx/task/app.js').react();
```

make sure to add following packages to package.json
```json
{
  "react": "^17.0.1",
  "react-bootstrap": "^1.4.3",
  "react-data-table-component": "^7.0.0-alpha-5",
  "react-dom": "^17.0.1",
  "react-icons": "^4.1.0",
  "react-json-view": "^1.20.4",
  "react-router": "^5.2.0",
  "react-router-dom": "^5.2.0",
  "react-select": "^3.2.0",
  "styled-components": "^5.2.1"
}
```
