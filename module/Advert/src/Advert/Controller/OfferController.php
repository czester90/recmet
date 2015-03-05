<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 26/02/15
 * Time: 22:47
 */

namespace Advert\Controller;


use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

class OfferController extends BaseController {

    const PATH = 'advert/advert/offer/';

    public function dashboardAction()
    {

        return $this->getTemplate('dashboard');
    }

    public function receivedAction()
    {
        $this->em('Advert\Entity\Offer')->updateSetSeenOffer($this->getCompanyId());
        $adverts = $this->em('Advert\Entity\Advert')->findBy(array('company_id' => $this->getCompanyId()));
        foreach($adverts as $key => $advert){
            $offers = $this->em('Advert\Entity\Offer')->findBy(array('advert_id' => $advert->getId()));
            if($offers){
                $advert->offers = $offers;
            }else{
                unset($adverts[$key]);
            }
        }
        return $this->getTemplate('received', array(
            'adverts' => $adverts
        ));
    }

    public function shippedAction()
    {
        return $this->getTemplate('shipped');
    }

    private function getTemplate($template, $variable = array())
    {
        $view = new ViewModel($variable);
        $view->setTemplate(self::PATH . $template . '.phtml');
        return $view;
    }
} 