<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

require dirname(__DIR__) . '/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    new Dotenv()->bootEnv(dirname(__DIR__) . '/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

// executes the "php bin/console cache:clear" command
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
        $_ENV['APP_ENV'],
        __DIR__
    )
);
// executes the "php bin/console doctrine:database:create" command
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" doctrine:database:create -n --env=test',
        $_ENV['APP_ENV'],
        __DIR__
    )
);
// executes the "php bin/console doctrine:migrations:migrate" command
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" doctrine:migrations:migrate -n --env=test',
        $_ENV['APP_ENV'],
        __DIR__
    )
);

// executes the "php bin/console doctrine:migrations:migrate" command
passthru(
    sprintf(
        'APP_ENV=%s php "%s/../bin/console" doctrine:fixtures:load -n --env=test ',
        $_ENV['APP_ENV'],
        __DIR__
    )
);

// ensure a fresh cache when debug mode is disabled
new Filesystem()->remove(__DIR__ . '/../var/cache/test');
