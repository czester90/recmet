<?php

namespace Advert\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdvertController extends AbstractActionController {
  
  public function advertListAction() {
    return new ViewModel();
  }
}
