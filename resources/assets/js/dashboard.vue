<template>
    <div class="dashboard">
        <google-calendar grid="a1:a2"></google-calendar>
        <last-fm grid="b1:c1"></last-fm>
        <current-time grid="d1" dateformat="ddd DD/MM"></current-time>
        <packagist-statistics grid="b2"></packagist-statistics>
        <rain-forecast grid="c2"></rain-forecast>
        <internet-connection grid="d2"></internet-connection>
        <github-file file-name="freek" grid="a3"></github-file>
        <github-file file-name="rogier" grid="b3"></github-file>
        <github-file file-name="seb" grid="c3"></github-file>
        <github-file file-name="willem" grid="d3"></github-file>
    </div>
</template>

<script>
import CurrentTime from './components/current-time';
import GithubFile from './components/github-file';
import GoogleCalendar from './components/google-calendar';
import InternetConnection from './components/internet-connection';
import LastFm from './components/last-fm';
import PackagistStatistics from './components/packagist-statistics';
import RainForecast from './components/rain-forecast';

export default {

    components: {
        CurrentTime,
        GithubFile,
        GoogleCalendar,
        InternetConnection,
        LastFm,
        PackagistStatistics,
        RainForecast,
    },

};
</script>
