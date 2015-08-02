<?php

namespace Tic_tac_toe;

require_once "field/Field.php";
require_once "field/TicTacToeField.php";
require_once "field/FieldDrawer.php";
require_once "field/JsonFieldDrawer.php";
require_once "field/FieldChecker.php";
require_once "storage/Storage.php";
require_once "storage/SessionStorage.php";
require_once "ai/Ai.php";
require_once "ai/TestAi.php";

use Tic_tac_toe\Field\Field;
use Tic_tac_toe\Field\TicTacToeField;
use Tic_tac_toe\Field\FieldChecker;
use Tic_tac_toe\Field\JsonFieldDrawer;
use Tic_tac_toe\Storage\SessionStorage;
use Tic_tac_toe\Ai\TestAi;

class App
{
    /**
     * run application
     */
    public function run()
    {
        $storage = new SessionStorage();
        $field = new TicTacToeField($storage);
        $fieldDrawer = new JsonFieldDrawer($field);
        $fieldChecker = new FieldChecker();
        $ai = new TestAi();

        switch($_SERVER['REQUEST_METHOD']) {
            case 'POST':

                $field->setValue(
                    $_POST['x'],
                    $_POST['y'],
                    TicTacToeField::PLAYER
                );

                if ($fieldChecker->checkWin($field, TicTacToeField::PLAYER)) {
                    $fieldDrawer->addToResponse('winner', FieldChecker::PLAYER);
                    $field->save();
                    break;
                }

                $point = $ai->makeChoice($field);
                if (is_array($point)) {
                    $field->setValue($point[1], $point[0], TicTacToeField::AI);

                    if ($fieldChecker->checkWin($field, TicTacToeField::AI)) {
                        $fieldDrawer->addToResponse('winner', FieldChecker::AI);
                        $field->save();
                        break;
                    }
                }

                if ($fieldChecker->checkDraw($field, TicTacToeField::NOT_SET)) {
                    $fieldDrawer->addToResponse('winner', FieldChecker::DRAW);
                    $field->save();
                    break;
                }

                $field->save();
                break;
            case 'DELETE':
                $field->reset();
                $field->save();
                break;
        }

        echo $fieldDrawer->getData();
    }
}