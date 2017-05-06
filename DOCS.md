FORMAT: 1A
HOST: https://vehicle-safety-api.herokuapp.com/

# Vehicle Safety API

This is a wrapper to the [NHTSA NCAP 5 Star Safety Ratings API](https://one.nhtsa.gov/webapi/Default.aspx?SafetyRatings/API/5)

## Vehicles Collection [/vehicles]

### Create a Vehicle [POST]

You may create your own vehicle using this action. It takes a JSON
object containing a model year, manufacter and model name.

+ Attributes
    + modelYear (number) - Year that model was manufactured. Example: 2015
    + manufacturer (string) - Company that manufactured the model. Example: Audi
    + model (string) - Name of the model. Example A3

+ Request (application/json)

        {
            "modelYear": 2015,
            "manufacturer": "Audi",
            "model": "A3"
        }


+ Response 201 (application/json)


        {   
            Count: 1,
            Results: [
                {   
                    Description: "2015 Audi A3",
                    VehicleId: 42
                }
            ]
        }
        
## Vehicle [/vehicles/{model_year}/{manufacturer}/{model}]

 + Parameters
    + model_year (number) - Year that model was manufactured. Example: 2015
    + manufacturer (string) - Company that manufactured the model. Example: Audi
    + model (string) - Name of the model. Example A3

### List a vehicle [GET]

+ Response 200 (application/json)

        {
            Count: <NUMBER OF RESULTS>,
            Results: [
                {   
                    Description: "<VEHICLE DESCRIPTION>",
                    VehicleId: <VEHICLE ID> 
                },  
                {   
                    Description: "<VEHICLE DESCRIPTION>",
                    VehicleId: <VEHICLE ID> 
                },  
                {   
                    Description: "<VEHICLE DESCRIPTION>",
                    VehicleId: <VEHICLE ID> 
                },  
                {   
                    Description: "<VEHICLE DESCRIPTION>",
                    VehicleId: <VEHICLE ID> 
                }   
            ]   
        }
+ Response 404 (application/json)

        {
            Count: 0,
            Results: []
        }

