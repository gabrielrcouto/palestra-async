<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('users')->truncate();

		$faker = Faker\Factory::create('pt_BR');

		for ($i = 0; $i < 10000; $i++) {
			$user = [
				'name' => $faker->name,
				'email' => $faker->email,
				'city' => $faker->city,
				'phone' => $faker->phoneNumber,
				'company_id' => rand(1, 1000)
			];

			User::create($user);
		}
	}

}
