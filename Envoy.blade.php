@setup
$server = 'dashboard.spatie.be';
$userAndServer = "forge@{$server}";
$siteName = 'dashboard.spatie.be';
$pathOnServer = '/home/forge/' . $siteName;
$deploymentId = 'Deployment of ' . $siteName . ':' . $pathOnServer . ' by ' . get_current_user(). ':';
@endsetup

@servers(['web' => $userAndServer, 'localhost' => '127.0.0.1'])

@task('display start message', ['on' => 'localhost'])
echo 'start deploying on {{ $server }}. Path: {{ $pathOnServer }}'
@endtask

@task('checkout master branch', ['on' => 'localhost'])
echo 'checking out the master branch'
git checkout master
@endtask

@task('bring app down', ['on' => 'web'])
echo 'bringing app down'
cd '{{ $pathOnServer }}'
php artisan down
@endtask

@task('pull changes on server', ['on' => 'web'])
cd '{{ $pathOnServer }}'
git pull origin master
@endtask

@task('run composer install', ['on' => 'web'])
echo 'running composer install'
cd '{{ $pathOnServer }}'
composer install --prefer-dist --no-scripts --no-dev -q -o
php artisan cache:clear
@endtask

@task('run yarn', ['on' => 'web'])
echo 'running yarn'
cd '{{ $pathOnServer }}'
yarn config set ignore-engines true
yarn
@endtask

@task('generate assets', ['on' => 'web'])
echo 'generating assets'
cd '{{ $pathOnServer }}'
yarn run production
@endtask

@task('bring app up', ['on' => 'web'])
cd '{{ $pathOnServer }}'
echo 'bringing app up'
php artisan up
@endtask

@task('reload php', ['on' => 'web'])
sudo service php7.1-fpm restart
sudo supervisorctl restart all
@endtask

@task('display success message', ['on' => 'localhost'])
echo "application successfully deployed"
@endtask

@task('restart pi', ['on' => 'localhost'])
ssh pi 'sudo reboot'
@endtask

@task('deployOnlyCode',['on' => 'web'])
cd {{ $pathOnServer }}
git pull origin master
@endtask

@macro('deploy')
display start message
checkout master branch
bring app down
pull changes on server
run composer install
run yarn
generate assets
reload php
bring app up
restart pi
display success message
@endmacro

@macro('deploy-code')
deployOnlyCode
reload php
restart pi
@endmacro

