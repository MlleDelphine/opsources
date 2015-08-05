# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'opus'
set :repo_url, 'git@bitbucket.org:ariane-space/opus.git'
set :repository_cache, "cached_copy"
set :deploy_via, :remote_cache

set :keep_releases, 3
set :use_sudo, true

# Symfony environment
set :symfony_env,           "dev"
set :app_path,              "app"
set :web_path,              "web"
set :log_path,              fetch(:app_path) + "/logs"
set :cache_path,            fetch(:app_path) + "/cache"
set :app_config_path,       fetch(:app_path) + "/config"
set :app_config_path_file,       fetch(:app_config_path) + "/parameters.yml"

# Files that need to remain the same between deploys
set :shared_files,          ["app/config/parameters.yml", "web/.htaccess"]
set :linked_files,          ["app/config/parameters.yml"]
set :linked_dirs,           [fetch(:log_path), fetch(:web_path) + "/uploads", fetch(:web_path) + "/public/img/IndexSlider"]

# Method used to set permissions (:chmod, :acl, or :chown)
set :file_permissions_users, ["www-data", "deploy"]
set :file_permissions_paths, [fetch(:log_path), fetch(:cache_path), fetch(:web_path) + "/uploads", fetch(:web_path) + "/public/"]
set :permission_method,      :acl
set :use_set_permissions,    true

# Symfony console path
set :symfony_console_path, fetch(:app_path) + "/console"
set :symfony_console_flags, "--no-debug -n"

# Assets install path
set :assets_install_path,   fetch(:web_path)
set :assets_install_flags,  '--symlink'

# Assetic dump flags
set :assetic_dump_flags,  ''
set :dump_assetic_assets,   true
fetch(:default_env).merge!(symfony_env: fetch(:symfony_env))

#Composer
set :use_composer,          true
set :composer_roles, :all
#set :composer_dump_autoload_flags, '--optimize'
set :composer_copy_previous_vendors, true
set :composer_options,  "--verbose --prefer-source"
set :update_vendors, true
set :copy_vendors, false
set :composer_download_url, "https://getcomposer.org/installer"
#set :composer_version, '1.0.0-alpha8' #(default: not set)

set :copy_files, ["vendor", "web/vendor"]

#Database
set :keep_db_backups, 20

#bower
set :bower_flags, '--quiet --config.interactive=false'
set :bower_roles, :web
#set :bower_target_path, nil

before "deploy:updated", "deploy:set_permissions:acl"
before "composer:install", "deploy:copy_files"


namespace :deploy do

  desc 'Save database'
  before :reverted, :restore_db do
    Rake::Task['database:restore'].invoke('current')
  end

  desc 'Revert database'
  before :updating, :save_db do
    Rake::Task['database:save'].invoke(true)
  end

  after :updated, :assets_install do
    invoke "symfony:console", "fos:js-routing:dump"
    invoke 'symfony:console', 'assetic:dump', '--no-interaction'
    invoke 'symfony:console', 'front:text'
    invoke 'symfony:console', 'cache:clear','--env=prod --no-interaction'
    invoke 'symfony:console', 'assets:install', 'web --symlink'
    invoke 'symfony:console', 'assetic:dump', '--env=prod --no-interaction'
  end

  desc 'Migrate database'
  task :migrate do
    invoke 'symfony:console', 'doctrine:migrations:migrate', '--no-interaction'
  end

  desc 'Restart application'
  task :restart do
    on roles(:app), in: :sequence, wait: 5 do
      # Your restart mechanism here, for example:
      # execute :touch, release_path.join('tmp/restart.txt')
    end
  end

  after :publishing, :restart

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end

end
