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
            'store_name' => 'lady_store',
            'location' => 'Riyadh-الرياض',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'phone' => "01025070424",
            'geoLocation' => '24.502081271239774,40.94955767784802',
            'whatsapp' => "01025070424",
        ]);
    }
}
