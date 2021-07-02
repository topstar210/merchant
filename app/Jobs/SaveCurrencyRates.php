<?php

namespace App\Jobs;

use App\Models\Currency;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveCurrencyRates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $rates;

    /**
     * SaveCurrencyRates constructor.
     * @param array $rates
     */
    public function __construct(array $rates)
    {
        //
        $this->rates = $rates;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        foreach ($this->rates as $key => $value) {
            Currency::query()->where('code', $key)->update(['rate' => $value]);
        }
    }
}
