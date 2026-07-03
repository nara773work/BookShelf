<?php

namespace App\Enums;

enum ReadingPlanStatus: string
{
    case Planing = 'planning';
    case Reading = 'reading';
    case Completed = 'completed';

    public function badgeClass(): string
    {
        return match ($this) {
            self::Planing => 'bg-green-100 text-green-800',
            self::Reading => 'bg-blue-100 text-blue-800',
            self::Completed => 'bg-green-100 text-green-800',
        };
    }

        public function label(): string
    {
        return match ($this) {
            self::Planing => '未読',
            self::Reading => '読書中',
            self::Completed => '読了',
        };

    }
}