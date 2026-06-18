<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([                  
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        User::firstOrCreate([                  
            'name' => '鈴木花子',
            'email' => 'suzuki@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        User::firstOrCreate([                  
            'name' => '田中一郎',
            'email' => 'tanaka@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        User::firstOrCreate([                  
            'name' => '佐藤美咲',
            'email' => 'sato@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        User::firstOrCreate([                  
            'name' => '高橋健太',
            'email' => 'takahashi@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }
}
