<?php

namespace Tic_tac_toe\Ai;

use Tic_tac_toe\Field\Field;

interface Ai
{
    public function makeChoice(Field $field);
}
