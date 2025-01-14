<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 0,
            'name' => '腕時計',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image' => 'Armani+Mens+Clock.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => 'HDD',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'image' => 'HDD+Hard+Disk.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => '玉ねぎ3束',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'image' => 'iLoveIMG+d.jpg',
            'condition' => 'やや傷や汚れあり'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => '革靴',
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'image' => 'Leather+Shoes+Product+Photo.jpg',
            'condition' => '状態が悪い'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => 'ノートPC',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'image' => 'Living+Room+Laptop.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => 'マイク',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'image' => 'Music+Mic+4632231.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => 'ショルダーバック',
            'price' => 3500,
            'description' => 'おしゃれなショルダーバック',
            'image' => 'Purse+fashion+pocket.jpg',
            'condition' => 'やや傷や汚れあり'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => 'タンブラー',
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'image' => 'Tumbler+souvenir.jpg',
            'condition' => '状態が悪い'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => 'コーヒーミル',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'image' => 'Waitress+with+Coffee+Grinder.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 0,
            'name' => 'メイクセット',
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'image' => '%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);
    }
}
