<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'store_name' => $this->faker->unique()->name,
            'phone' => $this->faker->randomNumber(9),
            'whatsapp' => $this->faker->randomNumber(9),
            'location' => $this->faker->city,
            'activation' => $this->faker->numberBetween(0,1),
            'email' => $this->faker->unique()->safeEmail,
            'geoLocation' => '30.0012797,31.159573',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }

}
