<?php

namespace Tic_tac_toe\Ai;

use Tic_tac_toe\Field\Field;
use Tic_tac_toe\Field\TicTacToeField;

class TestAi implements Ai
{
    protected $data = [];
    protected $point = null;

    /**
     * AI make choice
     *
     * @param Field $field
     * @return null
     */
    public function makeChoice(Field $field)
    {
        $this->data = $field->getData();

        $this->compute();

        return $this->point;
    }

    /**
     * run computing
     */
    protected function compute()
    {
        $this->lookForOptions();
    }

    /**
     * select from options best way
     */
    protected function lookForOptions()
    {
        if ($this->checkWin()) {
            return;
        } elseif ($this->checkBlock()) {
            return;
        } elseif ($this->checkFork()) {
            return;
        } elseif ($this->checkBlockFork()) {
            return;
        } elseif ($this->checkCenter()) {
            return;
        } elseif ($this->checkOppositeCorner()) {
            return;
        } elseif ($this->checkEmptyCorner()) {
            return;
        } else {
            $this->checkEmptySide();
        }
    }

    /**
     * get possible lines
     *
     * @return array
     */
    protected function getPaths()
    {
        return [
            [[0, 0], [0, 1], [0, 2]],
            [[1, 0], [1, 1], [1, 2]],
            [[2, 0], [2, 1], [2, 2]],

            [[0, 0], [1, 0], [2, 0]],
            [[0, 1], [1, 1], [2, 1]],
            [[0, 2], [1, 2], [2, 2]],

            [[0, 0], [1, 1], [2, 2]],
            [[0, 2], [1, 1], [2, 0]]
        ];
    }

    /**
     * check for win in next move
     *
     * @param $player
     * @param $opponent
     * @return bool
     */
    protected function playerGoingToWin($player, $opponent)
    {
        $points = null;
        $emptyCell = null;
        $opponentPoints = null;

        foreach ($this->getPaths() as $line) {
            foreach ($line as $element) {
                if ($this->data[$element[0]][$element[1]] === $player) {
                    $points++;
                } elseif ($this->data[$element[0]][$element[1]] === $opponent) {
                    $opponentPoints++;
                } else {
                    $emptyCell = $element;
                }
            }

            if ($points === 2 && isset($emptyCell) && $opponentPoints === null) {
                $this->point = $emptyCell;
                return true;
            } else {
                $points = null;
                $emptyCell = null;
                $opponentPoints = null;
            }
        }

        return false;
    }

    /**
     * check if AI can win in next move
     *
     * @return bool
     */
    protected function checkWin()
    {
        return $this->playerGoingToWin(
            TicTacToeField::AI,
            TicTacToeField::PLAYER
        );
    }

    /**
     * check for block if Player can win in next step
     *
     * @return bool
     */
    protected function checkBlock()
    {
        return $this->playerGoingToWin(
            TicTacToeField::PLAYER,
            TicTacToeField::AI
        );
    }

    /**
     * check for fork
     *
     * @return bool
     */
    protected function checkFork()
    {
        $points = null;
        $opponentPoints = null;

        $emptyCells = [];
        $possibleLines = [];

        foreach ($this->getPaths() as $line) {
            foreach ($line as $element) {
                if (
                    $this->data[$element[0]][$element[1]] ===
                    TicTacToeField::AI
                ) {
                    $points++;
                } elseif (
                    $this->data[$element[0]][$element[1]] ===
                    TicTacToeField::PLAYER
                ) {
                    $opponentPoints++;
                } else {
                    if (!in_array($element, $emptyCells)) {
                        $emptyCells[] = $element;
                    }
                }
            }

            if ($points === 1 && $opponentPoints === null) {
                if (!in_array($line, $possibleLines)) {
                    $possibleLines[] = $line;
                }
            } else {
                $points = null;
                $opponentPoints = null;
            }
        }

        foreach ($emptyCells as $cell) {
            $enters = 0;
            foreach ($possibleLines as $line) {
                if (in_array($cell, $line)) {
                    $enters++;
                }

                if ($enters >= 2) {
                    $this->point = $cell;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * check for block Player's fork
     *
     * @return bool
     */
    protected function checkBlockFork()
    {
        $points = null;
        $opponentPoints = null;
        $opponentSummaryPoints = 0;

        $emptyCells = [];
        $possibleLines = [];

        foreach ($this->getPaths() as $line) {
            foreach ($line as $element) {
                if (
                    $this->data[$element[0]][$element[1]] ===
                    TicTacToeField::PLAYER
                ) {
                    $opponentSummaryPoints++;
                    $opponentPoints++;
                } elseif (
                    $this->data[$element[0]][$element[1]] ===
                    TicTacToeField::AI
                ) {
                    $points++;
                } else {
                    if (!in_array($element, $emptyCells)) {
                        $emptyCells[] = $element;
                    }
                }
            }

            if (
                $points === 1 && $opponentPoints === null &&
                $opponentSummaryPoints > 1
            ) {
                if (!in_array($line, $possibleLines)) {
                    $possibleLines[] = $line;
                }
            } else {
                $points = null;
                $opponentPoints = null;
            }
        }

        foreach ($emptyCells as $cell) {
            $enters = 0;
            foreach ($possibleLines as $line) {
                if (in_array($cell, $line)) {
                    $enters++;
                }

                if ($enters >= 2) {
                    $this->point = $cell;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * check if AI can make move into center
     *
     * @return bool
     */
    protected function checkCenter()
    {
        if ($this->data[1][1] === TicTacToeField::NOT_SET) {
            $this->point = [1, 1];
            return true;
        }

        return false;
    }

    /**
     * check if AI can make move into corner opposite to player
     *
     * @return bool
     */
    protected function checkOppositeCorner()
    {
        if (
            $this->data[0][0] === TicTacToeField::PLAYER &&
            $this->data[2][2] === TicTacToeField::NOT_SET
        ) {
            $this->point = [2, 2];
            return true;
        } elseif (
            $this->data[0][0] === TicTacToeField::NOT_SET &&
            $this->data[2][2] === TicTacToeField::PLAYER
        ) {
            $this->point = [0, 0];
            return true;
        } elseif (
            $this->data[0][2] === TicTacToeField::NOT_SET &&
            $this->data[2][0] === TicTacToeField::PLAYER
        ) {
            $this->point = [0, 2];
            return true;
        } elseif (
            $this->data[0][2] === TicTacToeField::PLAYER &&
            $this->data[2][0] === TicTacToeField::NOT_SET
        ) {
            $this->point = [2, 0];
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function checkEmptyCorner()
    {
        if ($this->data[2][2] === TicTacToeField::NOT_SET) {
            $this->point = [2, 2];
            return true;
        } elseif ($this->data[0][0] === TicTacToeField::NOT_SET) {
            $this->point = [0, 0];
            return true;
        } elseif ($this->data[0][2] === TicTacToeField::NOT_SET) {
            $this->point = [0, 2];
            return true;
        } elseif ($this->data[2][0] === TicTacToeField::NOT_SET) {
            $this->point = [2, 0];
            return true;
        }

        return false;
    }

    /**
     * check for empty cell near border
     *
     * @return bool
     */
    protected function checkEmptySide()
    {
        if ($this->data[0][1] === TicTacToeField::NOT_SET) {
            $this->point = [0, 1];
            return true;
        } elseif ($this->data[1][0] === TicTacToeField::NOT_SET) {
            $this->point = [1, 0];
            return true;
        } elseif ($this->data[2][1] === TicTacToeField::NOT_SET) {
            $this->point = [2, 1];
            return true;
        } elseif ($this->data[1][2] === TicTacToeField::NOT_SET) {
            $this->point = [1, 2];
            return true;
        }

        return false;
    }
}
