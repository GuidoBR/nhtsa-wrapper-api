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
        if (!$this->validateRequest($year)) {
            return response()->json(["Count"=> 0, "Results"=> []], 400);
        }

        if ($this->filterQueryString($request)) {
            return $this->getAllWithRatings($year, $manufacturer, $model);
        };

        $nhtsa = new NHTSAController();
        return $nhtsa->makeGetRequest($year, $manufacturer, $model);
    }

    public function post(Request $request)
    {
        $year = $request->input("modelYear");
        $manufacturer = $request->input("manufacturer");
        $model = $request->input("model");

        if (!$this->validateRequest($year)) {
            return response()->json(["Count"=> 0, "Results"=> []], 400);
        }

        $nhtsa = new NHTSAController();
        return $nhtsa->makeGetRequest($year, $manufacturer, $model, "POST");
    }

    protected function getAllWithRatings($year, $manufacturer, $model)
    {
        $nhtsa = new NHTSAController();
        $response = $nhtsa->makeGetRequest($year, $manufacturer, $model);
        $responseContent = json_decode($response->content(), true);

        $apiResponse = [];
        $apiResponse["Count"] = $responseContent["Count"];
        foreach ($responseContent["Results"] as $res) {
            $res["CrashRating"] = $nhtsa->getVehicleRatingById($res["VehicleId"]);
            $apiResponse["Results"][] = $res;
        }

        return response()->json($apiResponse, 200);
    }

    protected function filterQueryString(Request $request)
    {
        $queryString = explode("=", $request->getQueryString());
        if (($queryString) && ($queryString[0] == "withRating") && (filter_var($queryString[1], FILTER_VALIDATE_BOOLEAN) == true)) {
            return true;
        }
        return false;
    }

    protected function validateRequest($year)
    {
        if (!is_numeric($year)) {
            return false;
        }

        return true;
    }
}
