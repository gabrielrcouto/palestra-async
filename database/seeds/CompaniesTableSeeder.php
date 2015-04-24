<?php

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CompaniesTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('companies')->truncate();

		$faker = Faker\Factory::create('pt_BR');

		for ($i = 0; $i < 1000; $i++) {
			$company = [
				'name' => $faker->company,
				'city' => $faker->city,
				'phone' => $faker->phoneNumber
			];

			Company::create($company);
		}
	}

}
