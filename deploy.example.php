<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'pos-katombo');

// Project repository
set('repository', 'git@gitlab.com:layanacorp/layana_POS_Katombo.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

//set('writable_mode', 'chown');

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('http_user', 'admin');

// Hosts

host('128.199.92.106')
    ->user('admin')
    ->identityFile('~/.ssh/layana_server_rsa')
    ->set('deploy_path', '/home/admin/web/pos.layana.id/public_html');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

//before('deploy:symlink', 'artisan:migrate');

