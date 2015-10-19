<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 24/09/15
 * Time: 22:16
 */

namespace Application\Components\Helper;


use Zend\Validator\NotEmpty;

class Validators {

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function notEmpty()
    {
        $validator = new NotEmpty();
        return $validator->isValid($this->value);
    }
} 