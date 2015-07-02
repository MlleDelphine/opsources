<?php

require 'recipe/symfony.php';

// Adding support for the Symfony3 directory structure
set('bin_dir', 'app');
set('var_dir', 'app');

//serverList('app/config/servers.yml');

server('preprod', 'preprod.opus.ariane.njord.fr', 17645)
    ->user('deploy')
    ->password('9h3MiJ28y6UiXpQ')
    ->stage('preproduction')
    ->env('deploy_path', '/var/www/preprod')
    ->env('branch','dev-delphine');

server('prod', 'opus.ariane.njord.fr', 17645)
    ->user('deploy')
    ->password('9h3MiJ28y6UiXpQ')
    ->stage('production')
    ->env('deploy_path', '/var/www/prod')
    ->env('branch','master');


set('repository', 'git@bitbucket.org:ariane-space/opus.git');

set('shared_dirs', ['app/logs', 'web/uploads']);

set('writeable_dirs', ['app/cache', 'app/logs']);

set('keep_releases', 4);

/*
task('deploy:doctrine:schema:update', function() {
    run('php {{release_path}}/' . trim(get('bin_dir'), '/') . '/console do:sc:up --force --env="prod"');
});

before('deploy:assetic:dump','deploy:doctrine:schema:update');*/


task('deploy:bower:install', function() {
    run('cd {{release_path}} && bower install');
});

before('deploy:assetic:dump','deploy:bower:install');

task('deploy:end', function () {
    run('{{release_path}}/sudo chmod -R 777 app/cache app/logs web/uploads');
});