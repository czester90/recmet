<?php

namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompanyController extends AbstractActionController {
  
  public function companiesListAction() {
    return new ViewModel(array(
      
    ));
  }
  
  public function priceTableAction() {
    return new ViewModel();
  }
}
