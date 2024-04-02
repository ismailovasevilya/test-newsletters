<?php

namespace App\Enums;

class NewsLetterStatusEnum
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const STOPPED = 'stopped';
    const FINISHED = 'finished';

    public static function getAll(): array
    {
        return [
            self::ACTIVE => [
                'value' => self::ACTIVE,
                'label' => 'В процессе',
            ],
            self::INACTIVE => [
                'value' => self::INACTIVE,
                'label' => 'Неактивная',
            ],
            self::STOPPED => [
                'value' => self::STOPPED,
                'label' => 'Остановлена',
            ],
            self::FINISHED => [
                'value' => self::FINISHED,
                'label' => 'Завершена',
            ]
        ];
    }
}
