<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:aizensoosuke/fledger-dashboard');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

//set('writable_mode', 'skip');

// Tasks
task('deploy:info')->verbose();

task('npm:build', function () {
    runLocally('npm run build');
});
task('npm:upload', function () {
    run('mkdir -p {{release_path}}/public/build');
    upload('public/build/', '{{release_path}}/public/build/');
});
task('horizon:restart', function () {
    run('sudo systemctl restart horizon'.get('labels')['horizon-suffix']);
});
task('artisan:deploy:permissions', artisan('deploy:permissions'));
task('artisan:deploy:scout', artisan('deploy:scout'));

// Hosts

host('production')
//    ->setLabels([
//        'horizon-suffix' => '@make-test',
//    ])
    ->set('hostname', 'fledger-dashboard')
    ->set('branch', 'production')
    ->set('php_version', '8.3')
    ->set('deploy_path', '/srv/fledger');

// Hooks

after('deploy:prepare', 'npm:build');
after('npm:build', 'npm:upload');

after('artisan:migrate', 'artisan:deploy:permissions');
//after('artisan:deploy:permissions', 'artisan:deploy:scout');

//after('deploy:success', 'horizon:restart');
after('deploy:failed', 'deploy:unlock');
