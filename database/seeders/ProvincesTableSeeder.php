<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            [
                "province_id" => 1,
                "province_name" => "Metro Manila"
            ],
            [
                "province_id" => 2,
                "province_name" => "Ilocos Norte"
            ],
            [
                "province_id" => 3,
                "province_name" => "Ilocos Sur"
            ],
            [
                "province_id" => 4,
                "province_name " => "La Union"
            ],
            [
                "province_id" => 5,
                "province_name " => "Pangasinan"
            ],
            [
                "province_id" => 6,
                "province_name " => "Batanes"
            ],
            [
                "province_id" => 7,
                "province_name " => "Cagayan"
            ],
            [
                "province_id" => 8,
                "province_name " => "Isabela"
            ],
            [
                "province_id" => 9,
                "province_name " => "Nueva Vizcaya"
            ],
            [
                "province_id" => 10,
                "province_name " => "Quirino"
            ],
            [
                "province_id" => 11,
                "province_name " => "Bataan"
            ],
            [
                "province_id" => 12,
                "province_name " => "Bulacan"
            ],
            [
                "province_id" => 13,
                "province_name " => "Nueva Ecija"
            ],
            [
                "province_id" => 14,
                "province_name " => "Pampanga"
            ],
            [
                "province_id" => 15,
                "province_name " => "Tarlac"
            ],
            [
                "province_id" => 16,
                "province_name " => "Zambales"
            ],
            [
                "province_id" => 17,
                "province_name " => "Aurora"
            ],
            [
                "province_id" => 18,
                "province_name " => "Batangas"
            ],
            [
                "province_id" => 19,
                "province_name " => "Cavite"
            ],
            [
                "province_id" => 20,
                "province_name " => "Laguna"
            ],
            [
                "province_id" => 21,
                "province_name " => "Quezon"
            ],
            [
                "province_id" => 22,
                "province_name " => "Rizal"
            ],
            [
                "province_id" => 23,
                "province_name " => "Marinduque"
            ],
            [
                "province_id" => 24,
                "province_name " => "Occidental Mindoro"
            ],
            [
                "province_id" => 25,
                "province_name " => "Oriental Mindoro"
            ],
            [
                "province_id" => 26,
                "province_name " => "Palawan"
            ],
            [
                "province_id" => 27,
                "province_name " => "Romblon"
            ],
            [
                "province_id" => 28,
                "province_name " => "Albay"
            ],
            [
                "province_id" => 29,
                "province_name " => "Camarines Norte"
            ],
            [
                "province_id" => 30,
                "province_name " => "Camarines Sur"
            ],
            [
                "province_id" => 31,
                "province_name " => "Catanduanes"
            ],
            [
                "province_id" => 32,
                "province_name " => "Masbate"
            ],
            [
                "province_id" => 33,
                "province_name " => "Sorsogon"
            ],
            [
                "province_id" => 34,
                "province_name " => "Aklan"
            ],
            [
                "province_id" => 35,
                "province_name " => "Antique"
            ],
            [
                "province_id" => 36,
                "province_name " => "Capiz"
            ],
            [
                "province_id" => 37,
                "province_name " => "Iloilo"
            ],
            [
                "province_id" => 38,
                "province_name " => "Negros Occidental"
            ],
            [
                "province_id" => 39,
                "province_name " => "Guimaras"
            ],
            [
                "province_id" => 40,
                "province_name" => "Bohol"
            ],
            [
                "province_id" => 41,
                "province_name" => "Cebu"
            ],
            [
                "province_id" => 42,
                "province_name" => "Negros Oriental"
            ],
            [
                "province_id" => 43,
                "province_name" => "Siquijor"
            ],
            [
                "province_id" => 44,
                "province_name" => "Eastern Samar"
            ],
            [
                "province_id" => 45,
                "province_name" => "Leyte"
            ],
            [
                "province_id" => 46,
                "province_name" => "Northern Samar"
            ],
            [
                "province_id" => 47,
                "province_name" => "Samar"
            ],
            [
                "province_id" => 48,
                "province_name" => "Southern Leyte"
            ],
            [
                "province_id" => 49,
                "province_name" => "Biliran"
            ],
            [
                "province_id" => 50,
                "province_name" => "Zamboanga del Norte"
            ],
            [
                "province_id" => 51,
                "province_name" => "Zamboanga del Sur"
            ],
            [
                "province_id" => 52,
                "province_name" => "Zamboanga Sibugay"
            ],
            [
                "province_id" => 53,
                "province_name" => "Bukidnon"
            ],
            [
                "province_id" => 54,
                "province_name" => "Camiguin"
            ],
            [
                "province_id" => 55,
                "province_name" => "Lanao del Norte"
            ],
            [
                "province_id" => 56,
                "province_name" => "Misamis Occidental"
            ],
            [
                "province_id" => 57,
                "province_name" => "Misamis Oriental"
            ],
            [
                "province_id" => 58,
                "province_name" => "Davao del Norte"
            ],
            [
                "province_id" => 59,
                "province_name" => "Davao del Sur"
            ],
            [
                "province_id" => 60,
                "province_name" => "Davao Oriental"
            ],
            [
                "province_id" => 61,
                "province_name" => "Davao de Oro"
            ],
            [
                "province_id" => 62,
                "province_name" => "Davao Occidental"
            ],
            [
                "province_id" => 63,
                "province_name" => "Cotabato"
            ],
            [
                "province_id" => 64,
                "province_name" => "South Cotabato"
            ],
            [
                "province_id" => 65,
                "province_name" => "Sultan Kudarat"
            ],
            [
                "province_id" => 66,
                "province_name" => "Sarangani"
            ],
            [
                "province_id" => 67,
                "province_name " => "Abra"
            ],
            [
                "province_id" => 68,
                "province_name " => "Benguet"
            ],
            [
                "province_id" => 69,
                "province_name " => "Ifugao"
            ],
            [
                "province_id" => 70,
                "province_name " => "Kalinga"
            ],
            [
                "province_id" => 71,
                "province_name " => "Mountain Province"
            ],
            [
                "province_id" => 72,
                "province_name " => "Apayao"
            ],
            [
                "province_id" => 73,
                "province_name" => "Basilan"
            ],
            [
                "province_id" => 74,
                "province_name" => "Lanao del Sur"
            ],
            [
                "province_id" => 75,
                "province_name" => "Maguindanao"
            ],
            [
                "province_id" => 76,
                "province_name" => "Sulu"
            ],
            [
                "province_id" => 77,
                "province_name" => "Tawi-Tawi"
            ],
            [
                "province_id" => 78,
                "province_name" => "Agusan del Norte"
            ],
            [
                "province_id" => 79,
                "province_name" => "Agusan del Sur"
            ],
            [
                "province_id" => 80,
                "province_name" => "Surigao del Norte"
            ],
            [
                "province_id" => 81,
                "province_name" => "Surigao del Sur"
            ],
            [
                "province_id" => 82,
                "province_name" => "Dinagat Islands"
            ],
        ];

        DB::table('provinces')->insert($provinces);

    }
}