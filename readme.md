# dashboard.spatie.be [![Composer Cache](https://shield.with.social/cc/github/spatie/dashboard.spatie.be/master.svg?style=flat-square)](https://packagist.org/packages/laravel/framework)

This repo contains the source code of our dashboard.

## Example

<img style="max-width:100%; height: auto" src="http://spatie.github.io/dashboard.spatie.be/images/screenshot20170620.jpg">

Our configured dashboard has following tiles:

- Twitter stream with all tweets mentioning and quoting [@spatie_be](https://twitter.com/spatie_be)
- Team calendar via [Google Calendar](https://google.com/calendar)
- Music currently playing via [Last.fm](https://last.fm)
- Clock/date/weather
- Team todo's via GitHub files
- [Packagist](https://packagist.org) downloads
- [NPM](https://npmjs.com) downloads
- Internet up/down via WebSockets

## How it works

Our own [Freek Van der Herten](https://twitter.com/freekmurze) gave a talk on the dashboard at Laracon EU where he explained how the dashboard works behind the screens. The talk was recorded and published [on Youtube](https://www.youtube.com/watch?v=jtB_rTh61Zo).

## Installation

Install this package by running cloning this repository and install like you normally install Laravel.

- Run `composer install` and `npm install yarn`
- Run `yarn` and `yarn run dev` to generate assets
- Copy `.env.example` to `.env` and fill your values (`php artisan key:generate`, database, pusher values etc)
- Run `php artisan migrate --seed`, this will seed a user based on your `BASIC_AUTH` `.env` values
- Start your queue listener and setup the Laravel scheduler.
- Open the dashboard in your browser, login and wait for the update events to fill the dashboard.

## Postcardware

If you are using our dashboard, please send us a postcard from your hometown.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

All postcards are published [on our website](https://spatie.be/en/opensource/postcards).

## Support
This dashboard is tailormade to be displayed on the wall mounted tv in our office. We do not follow [semver](http://semver.org) for this project and do not provide support whatsoever. However if you're a bit familiar with Laravel and Vue you should easily find your way.

For more details on the project, read our article about the [setup and components](https://murze.be/2017/06/building-realtime-dashboard-powered-laravel-vue-2017-edition/)

## License

This project and the Laravel framework are open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
