<?php


namespace App\Services;


use App\Jobs\SaveCurrencyRates;
use App\Models\Currency;
use App\Models\RateIncrements;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeService
{
    public static function currencyExchange(Currency $from, $amount, $toCurrency = 'USD')
    {
        $to = Currency::query()->where('code', $toCurrency)->first();
        $rateIncrease = RateIncrements::query()->where('from_id', $from->id)->where('to_id', $to->id)->first();
        $amount = (float)$amount;

        if ($from->exchange_from == "local") {
            $exchangeRate = $to->rate / $from->rate;
        } else {
            $exchange = self::getCurrencyRate($from->code, $to->code);

            $exchangeRate = $exchange ?? ($to->rate / $from->rate);
        }

        $exchangeAmount = (float)($exchangeRate) * $amount;

        if ($rateIncrease instanceof RateIncrements) {
            if ($rateIncrease->increase > 0) {
                $rateIncrement = $rateIncrease->increase;
                $exchangeRate = $exchangeRate * (1 + $rateIncrement / 100);
                $exchangeAmount = $exchangeAmount * (1 + $rateIncrement / 100);
            }
            if ($rateIncrease->decrease > 0) {
                $rateIncrement = $rateIncrease->decrease;
                $exchangeRate = $exchangeRate * (1 - $rateIncrement / 100);
                $exchangeAmount = $exchangeAmount * (1 - $rateIncrement / 100);
            }
        }

        return [
            'exchange_rate' => $exchangeRate,
            'from_currency' => $from->code,
            'to_currency' => $toCurrency,
            'exchange_amount' => $exchangeAmount,
            'converted' => true
        ];
    }

    public static function currencyExchangeBulk(Currency $from, $amount, $toCurrencies)
    {
        $ids = $toCurrencies->pluck('id');
        $rateIncreases = RateIncrements::query()->where('from_id', $from->id)->whereIn('to_id', $ids)->get();
        $_all = [];

        foreach ($toCurrencies as $to) {
            if (!$from->is($to)) {
                $rateIncrease = $rateIncreases->where('to_id', $to->id)->first();
                $amount = (float)$amount;

                $exchangeRate = $to->rate / $from->rate;
                $exchangeAmount = (float)($exchangeRate) * $amount;

                if ($rateIncrease instanceof RateIncrements) {
                    if ($rateIncrease->increase > 0) {
                        $rateIncrement = $rateIncrease->increase;
                        $exchangeRate = $exchangeRate * (1 + $rateIncrement / 100);
                        $exchangeAmount = $exchangeAmount * (1 + $rateIncrement / 100);
                    }
                    if ($rateIncrease->decrease > 0) {
                        $rateIncrement = $rateIncrease->decrease;
                        $exchangeRate = $exchangeRate * (1 - $rateIncrement / 100);
                        $exchangeAmount = $exchangeAmount * (1 - $rateIncrement / 100);
                    }
                }

                $_all[] = [
                    'exchange_rate' => $exchangeRate,
                    'from_currency' => $from->code,
                    'to_currency' => $to->code,
                    'exchange_amount' => $exchangeAmount,
                    'converted' => true
                ];
            }
        }


        return $_all;
    }

    public static function getCurrencyRate($from, $to)
    {
        $url = config('env.exchange_url');

        $response = Http::get($url, [
            'app_id' => config('env.exchange_key'),
            'symbols' => "$to,$from"
        ]);

        if ($response->status() == 200) {
            $rates = $response->json()['rates'];
            Log::info($rates);
            return $rates[$to] / $rates[$from];
        }

        return null;
    }

    public static function updateCurrencies()
    {
        $url = config('env.exchange_url');

        $response = Http::get($url, [
            'app_id' => config('env.exchange_key'),
        ]);

        if ($response->status() == 200) {
            SaveCurrencyRates::dispatch($response->json()['rates']);

            return [
                'status' => true,
                'message' => 'Rates Updated Successfully',
            ];
        }
        return [
            'status' => false,
            'message' => $response->json()['description'],
        ];
    }


}
