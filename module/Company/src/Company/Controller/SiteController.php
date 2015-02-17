<?php

namespace Company\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

class SiteController extends BaseController {

    public function siteAction()
    {
        return new ViewModel();
    }
} 