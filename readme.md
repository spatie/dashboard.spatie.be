# dashboard.spatie.be [![Composer Cache](https://shield.with.social/cc/github/spatie/dashboard.spatie.be/master.svg?style=flat-square)](https://packagist.org/packages/laravel/framework)

This repo contains the source code of our dashboard.

## Example

<img style="max-width:100%; height: auto" src="https://lh4.googleusercontent.com/uSv3nr5lLKxincVn6T8_RSSZfbZL6yvD1fRXuc82Yyu0fVysk-sr78rud_zXp40jL11TQZDx6KSrtw-igLhN=w3232-h1896-rw">

## Author

[@spatie_be](https://twitter.com/spatie_be)

Owner [Freek Van der Herten](https://twitter.com/freekmurze) gave a talk on the dashboard at Laracon EU where he explained how the dashboard works behind the screens. The talk was recorded and published [on Youtube](https://www.youtube.com/watch?v=jtB_rTh61Zo).

## Installation

Install this package by running cloning this repository and install like you normally install Laravel.

- Run `composer install` and `npm install yarn`
- Run `yarn` and `yarn run dev` to generate assets
- Copy `.env.example` to `.env` and fill your values (`php artisan key:generate`, database, pusher values etc)
- Create sqlite database `touch database/database.sqlite`
- Run `php artisan migrate --seed`, this will seed a user based on your `BASIC_AUTH` `.env` values
- Start php server `php -S localhost:5000 -t public`

Jira data:
- Fill jira host, user and password ([or api token](https://confluence.atlassian.com/cloud/api-tokens-938839638.html)) to `.env` file
- Crawl data by console command `php artisan dashboard:fetch-jira` or `php artisan dashboard:update` for fetching all services

That's all! Open the dashboard in your browser.

## Postcardware

If you are using our dashboard, please send us a postcard from your hometown.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

All postcards are published [on our website](https://spatie.be/en/opensource/postcards).

## Support
This dashboard is tailormade to be displayed on the wall mounted tv in our office. We do not follow [semver](http://semver.org) for this project and do not provide support whatsoever. However if you're a bit familiar with Laravel and Vue you should easily find your way.

For more details on the project, read our article about the [setup and components](https://murze.be/2017/06/building-realtime-dashboard-powered-laravel-vue-2017-edition/)

## License

This project and the Laravel framework are open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
