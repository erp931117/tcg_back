<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	User::create([
    		'name' => 'Jonh',
    		'last_name'=>'Doe',
    		'email'=>'jonh@test.com',
    		'password'=>Hash::make('123')
    	]);
    	$this->call(JobSeeder::class);
    }
}
