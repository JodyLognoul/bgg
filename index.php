<?php


use Carbon\Carbon;

require('vendor/autoload.php');
require_once('functions.php');

$mysqli = new mysqli(
    'database',
    'root',
    'vagrant',
    'bgg',
    '3306'
);

if ($mysqli->connect_errno) {
    printf("Échec de la connexion : %s\n", $mysqli->connect_error);
    exit();
}


ini_set('date.timezone', 'Europe/Zurich');

$now = Carbon::createFromDate(2017, 07, 3);

$now->setTimezone('Europe/Zurich');

$year = $now->year;
$month = $now->month;
$day = $now->day;
$week = $now->weekOfMonth;
$weekday = $now->dayOfWeek;
$nowString = $now->format('Y-m-d');
$nowPeriod = $now->format('Ym');


$sql = "SELECT EV.*
        FROM `events` EV
        RIGHT JOIN `events_meta` EM1 ON EM1.`event_id` = EV.`id`
        WHERE 
        (
          (DATEDIFF( '$nowString', repeat_start ) % repeat_day_interval = 0) 
        OR 
          (DATEDIFF( '$nowString', repeat_start ) / 7 % repeat_week_interval = 0)
        OR 
          (PERIOD_DIFF( '$nowPeriod', DATE_FORMAT(repeat_start, 'YYYYMM')) % repeat_month_interval = 0)
        )
        AND ( 
            (repeat_year = $year OR repeat_year = '*' )
            AND
            (repeat_month = $month OR repeat_month = '*' )
            AND
            (repeat_day_of_month = $day OR repeat_day_of_month = '*' )
            AND
            (repeat_week_of_month = $week OR repeat_week_of_month = '*' )
            AND
            (repeat_day_of_week = $weekday OR repeat_day_of_week = '*' )
            AND repeat_start <= DATE('$nowString')
        )";

$result = $mysqli->query($sql);


if ($mysqli->error) {
    printf("Échec de la connexion : %s\n", $mysqli->error);
    exit();
}

dd($result->fetch_all());
