<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utility\ExchangeRateUtility;

class ExchangeRateTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function testExchangeRateUtilityInstance()
    {
        $instance = ExchangeRateUtility::create();

        $this->assertInstanceOf('App\Utility\ExchangeRateUtility', $instance);
    }

    /**
     *
     * @return void
     */
    public function testExchangeRateAmount()
    {
        $responses = ExchangeRateUtility::create()->getResult();
        $entity = $responses->first();

        $amount = 5;
        $this->assertEquals($amount*$entity->getRate(), $entity->getExchangeRateAmount($amount));
    }

    /**
     *
     * @return void
     */
    public function testExchangeRateResponseCountEqualToAllowedCurrency()
    {
        $responses = ExchangeRateUtility::create()->getResult();
        $this->assertEquals(count(config('exchange.rate.allowed_currency')), $responses->count());

        $result = [];
        foreach ($responses as $exchangeEntity) {
            $result[] = $exchangeEntity->getCurrency();
        }

        $this->assertEqualsCanonicalizing(config('exchange.rate.allowed_currency'), $result);
    }
}
