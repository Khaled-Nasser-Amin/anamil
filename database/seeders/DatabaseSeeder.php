<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DeliveryServiceProvider;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Images;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Size;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {

        $this->call(UserSeeder::class);

        // $vendors=User::factory(10)->create();
        // Customer::factory(10)->create();
        // DeliveryServiceProvider::factory(10)->create();
        // $taxes=Tax::factory(10)->create();

        // Category::factory()->count(10)->create();
        // $orders=Order::factory()->count(10)->create();
        // $products=Product::factory()->count(50)->create();
        // $colors=Color::factory()->count(150)->create();
        // $sizes=Size::factory()->count(450)->create();

        // Images::factory()->count(450)->create();

        // foreach($orders as $order){
        //     $new_colors=$colors->random(4);
        //     $new_sizes=$sizes->random(4);
        //     $new_products=$products->random(4);
        //     $new_vendors=$vendors->random(4);
        //     for($i=0; $i <= 3 ;$i++){
        //         $order->colors()->syncWithoutDetaching([$new_colors[$i]->id=>['quantity' => 3,'amount'=> 5,'total_amount' => 150,'color'=> $new_colors[$i]->color]]);
        //         $order->sizes()->syncWithoutDetaching([$new_sizes[$i]->id=>['quantity' => 3,'size'=> $new_sizes[$i]->size]]);
        //         $order->products()->syncWithoutDetaching([$new_products[$i]->id=>['name_ar' => $new_products[$i]->name_ar,'name_en'=> $new_products[$i]->name_en,'image' => $new_products[$i]->getAttributes()['image']]]);
        //         $order->vendors()->syncWithoutDetaching([$new_vendors[$i]->id=>['total_amount'=> 162,'subtotal' => 150,'taxes'=> 12]]);
        //     }
        // }
        // foreach($products as $products){
        //     $new_taxes=$taxes->random(4);
        //     $products->taxes()->syncWithoutDetaching($new_taxes->pluck('id')->toArray());

        // }


        //payment token seeder
        Setting::create();


    }
}
