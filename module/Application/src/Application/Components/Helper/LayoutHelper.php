<?php

namespace Application\Components\Helper;


class LayoutHelper {

    public $parameters;

    public function setFormArray($array)
    {
        $this->parameters = $array;
    }

    public function getFormParam($name)
    {
        if(count($this->parameters) == 0){
            return '';
        }
        return isset($this->parameters[$name]) ? $this->parameters[$name] : '';
    }

    public function getFormParamEquals($name, $value, $check = 'selected', $default = '')
    {
        if(!isset($this->parameters[$name])){
            return $default;
        }
        return $this->parameters[$name] == $value ? $check : '';
    }
} 