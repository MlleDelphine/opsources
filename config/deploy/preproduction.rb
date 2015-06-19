set :stage, :preproduction

role :app, %w{deploy@preprod.opus.ariane.njord.fr :17645}
role :web, %w{deploy@preprod.opus.ariane.njord.fr :17645}
role :db,  %w{deploy@preprod.opus.ariane.njord.fr :17645}

server 'preprod.opus.ariane.njord.fr', user: 'deploy', roles: [:app], port: 17645

set :branch, 'dev-delphine'
set :deploy_to, '/var/www/preprod'

set :bundle_flags, "--no-deployment"