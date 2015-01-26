<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 25/01/15
 * Time: 21:42
 */

namespace Application\Components;

use Zend\View\Model\ViewModel as BaseViewModel;
use Application\Components\Bundle;

class ViewModel extends BaseViewModel {


    public function __construct($variables = null, $options = null)
    {
        parent::__construct();

        if (null === $variables) {
            $variables = new ViewVariables();
        }

        // Initializing the variables container
        $this->setVariables($variables, true);

        if (null !== $options) {
            $this->setOptions($options);
        }
    }
} 