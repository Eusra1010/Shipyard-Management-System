<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipyardDemoSeeder extends Seeder
{
   
    public function run(): void
    {
        DB::table('berths')->insert([
            ['berth_id' => 1, 'berth_name' => 'Berth A', 'status' => 'free', 'ship_id' => null],
            ['berth_id' => 2, 'berth_name' => 'Berth B', 'status' => 'free', 'ship_id' => null],
            ['berth_id' => 3, 'berth_name' => 'Berth C', 'status' => 'free', 'ship_id' => null],
        ]);

        DB::table('ships')->insert([
            [
                'ship_id' => 1, 'ship_name' => 'MV Ocean Star', 'ship_type' => 'Cargo',
                'owner_name' => 'John Shipping Co.', 'tonnage' => 5000, 'flag_country' => 'Panama',
                'status' => 'docked', 'arrival_date' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'ship_id' => 2, 'ship_name' => 'MV Sea Falcon', 'ship_type' => 'Tanker',
                'owner_name' => 'Falcon Marine Ltd.', 'tonnage' => 8200, 'flag_country' => 'Liberia',
                'status' => 'docked', 'arrival_date' => now(), 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        DB::table('workers')->insert([
            ['worker_id' => 1, 'name' => 'Karim Hossain', 'role' => 'Welder', 'phone' => '01711111111', 'status' => 'available'],
            ['worker_id' => 2, 'name' => 'Rafiq Islam', 'role' => 'Painter', 'phone' => '01722222222', 'status' => 'available'],
            ['worker_id' => 3, 'name' => 'Sajid Ahmed', 'role' => 'Electrician', 'phone' => '01733333333', 'status' => 'available'],
        ]);

        DB::table('materials')->insert([
            ['material_id' => 1, 'name' => 'Steel Plate', 'quantity' => 100, 'unit' => 'kg'],
            ['material_id' => 2, 'name' => 'Paint', 'quantity' => 50, 'unit' => 'litre'],
            ['material_id' => 3, 'name' => 'Bolts', 'quantity' => 500, 'unit' => 'pieces'],
        ]);

       
        DB::table('berths')->where('berth_id', 1)->update(['ship_id' => 1, 'status' => 'occupied']);
        DB::table('workers')->whereIn('worker_id', [1, 3])->update(['status' => 'busy']);

       
        DB::table('work_orders')->insert([
            'order_id' => 1, 'ship_id' => 1, 'title' => 'Engine Repair',
            'description' => 'Main engine overhaul and inspection',
            'status' => 'in_progress', 'start_date' => now(), 'created_at' => now(),
        ]);

        DB::table('work_order_workers')->insert([
            ['id' => 1, 'order_id' => 1, 'worker_id' => 1],
            ['id' => 2, 'order_id' => 1, 'worker_id' => 3],
        ]);

        
        DB::table('material_usage')->insert([
            'usage_id' => 1, 'order_id' => 1, 'material_id' => 1, 'qty_used' => 10, 'used_at' => now(),
        ]);
        DB::table('materials')->where('material_id', 1)->decrement('quantity', 10);
    }
}
