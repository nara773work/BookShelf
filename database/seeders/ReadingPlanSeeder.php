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
            'status' => ReadingPlanStatus::Planing->value,
            'target_date' => Carbon::today()->addDays(7),
        ]); 

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => 2,
            'status' => ReadingPlanStatus::Reading->value,
            'target_date' => Carbon::today()->addDays(2),
        ]); 

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => 3,
            'status' => ReadingPlanStatus::Completed->value,
            'target_date' => Carbon::today()->addDays(7),
        ]); 
    }
}
