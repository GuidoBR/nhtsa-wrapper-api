<?php

// GET http://localhost:8080/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>
$app->get('/vehicles/{model_year}/{manufacturer}/{model}', function () use ($app) {
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/<MODEL YEAR>/make/>MANUFACTURER>/model/<MODEL>?format=json', []);
    return $app->version();
});


// POST http://localhost:8080/vehicles
$app->post('/vehicles', function () use ($app) {
    return $app->version();
});

// GET http://localhost:8080/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>?withRating=true
$app->get('/vehicles/{model_year}/{manufacturer}/{model}', function () use ($app) {
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/<MODEL YEAR>/make/>MANUFACTURER>/model/<MODEL>?format=json', []);
    return $app->version();
});
