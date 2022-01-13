<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name_en=$this->faker->word;
        $name_ar=$this->faker->word;
        return [
            'name_ar' => ucfirst($name_ar),
            'name_en' => ucfirst($name_en) ,
            'slug' => $name_en."-".$name_ar,
        ];
    }
}
