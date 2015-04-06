set :application, "slims"
set :domain,      ""
set :deploy_to,   "/var/www/#{application}"
set :app_path,    "app"

set :user,             "ubuntu"
set :use_sudo,         false
set :interactive_mode, false

# Repository
set :repository,  "https://github.com/littlerobot/slims.git"
set :scm,         :git
set :branch,      "master"
set :deploy_via,  :remote_cache

set :model_manager, "doctrine"

# Files and folders that are shared between deployments
set :shared_files,        ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads"]

# Parameters
set :parameters_dir, "app/config/parameters"
set :parameters_file, false

# Vendors
set :copy_vendors, true

# Composer
set :use_composer, true
set :composer_options, "--no-dev -q --prefer-dist --optimize-autoloader --no-progress"

# File permissions
set :writable_dirs,       ["app/cache", "app/logs"]
set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true

role  :web,           domain
role  :app,           domain, :primary => true

set  :keep_releases,  5

after "deploy:share_childs", "upload_parameters"
after "deploy", "deploy:cleanup"
before "symfony:cache:warmup", "symfony:doctrine:migrations:migrate"

task :upload_parameters do
  origin_file = "app/config/parameters.prod.yml"
  destination_file = latest_release + "/app/config/parameters.yml"

  try_sudo "mkdir -p #{File.dirname(destination_file)}"
  top.upload(origin_file, destination_file)
end

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL
