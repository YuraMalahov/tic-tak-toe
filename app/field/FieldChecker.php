<?php

namespace Tic_tac_toe\Field;

class FieldChecker
{
    const PLAYER = 'player';
    const AI = 'ai';
    const DRAW = 'draw';

    /**
     * check whether the player won
     *
     * @param Field $field
     * @param $type
     * @return bool
     */
    public function checkWin(Field $field, $type)
    {
        $data = $field->getData();

        $result = false;

        foreach ($data as $index => $line) {
            if ($this->checkLine($line, $type)) {
                $result = true;
            } elseif ($this->checkLine(array_column($data, $index), $type)) {
                $result = true;
            }
        }

        if ($this->checkDiagonalLine($data, $type)) {
            $result = true;
        } elseif ($this->checkDiagonalLine($data, $type, true)) {
            $result = true;
        }

        return $result;
    }

    /**
     * check line for match
     *
     * @param $data
     * @param $type
     * @return bool
     */
    protected function checkLine($data, $type)
    {
        $result = true;

        foreach ($data as $cell) {
            if ($cell !== $type) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * check diagonals for match
     *
     * @param $data
     * @param $type
     * @param bool $flag
     * @return bool
     */
    protected function checkDiagonalLine($data, $type, $flag = false)
    {
        $result = true;
        $length = count($data);

        for ($i = 0; $i < $length; $i++ ) {
            if ($flag) {
                if ($data[$i][$length - 1 - $i] !== $type) {
                    $result = false;
                }
            } else {
                if ($data[$i][$i] !== $type) {
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * check if Draw
     *
     * @param Field $field
     * @param $value
     * @return bool
     */
    public function checkDraw(Field $field, $value)
    {
        foreach ($field->getData() as $row) {
            foreach ($row as $cell) {
                if ($cell === $value) {
                    return false;
                }
            }
        }

        return true;
    }
}