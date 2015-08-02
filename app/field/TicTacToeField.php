<?php

namespace Tic_tac_toe\Field;

use Tic_tac_toe\Storage\Storage;

class TicTacToeField implements Field
{
    const KEY = 'field';
    const PLAYER = 1;
    const AI = -1;
    const NOT_SET = 0;
    const CELLS_COUNT = 3;

    protected $filed = [];

    protected $storage = null;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->getData(self::KEY);

        if ($data) {
            $this->filed = $data;
        } else {
            $this->reset();
        }
    }

    /**
     * set cell value
     *
     * @param $x integer X coordinates
     * @param $y integer Y coordinates
     * @param $value
     */
    public function setValue($x, $y, $value)
    {
        $this->filed[$y][$x] = $value;
    }

    /**
     * get cell value
     *
     * @param $x integer X coordinates
     * @param $y integer Y coordinates
     * @return mixed
     */
    public function getValue($x, $y)
    {
        return $this->filed[$y][$x];
    }

    /**
     * get field data
     *
     * @return array
     */
    public function getData()
    {
        return array_merge($this->filed);
    }

    /**
     * reset field
     */
    public function reset()
    {
        $this->filed = [
            [self::NOT_SET, self::NOT_SET, self::NOT_SET],
            [self::NOT_SET, self::NOT_SET, self::NOT_SET],
            [self::NOT_SET, self::NOT_SET, self::NOT_SET]
        ];
    }

    /**
     * save field
     */
    public function save()
    {
        $this->storage->setData('field', $this->filed);
    }
}