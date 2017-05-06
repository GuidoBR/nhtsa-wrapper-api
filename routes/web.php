<?php
$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/vehicles/{year}/{manufacturer}/{model}', 'VehicleSafetyRatingsController@getAll');
$app->get('/vehicles/{year}/{manufacturer}/{model}?withRating=True', 'VehicleSafetyRatingsController@getAll');
$app->post('/vehicles/', 'VehicleSafetyRatingsController@post');

$app->get('/healthcheck', function () use ($app) {
    return "API is UP and running!";
});
