<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('user_details')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'tel' => '',
                'nickname' => '',
                // 'furigana' => '',
                'zip' => '',
                'pref' => null,
                'city' => '',
                'address' => '',
                'building' => '',
                // 'gender' => null,
                'birthday' => null,
                'introduction' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'tel' => '',
                'nickname' => '',
                // 'furigana' => '',
                'zip' => '',
                'pref' => null,
                'city' => '',
                'address' => '',
                'building' => '',
                // 'gender' => null,
                'birthday' => null,
                'introduction' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'tel' => '',
                'nickname' => '',
                // 'furigana' => '',
                'zip' => '',
                'pref' => null,
                'city' => '',
                'address' => '',
                'building' => '',
                // 'gender' => null,
                'birthday' => null,
                'introduction' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
