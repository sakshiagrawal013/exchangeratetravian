# Exchange Rate Service
Exchange Rate Service Based On Lumen platform dockerization

## About Project
Our shops store all product prices in EUR. We need to show the players the prices in their local currency.    
To archive this we need to create a service to take care of currency exchange rates.

## Start environment
To start docker environment, you must install Docker and digit this command in your favorite terminal:
```sh
make docker-start
```
When the process finished, Run below command and get into container working dir i.e /var/www
```sh
make docker-ssh
```

Then run below command into container
```sh
composer install
```
make exit from container.


## To Fetch Exchange Rate Service by Command Line
```sh
make fetch-exchange-rates
```

# Classes which are involved

### ExchangeRate Utility Class
```sh
App\Utility\ExchangeRateUtility
```

### ExchangeRate Value Object Class for entity
```sh
App\Models\ValueObjects\ExchangeRate
```

### ExchangeRate Command Class
```sh
App\Console\Commands\ExchangeRate
```

### Unit Test Class
```sh
Tests\Unit\ExchangeRateTest
```


## To run the PHPUnit Test
```sh
make tests
```

## Stop environment
To stop your environment you must digit this command in your favorite terminal:
```sh
make docker-stop
```