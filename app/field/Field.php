<?php

namespace Tic_tac_toe\Field;

use Tic_tac_toe\Storage\Storage;

interface Field
{
    public function __construct(Storage $storage);

    public function setValue($x, $y, $value);

    public function getValue($x, $y);

    public function getData();

    public function reset();

    public function save();
}