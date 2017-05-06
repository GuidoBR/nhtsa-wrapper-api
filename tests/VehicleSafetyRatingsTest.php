<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class VehicleSafetyRatingsTest extends TestCase
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
                $this->get('/vehicles/2013/Acura/RDX')
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

        public function testCreateAcuraRDXTESTReturns3Results()
        {
                $expectedResponse = [
                        "Count" => 2,
                        "Results" => [
                                ["Description" => "2013 Acura RDX SUV 4WD", "VehicleId" => 7731],
                                ["Description" => "2013 Acura RDX SUV FWD", "VehicleId" => 7520],
                                ["Description" => "2013 Acura RDX TEST", "VehicleId" => 9999],
                        ]
                ];
				$newVehicle = ['modelYear' => '2013', 'manufacturer' => 'Acura', 'model' => 'RDX TEST'];
                $this->json('POST', '/vehicles', $newVehicle)
						->shouldReturnJson()
						->seeStatusCode(201)
                        ->seeJsonEquals($expectedResponse);
        }

		public function testGetAllAcuraWithRatings()
		{
				$expectedResponse = [
						'Counts' => 2,
						'Results' => [
                                ["Description" => "2013 Acura RDX SUV 4WD", "VehicleId" => 7731, 'CrashRating' => 'Not Rated'],
                                ["Description" => "2013 Acura RDX SUV FWD", "VehicleId" => 7520, 'CrashRating' => 'Not Rated'],
						]
				];
				$this->get('/vehicles/2013/Acura/RDX?withRating=true')
						->shouldReturnJson()
						->seeStatusCode(200)
                        ->seeJsonEquals($expectedResponse);
		}

}
