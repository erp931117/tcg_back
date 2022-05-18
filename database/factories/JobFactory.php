<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Job;

class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model=Job::class;
    public function definition()
    {
        return [
            'name'=>$this->faker->sentence(),
            'code'=>$this->faker->sentence(),
            'importance'=>$this->faker->randomElement(['alta','baja','media']),
            'is_boss'=>$this->faker->randomElement([true,false]),
        ];
    }
}
