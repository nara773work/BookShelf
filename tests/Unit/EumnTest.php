<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Enums\ReadingPlanStatus;

class EumnTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_label()
    {
        $this->assertEquals(
            '読書中',
            ReadingPlanStatus::Reading->label()
        );

        $this->assertEquals(
            '読了',
            ReadingPlanStatus::Completed->label()
        );

        $this->assertEquals(
            '期限切れ',
            ReadingPlanStatus::Expired->label()
        );
    }
}
