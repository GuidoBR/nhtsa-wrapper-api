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

        $nhtsa = new NHTSAController();
        return $nhtsa->makeGetRequest($year, $manufacturer, $model);
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
}
