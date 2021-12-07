
@setup
$server = "dashboard.spatie.be";
$userAndServer = 'forge@157.230.125.153';
$repository = "rubenvanassche/ella-ruben.be";
$baseDir = "/home/forge/{$server}";
$releasesDir = "{$baseDir}/releases";
$currentDir = "{$baseDir}/current";
$newReleaseName = date('Ymd-His');
$newReleaseDir = "{$releasesDir}/{$newReleaseName}";
$user = get_current_user();

function logMessage($message) {
return "echo '\033[32m" .$message. "\033[0m';\n";
}
@endsetup

@servers(['local' => '127.0.0.1', 'remote' => $userAndServer])

@macro('deploy')
startDeployment
cloneRepository
runComposer
runYarn
generateAssets
updateSymlinks
optimizeInstallation
migrateDatabase
blessNewRelease
cleanOldReleases
restart pi
finishDeploy
@endmacro

@macro('deploy-code')
deployOnlyCode
@endmacro

@macro('deploy-clean')
cloneRepository
runComposerWithDependencies
runYarn
generateAssets
updateSymlinks
optimizeInstallation
blessNewRelease
cleanOldReleases
restart pi
finishDeploy
@endmacro

@task('startDeployment', ['on' => 'local'])
{{ logMessage("🏃  Starting deployment...") }}
git checkout master
git pull origin master
@endtask

@task('cloneRepository', ['on' => 'remote'])
{{ logMessage("🌀  Cloning repository...") }}
[ -d {{ $releasesDir }} ] || mkdir {{ $releasesDir }};
cd {{ $releasesDir }};

# Create the release dir
mkdir {{ $newReleaseDir }};

# Clone the repo
GIT_SSH_COMMAND='ssh git clone --depth 1 git@github.com:{{ $repository }} {{ $newReleaseName }}

# Configure sparse checkout
cd {{ $newReleaseDir }}
git config core.sparsecheckout true
echo "*" > .git/info/sparse-checkout
echo "!storage" >> .git/info/sparse-checkout
echo "!public/build" >> .git/info/sparse-checkout
git read-tree -mu HEAD

# Mark release
cd {{ $newReleaseDir }}
echo "{{ $newReleaseName }}" > public/release-name.txt
@endtask

@task('runComposer', ['on' => 'remote'])
{{ logMessage("🚚  Running Composer...") }}
cd {{ $newReleaseDir }};
composer install --prefer-dist --no-scripts --no-dev -q -o;
{{--composer install --prefer-dist --no-scripts -q -o;--}}
@endtask

@task('runComposerWithDependencies', ['on' => 'remote'])
{{ logMessage("🚚  Running Composer...") }}
cd {{ $newReleaseDir }};
composer install;
@endtask

@task('runYarn', ['on' => 'remote'])
{{ logMessage("📦  Running Yarn...") }}
cd {{ $newReleaseDir }};
yarn config set ignore-engines true
yarn --frozen-lockfile
@endtask

@task('generateAssets', ['on' => 'remote'])
{{ logMessage("🌅  Generating assets...") }}
cd {{ $newReleaseDir }};
yarn run production
@endtask

@task('updateSymlinks', ['on' => 'remote'])
{{ logMessage("🔗  Updating symlinks to persistent data...") }}
# Remove the storage directory and replace with persistent data
rm -rf {{ $newReleaseDir }}/storage;
cd {{ $newReleaseDir }};
ln -nfs {{ $baseDir }}/persistent/storage storage;

rm -rf {{ $newReleaseDir }}/public/storage;
cd {{ $newReleaseDir }}/public/;
ln -nfs {{ $baseDir }}/persistent/storage/app/public storage;

# Import the environment config
cd {{ $newReleaseDir }};
ln -nfs {{ $baseDir }}/.env .env;
@endtask

@task('optimizeInstallation', ['on' => 'remote'])
{{ logMessage("✨  Optimizing installation...") }}
cd {{ $newReleaseDir }};
php artisan clear-compiled;
@endtask

@task('migrateDatabase', ['on' => 'remote'])
{{ logMessage("🙈  Migrating database...") }}
cd {{ $newReleaseDir }};
php artisan migrate --force;
@endtask


@task('blessNewRelease', ['on' => 'remote'])
{{ logMessage("🙏  Blessing new release...") }}
ln -nfs {{ $newReleaseDir }} {{ $currentDir }};
cd {{ $newReleaseDir }}
php artisan config:clear
php artisan cache:clear
php artisan config:cache

sudo service php8.1-fpm restart
sudo supervisorctl restart all
@endtask

@task('cleanOldReleases', ['on' => 'remote'])
{{ logMessage("🚾  Cleaning up old releases...") }}
# Delete all but the 5 most recent.
cd {{ $releasesDir }}
ls -dt {{ $releasesDir }}/* | tail -n +6 | xargs -d "\n" sudo chown -R forge .;
ls -dt {{ $releasesDir }}/* | tail -n +6 | xargs -d "\n" rm -rf;
@endtask

@task('restart pi', ['on' => 'localhost'])
ssh pi 'sudo reboot'
@endtask

@task('finishDeploy', ['on' => 'local'])
{{ logMessage("🚀  Application deployed!") }}
@endtask

@task('deployOnlyCode',['on' => 'remote'])
{{ logMessage("💻  Deploying code changes...") }}
cd {{ $currentDir }}
GIT_SSH_COMMAND='ssh git pull origin master
php artisan config:clear
php artisan cache:clear
php artisan config:cache
sudo supervisorctl restart all
sudo service php8.1-fpm restart
@endtask
