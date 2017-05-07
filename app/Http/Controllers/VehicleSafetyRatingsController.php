<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use GuzzleHttp\Client;
use Laravel\Lumen\Routing\Controller as BaseController;

class VehicleSafetyRatingsController extends BaseController
{
    public function get(Request $request, $year, $manufacturer, $model)
    {
        $nhtsa = new NHTSAController();
        if (!$this->validateRequest($year)) {
            return $nhtsa->sendErrorResponse(400);
        }

        if ($this->filterQueryString($request)) {
            return $nhtsa->getVehiclesWithRating($year, $manufacturer, $model);
        };

        return $nhtsa->getVehicles($year, $manufacturer, $model);
    }

    public function post(Request $request)
    {
        $year = $request->input("modelYear");
        $manufacturer = $request->input("manufacturer");
        $model = $request->input("model");

        $nhtsa = new NHTSAController();

        if (!$this->validateRequest($year)) {
            return $nhtsa->sendErrorResponse(400);
        }

        return $nhtsa->getVehicles($year, $manufacturer, $model, "POST");
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
