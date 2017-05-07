<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use GuzzleHttp\Client;

class NhtsaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function makeGetRequest($year, $manufacturer, $model, $method="GET")
    {
        $url = "https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/$year/make/$manufacturer/model/$model?format=json";
        try {
            $client = new Client();
            $response = $client->request('GET', $url);
            return $this->createResponse($response, $method);
        } catch (\Exception $e) {
            print($e);
            return response()->json(["Count" => 0, "Results" => []], 500);
        }
    }

    public function getVehicleRatingById($vehicleId)
    {
        $url = "https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/$vehicleId?format=json";
        try {
            $client = new Client();
            $response = $client->request('GET', $url);
            return $this->getOverallRating($response);
        } catch (\Exception $e) {
            print($e);
            return response()->json(["Count" => 0, "Results" => []], 500);
        }

    }

    protected function getOverallRating($response)
    {
        $json = json_decode($response->getBody(), true);
        return $json["Results"][0]["OverallRating"];

    }

    protected function createResponse($response, $method)
    {
        $apiResponse = [];
        $json = json_decode($response->getBody(), true);
        if ($response->getStatusCode() == 200 && $json['Count'] != 0) {
            $apiResponse["Count"] = $json["Count"];
            $apiResponse["Results"] = [];
            foreach ($json["Results"] as $result) {
                $apiResponse["Results"][] = ["Description" => $result["VehicleDescription"], "VehicleId" => $result["VehicleId"]];
            }
            if ($method == "GET") {
                return  response()->json($apiResponse, 200);
            }
            return  response()->json($apiResponse, 201);
        }

        return response()->json(["Count" => 0, "Results" => []], 404);
    }
}
