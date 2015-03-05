<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 19/02/15
 * Time: 17:40
 */

namespace Application\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

class SystemController extends BaseController {

    public function indexAction()
    {

    }

    public function setLanguageAction()
    {
        $result = new ViewModel();
        $result->setTerminal(true);

        if($this->request->isXmlHttpRequest()) {
            $lang = $this->request->getPost('lang');
            if($this->user()->hasIdentity()){
                try{
                $user = $this->em('User\Entity\User')->find($this->getUserId());
                $user->setLocale($lang);
                $this->em()->persist($user);
                $this->em()->flush();
                }catch(\Exception $e) {
                    return $this->jsonResponse(array('status' => false));
                }
            }else{
                setcookie('lang', $lang, time() + (86400 * 30 * 30), '/');
            }

            return $this->jsonResponse(array('status' => true));
        }

        return $result;
    }
} 