<?php

use App\District;
use App\Province;
use App\Ward;
use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = factory(Province::class, 5)->create();

        foreach ($provinces as $province) {
            $districts = factory(District::class, 4)
                ->create([
                    'province_id' => $province->id
                ]);

            foreach ($districts as $district) {
                factory(Ward::class, 3)
                    ->create([
                        'district_id' => $district->id
                    ]);
            }
        }
    }
}
