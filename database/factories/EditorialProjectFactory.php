<?php

namespace Database\Factories;

use App\Models\EditorialProject;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EditorialProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EditorialProject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sector = Sector::all()->random(1)->first();
        $user = User::all()->random(1)->first();

        return [
            'title' => $this->faker->text(),
            'pages' => $this->faker->numberBetween(20, 5000),
            'price' => $this->faker->numberBetween(20, 5000),
            'cost' => $this->faker->numberBetween(20, 5000),
            'sector_id' => $sector->id,
            'author_id' => $user->id,
            'is_approved_by_ceo' => rand(0,1) == 1,
            'is_approved_by_editorial_director' => rand(0,1) == 1,
            'is_approved_by_editorial_responsible' => rand(0,1) == 1,
            'is_approved_by_sales_director' => rand(0,1) == 1,
        ];
    }
}