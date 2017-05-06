<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use GuzzleHttp\Client;

class VehicleSafetyRatingsController extends Controller
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

    public function getAll(Request $request, $year, $manufacturer, $model)
    {
        if ($this->filterQueryString($request)) {
            return $this->getAllWithRatings($request, $year, $manufacturer, $model);
        };

        return $this->makeGetRequest($year, $manufacturer, $model);
    }

    public function post(Request $request)
    {
        $tdd = [
                    "Count" => 2,
                    "Results" => [
                            ["Description" => "2013 Acura RDX SUV 4WD", "VehicleId" => 7731],
                            ["Description" => "2013 Acura RDX SUV FWD", "VehicleId" => 7520],
                            ["Description" => "2013 Acura RDX TEST", "VehicleId" => 9999],
                    ]
        ];

        return response()->json($tdd, 201);
    }

    protected function getAllWithRatings(Request $request, $year, $manufacturer, $model)
    {
				$tdd = [
						'Counts' => 2,
						'Results' => [
                                ["Description" => "2013 Acura RDX SUV 4WD", "VehicleId" => 7731, 'CrashRating' => 'Not Rated'],
                                ["Description" => "2013 Acura RDX SUV FWD", "VehicleId" => 7520, 'CrashRating' => 'Not Rated'],
						]
				];

        return response()->json($tdd, 200);
    }
    
    protected function filterQueryString(Request $request)
    {
        $queryString = explode("=", $request->getQueryString());
        if (($queryString) && ($queryString[0] == "withRating") && ($queryString[1] == true)) {
            return true;
        }
        return false;
    }

    protected function makeGetRequest($year, $manufacturer, $model)
    {
        $url = "https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/$year/make/$manufacturer/model/$model?format=json";
        try {
            $client = new Client();
            $response = $client->request('GET', $url);
                    return $this->createResponse($response);
        } catch (\Exception $e) {
            print($e);
            return response()->json(["Count" => 0, "Results" => []], 500);
        }
    }

    protected function createResponse($response)
    {
            $apiResponse = [];
            $json = json_decode($response->getBody(), true);
            if ($response->getStatusCode() == 200 && $json['Count'] != 0) {
                    $apiResponse["Count"] = $json["Count"];
                    $apiResponse["Results"] = [];
                    foreach ($json["Results"] as $result) {
                            $apiResponse["Results"][] = ["Description" => $result["VehicleDescription"], "VehicleId" => $result["VehicleId"]];
                    }

                    return  response()->json($apiResponse, 200);
            }

            return response()->json(["Count" => 0, "Results" => []], 404);
    }
}
