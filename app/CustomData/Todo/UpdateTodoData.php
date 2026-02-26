<?php

namespace App\CustomData\Todo;

use Kakaprodo\CustomData\CustomData;

class UpdateTodoData extends CustomData
{
    protected function expectedProperties(): array
    {
        return [
            'title?' => $this->dataType()->string(),
            'description?' => $this->dataType()->string(),
            'completed?' => $this->dataType()->bool(),
        ];
    }
}