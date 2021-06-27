<?php


namespace App\Http\Utils;


use Illuminate\Support\Arr;

class Resource
{
    public static function getCountryState($country)
    {
        $path = storage_path('app/data/states.json');
        $states = readJson($path);

       return Arr::where($states, function ($val, $key) use($country){
           return $val['country_code'] == $country;
        });
    }

}
