<?php

namespace App\CustomData\Todo;

use Kakaprodo\CustomData\CustomData;

class CreateTodoData extends CustomData
{
    protected function expectedProperties(): array
    {
        return [
            'title' =>  $this->dataType()->string(),
            'description?' => $this->dataType()->string(),
        ];
    }

    public function boot()
    {
        // make validation before data is injected to action
    }
}
