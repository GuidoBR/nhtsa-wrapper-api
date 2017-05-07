<?php
$app->get('/', function () {
    return view('index');
});

$app->get('/vehicles/{year}/{manufacturer}/{model}', 'VehicleSafetyRatingsController@getAll');
$app->get('/vehicles/{year}/{manufacturer}/{model}?withRating=True', 'VehicleSafetyRatingsController@getAll');
$app->post('/vehicles/', 'VehicleSafetyRatingsController@post');

$app->get('/healthcheck', function () {
    return response()->json(["message" => "API is UP"], 200);
});
