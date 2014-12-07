<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 05/11/14
 * Time: 19:55
 */

namespace Admin\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Admin\Database\CategoryDB;

class InstallController extends BaseController {

    private function handleCategory() {
        return new CategoryDB();
    }

    public function indexAction() {
        $_category = $this->handleCategory();

        $request = $this->getRequest();
        if($request->isPost()){
            $_category->install($request->getPost('type'), $this);
        }

        return new ViewModel(array(
            'categoryList' => $_category->setup()
        ));
    }

    public function installAction() {

        return new ViewModel();
    }


} 