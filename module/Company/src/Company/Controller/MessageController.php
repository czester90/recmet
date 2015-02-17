<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 11/02/15
 * Time: 17:31
 */

namespace Company\Controller;


use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

class MessageController extends BaseController {

    public function indexAction()
    {
        return new ViewModel();
    }
} 