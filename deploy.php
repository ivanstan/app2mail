<?php /** @noinspection ALL */

namespace Deployer;

require 'recipe/symfony.php';

set('repository', 'https://github.com/ivanstan/app2mail');
set('git_tty', true);
set('bin_dir', 'bin');
set('http_user', 'glutenfr');
set('writable_mode', 'chmod');
set('default_stage', 'production');
add('shared_files', ['.env']);
add('shared_dirs', ['var', 'config/secrets']);
add('writable_dirs', []);

set('composer_options', '--verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader');
set('composer_action', 'install');
set('bin/composer', '~/bin/composer.phar');

host('ivanstanojevic.me')
    ->user('glutenfr')
    ->port(2233)
    ->stage('production')
    ->set('deploy_path', '~/projects/app2mail.ivanstanojevic.me');

task('test', function () {
    set('symfony_env', 'dev');
//    runLocally('bin/phpunit');
});

task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        warning('To speed up composer installation setup "unzip" command with PHP zip extension.');
    }
    run('cd {{release_path}} && {{bin/composer}} {{composer_action}} {{composer_options}} 2>&1');
});

task('deploy:dump-env', function () {
    run('cd {{release_path}} && {{bin/composer}} dump-env prod');
});

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:assets',
    'deploy:vendors',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:dump-env',
    'deploy:writable',
    'database:migrate',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

before('deploy', 'test');
after('deploy:failed', 'deploy:unlock');
