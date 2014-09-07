<?php

namespace Advert\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

class AdvertController extends BaseController {
  
  public function advertListAction() {
    return new ViewModel();
  }
  
  public function addAction() {
    $request = $this->getRequest();
    
    if($request->isPost()){
      
    }
    return new ViewModel();
  }
}
