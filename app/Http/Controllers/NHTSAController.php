<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use GuzzleHttp\Client;
use Laravel\Lumen\Routing\Controller as BaseController;

class NhtsaController extends BaseController
{
    public function getVehicles($year, $manufacturer, $model, $method="GET")
    {
        $manufacturer = ucfirst($manufacturer);

        $url = "https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/$year/make/$manufacturer/model/$model?format=json";
        try {
            $client = new Client();
            $response = $client->request('GET', $url);
            return $this->createResponse($response, $method);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(500);
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
            return $this->sendErrorResponse(500);
        }
    }

    public function getVehiclesWithRating($year, $manufacturer, $model, $method="GET")
    {
        $response = $this->getVehicles($year, $manufacturer, $model, $method);
        $responseContent = json_decode($response->content(), true);

        $apiResponse = [];
        $apiResponse["Count"] = $responseContent["Count"];
        foreach ($responseContent["Results"] as $res) {
            $res["CrashRating"] = $this->getVehicleRatingById($res["VehicleId"]);
            $apiResponse["Results"][] = $res;
        }

        return response()->json($apiResponse, 200);
    }

    public function sendErrorResponse($statusCode)
    {
        return response()->json(["Count" => 0, "Results" => []], $statusCode);
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
            $successStatus = ($method == "GET") ? 200 : 201;
            return  response()->json($apiResponse, $successStatus);
        }

        return $this->sendErrorResponse(404);
    }
}
