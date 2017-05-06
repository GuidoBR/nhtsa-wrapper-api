<?php
$app->get('/', function () use ($app) {
    return $app->version();
});

// GET /vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>
$app->get('/vehicles/{model_year}/{manufacturer}/{model}', function () use ($app) {
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/<MODEL YEAR>/make/>MANUFACTURER>/model/<MODEL>?format=json', []);
    return $app->version();
});


// POST /vehicles
$app->post('/vehicles', function () use ($app) {
    return $app->version();
});

// GET /vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>?withRating=true
$app->get('/vehicles/{model_year}/{manufacturer}/{model}', function () use ($app) {
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/<MODEL YEAR>/make/>MANUFACTURER>/model/<MODEL>?format=json', []);
    return $app->version();
});


// GET /healthcheck
$app->get('/healthcheck', function () use ($app) {
    return "API is UP and running!";
});
