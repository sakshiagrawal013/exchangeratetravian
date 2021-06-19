<?php

namespace App\Console\Commands;

use App\Utility\ExchangeRateUtility;
use Illuminate\Console\Command;
use App\Models\ValueObjects\ExchangeRate as ExchangeRateEntity;

/**
 * Class ExchangeRate
 * @package App\Console\Commands
 */
class ExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:exchange-rates';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exchange Rate Service';

    protected float $amount = 1;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $amount = $this->ask('What is amount in Euro?', $this->getAmount());

        if (!is_numeric($amount)) {
            $this->error('Amount is not valid!');
            return;
        }

        $this->setAmount($amount);

        $currencies = (array)$this->choice(
            'Which are the currencies do you want to exchange? Ex: USD, RUB, GBP',
            config('exchange.rate.allowed_currency'),
            1,
            2,
            true
        );

        $exchangeEntities = ExchangeRateUtility::create()->getResult();

        $headers = ['Date', 'Base Currency', 'Currency', 'Exchange Rate', 'Amount in Currency'];
        $body = [];

        if (!empty($currencies)) {
            $exchangeEntities = $exchangeEntities->filter(function ($entity) use ($currencies) {
                return in_array($entity->getCurrency(), $currencies);
            });
        }

        foreach ($exchangeEntities as $exchangeEntity) {
            $body[] = $this->getExchangeRateData($exchangeEntity);
        }
        $this->table($headers, $body);
    }

    /**
     * @return float
     */
    protected function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param ExchangeRateEntity $exchangeEntity
     * @return array
     */
    protected function getExchangeRateData(ExchangeRateEntity $exchangeEntity): array
    {
        return [
            $exchangeEntity->getDate(),
            $exchangeEntity->getBase(),
            $exchangeEntity->getCurrency(),
            $exchangeEntity->getRate(),
            $exchangeEntity->getExchangeRateAmount($this->getAmount()) . ' ' . $exchangeEntity->getCurrency(),
        ];
    }

    /**
     * @param float $amount
     */
    protected function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
