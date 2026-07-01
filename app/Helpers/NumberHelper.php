<?php
namespace App\Helpers;

class NumberHelper
{
    public static function getNumberLetter($number)
    {
        $number = abs($number);
        $read = [0 => '', 1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima', 6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 9 => 'Sembilan', 10 => 'Sepuluh', 11 => 'Sebelas'];

        $result = "";

        if ($number < 12) {
            $result = $read[$number];
        } elseif ($number < 20) {
            $result = $read[$number - 10] . " Belas";
        } elseif ($number < 100) {
            $result = $read[$number / 10] . " Puluh ";
        } elseif ($number < 200) {
            $result = "Seratus " . self::getNumberLetter($number - 100);
        } elseif ($number < 1000) {
            $result = $read[$number / 100] . " Ratus ";
        } elseif ($number < 2000) {
            $result = "Seribu " . self::getNumberLetter($number - 1000);
        } elseif ($number < 1000000) {
            $result = $read[$number / 1000] . " Ribu ";
        } elseif ($number < 1000000000) {
            $result = $read[$number / 1000000] . " Juta ";
        } elseif ($number < 1000000000000) {
            $result = $read[$number / 1000000000] . " Milyar ";
        } elseif ($number < 1000000000000000) {
            $result = $read[$number / 1000000000000] . " Triliun ";
        } elseif ($number < 1000000000000000000) {
            $result = $read[$number / 1000000000000000] . " Kuadriliun ";
        } elseif ($number < 1000000000000000000000) {
            $result = $read[$number / 1000000000000000000] . " Kuintiliun ";
        } elseif ($number < 1000000000000000000000000) {
            $result = $read[$number / 1000000000000000000000] . " Sekstiliun ";
        }

        return $result;
    }
}
