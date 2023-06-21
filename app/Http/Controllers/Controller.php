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

    public function fixDateOnly($val)
    {
        $day = date("d", strtotime($val));
        $month = date("m", strtotime($val));
        $year = date("Y", strtotime($val));
        $bulan = [
            '01' => "Januari",
            '02' => "Februari",
            '03' => "Maret",
            '04' => "April",
            '05' => "Mei",
            '06' => "Juni",
            '07' => "Juli",
            '08' => "Agustus",
            '09' => "September",
            '10' => "Oktober",
            '11' => "November",
            '12' => "Desember"
        ];
        $result = $day . ' ' . $bulan[$month] . ' ' . $year;

        return $result;
    }
}
