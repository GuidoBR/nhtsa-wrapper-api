<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

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

    public function getAll()
    {
        $tdd = [
                "Count" => 4,
                "Results" => [
                        ["Description" => "2015 Audi A3 4 DR AWD", "VehicleId" => 9403],
                        ["Description" => "2015 Audi A3 4 DR FWD", "VehicleId" => 9408],
                        ["Description" => "2015 Audi A3 C AWD", "VehicleId" => 9405],
                        ["Description" => "2015 Audi A3 C FWD", "VehicleId" => 9406],
                ]
        ];
        
        return response()->json($tdd, 200);
    }

    public function post()
    {
        return response()->json([], 201);
    }
    
    protected function getAllWithRatings()
    {

    }
}
