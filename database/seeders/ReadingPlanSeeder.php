<?php

namespace Database\Seeders;

use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReadingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yamada = User::where('email', 'yamada@example.com')->first();

        ReadingPlan::create([
            'user_id' => $yamada->id,
            'book_id' => 1,
            'status' => ReadingPlanStatus::Reading->value,
            'target_date' => Carbon::today()->addDays(3), // 通知来る
        ]);

        ReadingPlan::create([
            'user_id' => $yamada->id,
            'book_id' => 2,
            'status' => ReadingPlanStatus::Reading->value,
            'target_date' => Carbon::today()->addDays(5), // 通知来ない
        ]);

        ReadingPlan::create([
            'user_id' => $yamada->id,
            'book_id' => 3,
            'status' => ReadingPlanStatus::Completed->value,
            'target_date' => Carbon::today()->addDays(3),
        ]);

        ReadingPlan::create([
            'user_id' => $yamada->id,
            'book_id' => 4,
            'status' => ReadingPlanStatus::Expired->value,
            'target_date' => Carbon::yesterday(), // 通知来る
        ]);

        $suzuki = User::where('email', 'suzuki@example.com')->first();

        ReadingPlan::create([
            'user_id' => $suzuki->id,
            'book_id' => 1,
            'status' => ReadingPlanStatus::Reading->value,
            'target_date' => Carbon::today()->addDays(2),
        ]);
    }
}
