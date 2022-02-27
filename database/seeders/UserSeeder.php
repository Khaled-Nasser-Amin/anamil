<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run()
    {
        $user=User::create([
            'name' => 'admin',
            'role' => 'admin',
            'activation' => 1,
            'add_product' => 1,
            'store_name' => 'anamil',
            'location' => 'Riyadh-الرياض',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'phone' => "11111111111",
            'geoLocation' => '24.502081271239774,40.94955767784802',
            'whatsapp' => "111111111111",
        ]);
    }
}
