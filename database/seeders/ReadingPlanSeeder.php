<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;
use Carbon\Carbon;
class ReadingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => 1,
            'status' => ReadingPlanStatus::Reading->value,
            'target_date' => Carbon::today()->addDays(3), //通知来る
        ]); 

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => 2,
            'status' => ReadingPlanStatus::Reading->value,
            'target_date' => Carbon::today()->addDays(5), //通知来ない
        ]); 

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => 3,
            'status' => ReadingPlanStatus::Completed->value,
            'target_date' => Carbon::today()->addDays(3),
        ]);

        
        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => 4,
            'status' => ReadingPlanStatus::Expired->value,
            'target_date' => Carbon::yesterday(), //通知来る
        ]); 
    }
}
