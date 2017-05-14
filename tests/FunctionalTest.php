<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class FunctionalTest extends TestCase
{
    /*
     * VehicleSafetyRatingsTest - Functional tests (end-to-end) that uses the API
     */ 

    public function testGetAudiA3Returns4Results()
    {
        $expectedResponse = [
            "Count" => 4,
            "Results" => [
                ["Description" => "2015 Audi A3 4 DR AWD", "VehicleId" => 9403],
                ["Description" => "2015 Audi A3 4 DR FWD", "VehicleId" => 9408],
                ["Description" => "2015 Audi A3 C AWD", "VehicleId" => 9405],
                ["Description" => "2015 Audi A3 C FWD", "VehicleId" => 9406],
            ]
        ];
        $this->get('/vehicles/2015/Audi/A3')
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponse);
    }

    public function testGetAcuraRDXAReturns2Results()
    {
        $expectedResponse = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2013 Acura RDX SUV 4WD", "VehicleId" => 7731],
                ["Description" => "2013 Acura RDX SUV FWD", "VehicleId" => 7520],
            ]
        ];
        $this->get('/vehicles/2013/Acura/rdx')
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponse);

    }

    public function testGetAnUnknowVehicleReturnsAppropriateResult()
    {
        $expectedResponse = [
            "Count" => 0,
            "Results" => []
        ];
        $this->get('/vehicles/2015/Acura/CarX')
            ->shouldReturnJson()
            ->seeStatusCode(404)
            ->seeJsonEquals($expectedResponse);
    }

    public function testGetAnUnknowYearReturnsAppropriateResult()
    {
        $expectedResponse = [
            "Count" => 0,
            "Results" => []
        ];
        $this->get('/vehicles/404/Acura/CarX')
            ->shouldReturnJson()
            ->seeStatusCode(404)
            ->seeJsonEquals($expectedResponse);
    }

    public function testCreateAcuraRDXTESTReturns2Results()
    {
        $expectedResponse = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2013 Acura RDX SUV 4WD", "VehicleId" => 7731],
                ["Description" => "2013 Acura RDX SUV FWD", "VehicleId" => 7520],
            ]
        ];
        $newVehicle = ['modelYear' => '2013', 'manufacturer' => 'Acura', 'model' => 'rdx'];
        $this->json('POST', '/vehicles', $newVehicle)
            ->shouldReturnJson()
            ->seeStatusCode(201)
            ->seeJsonEquals($expectedResponse);
    }

    public function testGetAllAcuraWithRatings()
    {
        $expectedResponse = [
            'Count' => 2,
            'Results' => [
                ["Description" => "2013 Acura RDX SUV 4WD", "VehicleId" => 7731, 'CrashRating' => 'Not Rated'],
                ["Description" => "2013 Acura RDX SUV FWD", "VehicleId" => 7520, 'CrashRating' => 'Not Rated'],
            ]
        ];
        $this->get('/vehicles/2013/Acura/rdx?withRating=true')
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponse);
    }

    public function testGetAllAudiA5WithRatings()
    {
        $expectedResponse = [
            'Count' => 2,
            'Results' => [
                ["Description" => "2015 Audi A5 2 DR AWD", "VehicleId" => 9409, "CrashRating" => "Not Rated"],
                ["Description" => "2015 Audi A5 C AWD", "VehicleId" => 9410, "CrashRating" => "Not Rated"],
            ]
        ];
        $this->get('/vehicles/2015/Audi/A5?withRating=true')
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponse);
    }

    public function testAllRequirement1Urls()
    {
        $url1 = '/vehicles/2015/Audi/A3';
        $url2 = '/vehicles/2015/Toyota/Yaris';
        $url3 = '/vehicles/2015/Ford/Crown Victoria';
        $url4 = '/vehicles/undefined/Ford/Fusion';
        $url5 = '/vehicles/2015/undefined/Fusion';
        $url6 = '/vehicles/2015/Ford/undefined';

        $expectedResponseUrl1 = [
            "Count" => 4,
            "Results" => [
                ["Description" => "2015 Audi A3 4 DR AWD", "VehicleId" => 9403],
                ["Description" => "2015 Audi A3 4 DR FWD", "VehicleId" => 9408],
                ["Description" => "2015 Audi A3 C AWD", "VehicleId" => 9405],
                ["Description" => "2015 Audi A3 C FWD", "VehicleId" => 9406],
            ]
        ];

        $expectedResponseUrl2 = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2015 Toyota Yaris 3 HB FWD", "VehicleId" => 9791],
                ["Description" => "2015 Toyota Yaris Liftback 5 HB FWD", "VehicleId" => 9146],
            ]
        ];

        $expectedResponseUrl3 = [
            "Count" => 0,
            "Results" => []
        ];

        $expectedResponseUrl4 = [
            "Count" => 0,
            "Results" => []
        ];

        $expectedResponseUrl5 = [
            "Count" => 0,
            "Results" => []
        ];

        $expectedResponseUrl6 = [
            "Count" => 0,
            "Results" => []
        ];

        $this->get($url1)
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponseUrl1);

        $this->get($url2)
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponseUrl2);

        $this->get($url3)
            ->shouldReturnJson()
            ->seeStatusCode(404)
            ->seeJsonEquals($expectedResponseUrl3);

        $this->get($url4)
            ->shouldReturnJson()
            ->seeStatusCode(400)
            ->seeJsonEquals($expectedResponseUrl4);

        $this->get($url5)
            ->shouldReturnJson()
            ->seeStatusCode(404)
            ->seeJsonEquals($expectedResponseUrl4);

        $this->get($url6)
            ->shouldReturnJson()
            ->seeStatusCode(404)
            ->seeJsonEquals($expectedResponseUrl4);
    }

    public function testAllRequirement2Urls()
    {
        $vehicleTest1 = ["modelYear" => 2015, "manufacturer" => "Audi", "model" => "A3"];
        $vehicleTest2 = ["modelYear" => 2015, "manufacturer" => "Toyota", "model" => "Yaris"];
        $vehicleTest3 = ["manufacturer" => "Honda", "model" => "Accord"];

        $url = '/vehicles';

        $expectedResponseUrl1 = [
            "Count" => 4,
            "Results" => [
                ["Description" => "2015 Audi A3 4 DR AWD", "VehicleId" => 9403],
                ["Description" => "2015 Audi A3 4 DR FWD", "VehicleId" => 9408],
                ["Description" => "2015 Audi A3 C AWD", "VehicleId" => 9405],
                ["Description" => "2015 Audi A3 C FWD", "VehicleId" => 9406],
            ]
        ];

        $expectedResponseUrl2 = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2015 Toyota Yaris 3 HB FWD", "VehicleId" => 9791],
                ["Description" => "2015 Toyota Yaris Liftback 5 HB FWD", "VehicleId" => 9146],
            ]
        ];

        $expectedResponseUrl3 = [
            "Count" => 0,
            "Results" => []
        ];

        $this->json('POST', '/vehicles', $vehicleTest1)
            ->shouldReturnJson()
            ->seeStatusCode(201)
            ->seeJsonEquals($expectedResponseUrl1);

        $this->json('POST', '/vehicles', $vehicleTest2)
            ->shouldReturnJson()
            ->seeStatusCode(201)
            ->seeJsonEquals($expectedResponseUrl2);

        $this->json('POST', '/vehicles', $vehicleTest3)
            ->shouldReturnJson()
            ->seeStatusCode(400)
            ->seeJsonEquals($expectedResponseUrl3);
    }

    public function testAllRequirement3Urls()
    {
        $url1 = '/vehicles/2015/Toyota/Yaris?withRating=true';
        $url2 = '/vehicles/2015/Toyota/Yaris?withRating=false';
        $url3 = '/vehicles/2015/Toyota/Yaris?withRating=banana';
        $url4 = '/vehicles/2015/Toyota/Yaris?withRating=2';
        $url5 = '/vehicles/2015/Toyota/Yaris?withRating=1';

        $expectedResponseUrl1 = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2015 Toyota Yaris 3 HB FWD", "VehicleId" => 9791, "CrashRating" => "Not Rated"],
                ["Description" => "2015 Toyota Yaris Liftback 5 HB FWD", "VehicleId" => 9146, "CrashRating" => "4"],
            ]
        ];

        $expectedResponseUrl2 = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2015 Toyota Yaris 3 HB FWD", "VehicleId" => 9791],
                ["Description" => "2015 Toyota Yaris Liftback 5 HB FWD", "VehicleId" => 9146],
            ]
        ];

        $expectedResponseUrl3 = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2015 Toyota Yaris 3 HB FWD", "VehicleId" => 9791],
                ["Description" => "2015 Toyota Yaris Liftback 5 HB FWD", "VehicleId" => 9146],
            ]
        ];

        $expectedResponseUrl4 = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2015 Toyota Yaris 3 HB FWD", "VehicleId" => 9791],
                ["Description" => "2015 Toyota Yaris Liftback 5 HB FWD", "VehicleId" => 9146],
            ]
        ];

        $expectedResponseUrl5 = [
            "Count" => 2,
            "Results" => [
                ["Description" => "2015 Toyota Yaris 3 HB FWD", "VehicleId" => 9791, "CrashRating" => "Not Rated"],
                ["Description" => "2015 Toyota Yaris Liftback 5 HB FWD", "VehicleId" => 9146, "CrashRating" => "4"],
            ]
        ];

        $this->get($url1)
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponseUrl1);

        $this->get($url2)
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponseUrl2);

        $this->get($url3)
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponseUrl3);

        $this->get($url4)
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponseUrl4);

        $this->get($url5)
            ->shouldReturnJson()
            ->seeStatusCode(200)
            ->seeJsonEquals($expectedResponseUrl5);
    }
}
