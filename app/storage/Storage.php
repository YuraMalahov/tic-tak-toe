<?php

namespace Tic_tac_toe\Storage;

interface Storage
{
    public function setData($key, $value);

    public function getData($key);
}