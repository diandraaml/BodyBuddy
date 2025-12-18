<?php
namespace App\Controllers;

class BmiController {

    public function calculate($height, $weight) {
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);

        if ($bmi < 18.5) {
            $category = 'Kekurangan Berat Badan';
        } elseif ($bmi < 25) {
            $category = 'Ideal';
        } elseif ($bmi < 30) {
            $category = 'Kelebihan Berat Badan';
        } else {
            $category = 'Obesitas';
        }

        return [
            'bmi' => round($bmi, 2),
            'category' => $category
        ];
    }
}
