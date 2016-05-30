@setup
$server = 'spatie.be';
$siteName = 'dashboard.spatie.be';
$pathOnServer = '/home/forge/' . $siteName;
$deploymentId = 'Deployment of ' . $siteName . ':' . $pathOnServer . ' by ' . get_current_user(). ':';
@endsetup

@servers(['web' => $server, 'localhost' => '127.0.0.1'])

@task('display start message', ['on' => 'localhost'])
echo 'start deploying on {{ $server }}. Path: {{ $pathOnServer }}'
php artisan slack "{{ $deploymentId }} starting"
@endtask

@task('checkout master branch', ['on' => 'localhost'])
echo 'checking out the master branch'
git checkout master
@endtask

@task('generate assets', ['on' => 'localhost'])
echo 'generating assets'
gulp
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
composer install
php artisan cache:clear
@endtask

@task('clear assets on server', ['on' => 'web'])
echo 'clearing assets on server'
rm -rf '{{ $pathOnServer }}/public/assets'
@endtask

@task('upload generated assets', ['on' => 'localhost'])
echo 'uploading generated assets'
scp -r public/build {{ $server }}:{{$pathOnServer}}/public
@endtask

@task('bring app up', ['on' => 'web'])
cd '{{ $pathOnServer }}'
echo 'bringing app up'
php artisan up
@endtask

@task('reload php', ['on' => 'web'])
sudo service php7.0-fpm restart
sudo supervisorctl restart all
@endtask

@task('display success message', ['on' => 'localhost'])
echo "application successfully deployed"
php artisan slack "{{ $deploymentId }} :thumbsup: application successfully deployed"
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
generate assets
bring app down
pull changes on server
run composer install
clear assets on server
upload generated assets
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
