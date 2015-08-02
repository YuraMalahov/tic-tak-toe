<?php

namespace Tic_tac_toe\Field;

class JsonFieldDrawer implements FieldDrawer
{
    protected $field;
    protected $data = [];

    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    /**
     * add additional data to response
     *
     * @param $key string
     * @param $value mixed
     */
    public function addToResponse($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * get data in json format
     *
     * @return string
     */
    public function getData()
    {
        $output = ['data' => $this->field->getData()];

        if (!empty($this->data)) {
            $output = array_merge($output, $this->data);
        }

        return json_encode($output);
    }
}