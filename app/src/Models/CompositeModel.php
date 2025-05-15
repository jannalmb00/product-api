<?php

namespace App\Models;

use Slim\Exception\HttpBadRequestException;

class CompositeModel extends BaseModel
{

    function combineCoffeeCategory(array $filters): array
    {

        $url = "https://api.sampleapis.com/coffee/hot";

        $coffeeData = file_get_contents($url);
        if ($coffeeData === false) {
            // throw new HttpBadRequestException($request, );
        }
        dd($coffeeData);

        $coffeeData = json_decode($coffeeData, true);
        return array();
    }
}
