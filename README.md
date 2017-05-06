# Vehicle Safety API

API to expose [NHTSA NCAP 5 Star Safety Ratings API](https://one.nhtsa.gov/webapi/Default.aspx?SafetyRatings/API/5)

## Installation

```
composer install
```

## Usage
```
php -S localhost:8000 -t public
```

I've used httpie to make requests to this API, but you could use with cURL or any tool you prefer.

### Get a vehicle

```
http localhost:8000/vehicles/2015/audi/a5
```

### Get a vehicle with ratings
```
http localhost:8000/vehicles/2015/audi/a3\?withRating\=true
```

### Post a vehicle

```
http POST localhost:8000/vehicles/ modelYear=2015 manufacturer=Audi model=A3
```

### Documentation

[Vehicle Safety API documentation](http://docs.vehiclesafetyapi.apiary.io)


## Running the tests

```
phpunit tests
```

## Built with

- [Lumen](https://lumen.laravel.com/)
