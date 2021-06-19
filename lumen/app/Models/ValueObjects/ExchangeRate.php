<?php

namespace App\Models\ValueObjects;

use Carbon\Carbon;

final class ExchangeRate
{
    private string $base;

    private string $currency;

    private float $rate;

    private Carbon $date;

    public function getDate(): Carbon
    {
        return $this->date;
    }

    public function setDate(Carbon $date): void
    {
        $this->date = $date;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function setBase(string $base): void
    {
        $this->base = $base;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function getExchangeRateAmount(float $amount): float
    {
        return round($amount * $this->getRate(), 2);
    }
}
