<?php

namespace Tic_tac_toe\Field;

interface FieldDrawer
{
    public function __construct(Field $field);

    public function getData();
}
