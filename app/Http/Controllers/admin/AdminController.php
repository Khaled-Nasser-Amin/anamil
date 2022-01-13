<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
       $date=$this->getDate();
        $current_month_orders=auth()->user()->orders()->where('payment_status','paid')->getOrdersThroughMonth($date['year'],$date['month']);
        $last_month_orders=auth()->user()->orders()->where('payment_status','paid')->getOrdersThroughMonth($date['last_year'],$date['last_month']);
        $orders=auth()->user()->orders->where('payment_status','paid')->count();
        $total_refunds=auth()->user()->refunds->where('refund_status','not refunded yet')->sum('total_refund_amount');
        $products=auth()->user()->products->where('isActive',1)->count();
        $inactive_products=auth()->user()->products()->where('isActive',0)->count();
        $inactive_colors_counter=0;
        $inactive_colors=auth()->user()->products_colors->map(function($item) use(&$inactive_colors_counter){
            if($item->sizes->sum('stock') == 0){
                $inactive_colors_counter+=1;
            }
            return $item->sizes->sum('stock');
        });
        $total_amount=auth()->user()->orders->where('payment_status','paid')->sum('pivot.total_amount');
        return view('admin.dashboard',compact('products','orders','total_amount','current_month_orders','last_month_orders','inactive_products','inactive_colors_counter','total_refunds'));

    }
    public function index_for_app(){
        $categories=Category::count();
        $products=Product::count();
        $users=Customer::count();
        $vendors=User::where('role' ,'!=' ,'admin')->count();
        $orders=Order::where('payment_status','paid')->count();
        $total_amount=Order::where('payment_status','paid')->sum('total_amount');
        $date=$this->getDate();
        $current_month_orders=Order::where('payment_status','paid')->getOrdersThroughMonth($date['year'],$date['month']);
        $last_month_orders=Order::where('payment_status','paid')->getOrdersThroughMonth($date['last_year'],$date['last_month']);

        return view('admin.dashboardForApp',compact('categories','products','vendors','users','orders','total_amount','current_month_orders','last_month_orders'));
    }

    protected function getDate(){
        $date['month']= Carbon::now()->month;
        $date['year']= Carbon::now()->year;
        if($date['month'] == 1){
            $date['last_month']=12;
            $date['last_year']=$date['year']-1;
        }else{
            $date['last_month']=$date['month']-1;
            $date['last_year']=$date['year']-1;
        }

        return $date;
    }
}
