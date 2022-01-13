<?php

namespace Database\Factories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

class ColorFactory extends Factory
{

    protected $model = Color::class;

    public function definition()
    {
        $colors='abcdef0123456789';
        $name_ar=$this->faker->word;
        return [
            'color'=>"#".substr(str_shuffle($colors),0,6),
            'price'=>$this->faker->randomNumber(3),
            'sale'=>0,
            'product_id'=>$this->faker->numberBetween(1,50),
        ];
    }
}
