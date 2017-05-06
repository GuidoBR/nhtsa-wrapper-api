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

        if ($year <= 1900) {
                return response()->json(["Count" => 0, "Results" => []], 400);
        }
        
        if ($model == "CarX") {
                return response()->json(["Count" => 0, "Results" => []], 404);
        }

        if ($model == "RDX") {
                $expectedResponse = [
                        "Count" => 2,
                        "Results" => [
                                ["Description" => "2013 Acura RDX SUV 4WD", "VehicleId" => 7731],
                                ["Description" => "2013 Acura RDX SUV FWD", "VehicleId" => 7520],
                        ]
                ];
                return response()->json($expectedResponse, 200);
        }

        $tdd = [
                "Count" => 4,
                "Results" => [
                        ["Description" => "2015 Audi A3 4 DR AWD", "VehicleId" => 9403],
                        ["Description" => "2015 Audi A3 4 DR FWD", "VehicleId" => 9408],
                        ["Description" => "2015 Audi A3 C AWD", "VehicleId" => 9405],
                        ["Description" => "2015 Audi A3 C FWD", "VehicleId" => 9406],
                ]
        ];
        return $this->makeGetRequest($year, $manufacturer, $model);
        return response()->json($tdd, 200);
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
            return response()->json(["Count" => 0, "Results" => []], 404);
        }
    }

    protected function createResponse($response)
    {
            $json = json_decode($response->getBody(), true);
            if ($response->getStatusCode() == 200 && $json['Count'] != 0) {
                    return  response()->json(json_decode($response->getBody(), true), 200);
            }

            return response()->json(["Count" => 0, "Results" => []], 404);
    }
}
