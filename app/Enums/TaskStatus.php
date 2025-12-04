<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    // Um helper pra gente pegar o texto bonitinho no front depois
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'A Fazer',
            self::IN_PROGRESS => 'Fazendo',
            self::COMPLETED => 'Concluído',
        };
    }
}
