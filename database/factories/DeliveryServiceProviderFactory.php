<?php

namespace Database\Factories;

use App\Models\DeliveryServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryServiceProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryServiceProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->randomNumber(9),
            'activation' => $this->faker->numberBetween(0,1),
            'email' => $this->faker->unique()->safeEmail,
            'image'=>$this->faker->numberBetween(1,10).".jpg",
            'driving_license'=>$this->faker->numberBetween(1,10).".jpg",
            'personal_id'=>$this->faker->numberBetween(1,10).".jpg",
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }
}
