<?php

namespace App\Enums;

/**
 * 読書計画の状態を管理するEnum。
 */
enum ReadingPlanStatus: string
{
    case Reading = 'reading';
    case Completed = 'completed';
    case Expired = 'expired';

    public function badgeClass(): string
    {
        return match ($this) {
            self::Reading => 'bg-blue-100 text-blue-800',
            self::Completed => 'bg-green-100 text-green-800',
            self::Expired => 'bg-red-100 text-green-800',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Reading => '読書中',
            self::Completed => '読了',
            self::Expired => '期限切れ',
        };

    }
}
