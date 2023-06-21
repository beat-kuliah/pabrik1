<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function fixPrice($value)
    {
        $price = strval($value);
        $remainder = strlen($price) % 3;
        $newRemainder = (strlen($price) - $remainder) / 3;

        $result = 'Rp. ';
        $counter = 0;
        if ($remainder > 0) {
            $result .= substr($price, $counter, $counter + $remainder) . '.';
            $counter += $remainder;
        }
        for ($i = 1; $i <= $newRemainder; $i++) {
            $result .= substr($price, $counter, $counter + 3);
            if ($i != $newRemainder)
                $result .= '.';
            $counter += 3;
        }

        return $result;
    }
}
