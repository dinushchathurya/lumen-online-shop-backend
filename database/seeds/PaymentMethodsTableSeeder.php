<?php

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('payment_methods')
            ->insert(array('name' => 'Paypal', 'slug' => 'paypal'));
        \Illuminate\Support\Facades\DB::table('payment_methods')
            ->insert(array('name' => 'Pay on delivery', 'slug' => 'cash'));
    }
}
