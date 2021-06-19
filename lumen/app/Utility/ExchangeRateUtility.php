<?php

namespace App\Utility;

use App\Models\ValueObjects\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExchangeRateUtility
{
    public const CURRENCY_NAMES = [
        'EUR' => 'Euro',
        'USD' => 'US DOLLAR',
        'GBP' => 'Pounds Sterling',
        'RUB' => 'Russian Ruble',
    ];

    /**
     * @return static
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * @return Collection
     */
    public function getResult(): Collection
    {
        $response = $this->curlRequest();
        if (!$response) {
            return collect();
        }

        return $this->getResponse($response);
    }

    /**
     * @param array $response
     * @return Collection
     */
    protected function getResponse(array $response): Collection
    {
        $collection = collect();

        if (!isset($response['rates']) || !isset($response['base'])) {
            return $collection;
        }

        $rates = array_filter(
            $response['rates'],
            fn ($key) => in_array($key, config('exchange.rate.allowed_currency')),
            ARRAY_FILTER_USE_KEY
        );

        $date = $response['timestamp'] ? Carbon::createFromTimestamp($response['timestamp']) : Carbon::now();

        foreach ($rates as $currency => $rate) {
            $exchangeRate = new ExchangeRate();
            $exchangeRate->setDate($date);
            $exchangeRate->setBase($this->getCurrencyAbbreviation($response['base']));
            $exchangeRate->setCurrency($this->getCurrencyAbbreviation($currency));
            $exchangeRate->setRate($rate);
            $collection->add($exchangeRate);
        }

        return $collection;
    }

    /**
     * @return string
     */
    protected function getClientURL(): string
    {
        return config('exchange.rate.domain') . '?access_key=' . config('exchange.rate.access_key');
    }

    /**
     * @param $inputCurrency
     * @return int|mixed|string
     */
    protected function getCurrencyAbbreviation($inputCurrency)
    {
        $key = array_search($inputCurrency, self::CURRENCY_NAMES);

        return $key ? $key : $inputCurrency;
    }

    /**
     * @return array|null
     */
    protected function curlRequest(): ?array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getClientURL(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 500,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        return $error ? null : json_decode($response, true);
    }
}
