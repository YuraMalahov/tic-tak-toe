<?php

namespace Tic_tac_toe\Storage;

class SessionStorage implements Storage
{
    public function __construct()
    {
        session_start();
    }

    public function setData($key, $value)
    {
        $_SESSION[$key] = serialize($value);
    }

    public function getData($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            $tmp = $_SESSION[$key];
            return unserialize($tmp);
        }

        return null;
    }
}