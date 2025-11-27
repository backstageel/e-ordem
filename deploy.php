<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config
set('application', 'ormm');
set('repository', 'git@github.com:hostmoz/ormm.git');
set('keep_releases', 1);

// Shared files/dirs between deploys
add('shared_files', [
    '.env',
    'public/.user.ini',
]);
add('shared_dirs', [
    'storage',
    'bootstrap/cache',
]);

// Writable dirs by web server
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
]);

// Hosts

host('ormm.hostmoz.net')
    ->set('remote_user', 'root')
    ->set('writable_use_sudo', true)
    ->set('deploy_path', '/var/www/{{application}}');

desc('Runs the database migrations');
task('artisan:migrate', artisan('migrate:fresh --seed --force', ['skipIfNoEnv']));

// Hosts

// Hooks
// Hooks
task('deploy:php', function () {
    run('update-alternatives --set php /usr/bin/php8.3');
});
task('deploy:done', function () {
    run('update-alternatives --set php /usr/bin/php8.3');
});
task('deploy:npm', function () {
    run('npm install');
    run('npm run build');
});
after('deploy', 'deploy:done');
before('deploy:update_code', 'deploy:php');

after('deploy:failed', 'deploy:unlock');
