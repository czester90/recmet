<?php

namespace Advert\Controller;

use Advert\Entity\Observe;
use Advert\Entity\Offer;
use Application\Controller\BaseController;
use Application\Components\ViewModel;
use Zend\View\Model\JsonModel;
use Advert\Entity\Advert;
use Advert\Entity\Image;
use Zend\Session\Container;
use Advert\Repository\AdvertRepository;

use Advert\Components\AddOffer;

class AdvertController extends BaseController
{

    public function advertListAction()
    {
        $resultPage = 7;
        $page = $this->getParam('page', 1);
        $currentCategoryId = $this->getParam('id', null);
        $offset = max(0, ($page - 1) * $resultPage);

        $currentCategory = null;
        $categoryIds = null;
        $subcategory = null;

        if ($currentCategoryId) {
            $currentCategory = $this->em('Advert\Entity\Category')->find($currentCategoryId);
            $categoryIds = $this->em('Advert\Entity\Category')->getCategoryArray($currentCategory);
            $advertList = $this->em('Advert\Entity\Advert')->getAdvertByCategory($categoryIds, $offset, $resultPage, $this->request->getQuery());
        }else{
            $advertList = $this->em('Advert\Entity\Advert')->findBy(array('active' => Advert::ADVERT_ACTIVE), array('created_at' => 'DESC'), $resultPage, $offset);
        }
        $count = $this->em('Advert\Entity\Advert')->getCountAdvertsByCategory($categoryIds, $this->request->getQuery());

        return new ViewModel(array(
            'adverts' => $advertList,
            'currentPage' => $page,
            'currentCategory' => $currentCategory,
            'resultPage' => $resultPage,
            'resultCount' => $count,
            'pages' => ceil($count/$resultPage),
            'category' => $this->getCategoryRepository()->generateCategory($currentCategory),
            'query' => $this->request->getQuery() ? $this->request->getQuery()->toArray() : array()
        ));
    }

    public function addAction()
    {
        $bundle = $this->em('Company\Entity\BundlePayments')->findOneBy(array('company_id' => $this->getCompanyId()));
        if ($this->request->isPost()) {
            $this->getAdvertRepository()->setDirectoryPath();
            $advert = $this->getAdvertRepository()->saveAdvert();
            $this->getAdvertRepository()->saveImage($this->files('photo'), $advert, Image::ADVERT_TYPE_PHOTO, $this->request->getPost('profile-image'));
            $this->getAdvertRepository()->saveImage($this->files('attach'), $advert, Image::ADVERT_TYPE_ATTACH);

            if(isset($preview)){
                $view = new ViewModel(array('variable' => $advert));
                $view->setTemplate('advert/advert/preview.phtml');
                return $view;
            }

            if($bundle->getAdvertsToUse()){
                $bundle->setAdvertsToUse($bundle->getAdvertsToUse()-1);
                $this->em()->persist($bundle);
                $this->em()->flush();
            }

            $sessionAdvert = new Container('advert');
            $sessionAdvert->alert = array('alert' => 'success', 'message' => 'Ogłoszenie zostało dodane.');
            return $this->redirect()->toRoute('advert/dashboard');
        }

        $category = $this->em('Advert\Entity\Category')->findBy(array('parent_id' => null), array('position' => 'ASC'));

        return new ViewModel(array(
            'categories' => $category,
            'action' => $this->params('action'),
            'bundle' => $bundle
        ));
    }

    public function getAdvertAction()
    {
        $id = $this->getParam('id');
        if ($this->request->isPost()) {
            $advert = $this->em('Advert\Entity\Advert')->find($this->request->getPost('id'));
        }
        return $this->jsonResponse($advert->toArray());
    }

    public function deleteAction()
    {
        $advert = $this->em('Advert\Entity\Advert')->findOneBy(array('id' => $this->getParam('id'), 'company_id' => $this->getCompanyId()));
        $advert->getImages()->clear();
        $this->em()->remove($advert);
        $this->em()->flush();

        $this->redirect()->toRoute('advert/dashboard');
    }

    public function editAction()
    {
        $advert = $this->em('Advert\Entity\Advert')->findOneBy(array('id' => $this->getParam('id'), 'company_id' => $this->getCompanyId()));

        return new ViewModel(array(
            'advert' => $advert
        ));
    }

    public function dashboardAction()
    {
        $alert = array();

        $sessionAdvert = new Container('advert');
        if (isset($sessionAdvert->alert)) {
            $alert = $sessionAdvert->alert;
        }

        unset($sessionAdvert->alert);

        $adverts = $this->em('Advert\Entity\Advert')->findBy(array('company_id' => $this->getCompanyId()));
        return new ViewModel(array(
            'adverts' => $adverts,
            'alert' => $alert,
            'action' => $this->params('action'),
            'images' => $this->em('Advert\Entity\Image'),
            'company' => $this->em('Company\Entity\Company'),
            'category' => $this->em('Advert\Entity\Category')
        ));
    }

    public function offerDeleteAction()
    {
        $result = new ViewModel();
        $result->setTerminal(true);

        $offerId = $this->getParam('id');

        $offer = $this->em('Advert\Entity\Offer')->find($offerId);
        $advert = $this->em('Advert\Entity\Advert')->find($offer->getAdvertId());
        $this->em()->remove($offer);
        $this->em()->flush();

        $this->redirect()->toRoute('advert/view', array('id' => $advert->getId(), 'url' => $advert->getUrl()));
    }

    public function viewAction()
    {
        if($this->isUser()) return $this->isUser();
        $advertId = $this->getParam('id');
        $companyId = $this->user()->getIdentity()->getCompanyId();

        $offer = new AddOffer();

        $advert = $this->em('Advert\Entity\Advert')->find($advertId);
        $companyFromAdvert = $this->em('Company\Entity\Company')->find($advert->getCompanyId());
        $offer->setDBCompany($companyFromAdvert);

        if($this->request->isPost()){
            if((boolean)$this->request->getPost('offer') && $this->request->getPost('amount') != null){
                if($advert->getActive() != Advert::ADVERT_FINISH){
                    $addOffer = new Offer();
                    $addOffer->setAmount($this->request->getPost('amount'));
                    $addOffer->setAdvertId($advert->getId());
                    $addOffer->setCompanyId($advert->getCompanyId());
                    $addOffer->setSendCompanyId($companyId);
                    $description = $this->request->getPost('description');
                    $addOffer->setDescription(isset($description) ? $description : '');
                    $addOffer->setType($advert->isKupTeraz() ? Offer::TYPE_BUY : Offer::TYPE_SENT);
                    $this->em()->persist($addOffer);
                    $this->em()->flush();

                    $offer->setIsSent(true);
                    if($advert->isKupTeraz()){
                        $advert->setActive(Advert::ADVERT_FINISH);
                        $this->em()->persist($advert);
                        $this->em()->flush();
                        $offer->setText('<div class="alert alert-success text-center">Ogłoszenie Zakończone. Złożyłeś najwyższą ofertę.</div>');
                    }else{
                        $offer->setText('<div class="alert alert-success text-center">Oferta została wysłana. Wyślemy wiadomość kiedy Firma oferta zostanie rozpatrzona.</div>');
                    }
                }
            }else{
                $offer->setOffer($this->request->getPost('amount'));
            }
        }

        if($advert->getUser_id() != $this->user()->getIdentity()->getId()){
            $advert->setVisits($advert->getVisits()+1);
            $this->em()->persist($advert);
            $this->em()->flush();
        }

        $query = $this->em()->createQuery("SELECT count(m) FROM Advert\Entity\Offer m WHERE m.advert_id =". $advert->getId());
        $count = $query->getSingleResult();

        $yourOffer = $this->em('Advert\Entity\Offer')->findOneBy(array('advert_id' => $advert->getId(), 'company_id' => $companyId));

        if($yourOffer){
            $offer->setIsSent(true);
            if($advert->isKupTeraz() && $count[1] > 0){
                $offer->setText('<div class="alert alert-success text-center">Ogłoszenie Zakończone. Złożyłeś najwyższą ofertę.</div>');
            }else{
                $offer->setText('<div class="alert alert-info text-center">Złożyłeś już ofertę do tego ogłoszenia.</div>');
            }
        }else{
            if($advert->isKupTeraz()) {
                $offer->setText('<div class="alert alert-danger text-center">Ogłoszenie Zakończone.</div>');
            }
        }

        return new ViewModel(array(
            'offer' => $offer,
            'offerYour' => $yourOffer,
            'offerCount' => $count[1],
            'companyFromAdvert' => $companyFromAdvert,
            'categories' => $this->em('Advert\Entity\Category')->findBy(array('parent_id' => null), array('position' => 'ASC')),
            'advert' => $advert,
            'observe' => $this->em('Advert\Entity\Observe')->findOneBy(array('advert_id' => $advertId, 'company_id' => $companyId))
        ));
    }

    public function offerAction()
    {
        return new ViewModel(array());
    }

    public function magazineAction()
    {

    }

    public function transationsAction()
    {

    }

    public function observeListAction()
    {
        if($this->isUser()) return $this->isUser();

        $companyId = $this->user()->getIdentity()->getCompanyId();
        $observes = $this->em('Advert\Entity\Observe')->findBy(array('company_id' => $companyId));

        $observes_arr = array();
        foreach($observes as $observe){
            $observes_arr[] = $observe->getAdvertId();
        }
        $adverts = $this->em('Advert\Entity\Advert')->findBy(array('id' => $observes_arr));

        return new ViewModel(array(
            'adverts' => $adverts,
            'action' => $this->params('action'),
            'images' => $this->em('Advert\Entity\Image'),
            'company' => $this->em('Company\Entity\Company'),
            'category' => $this->em('Advert\Entity\Category')
        ));
    }

    public function observeAction()
    {
        if($this->request->isXmlHttpRequest()) {
            $advertId = $this->request->getPost('id');
            $companyId = $this->user()->getIdentity()->getCompanyId();

            $observe = $this->em('Advert\Entity\Observe')->findOneBy(array('advert_id' => $advertId, 'company_id' => $companyId));

            if($observe){
                $this->em()->remove($observe);
                $this->em()->flush();

                return $this->jsonResponse(array('html' => 'Obserwuj Ogłoszenie'));
            }

            $observe = new Observe();
            $observe->setAdvertId($this->request->getPost('id'));
            $observe->setCompanyId($this->user()->getIdentity()->getCompanyId());
            $this->em()->persist($observe);
            $this->em()->flush();

            return $this->jsonResponse(array('html' => 'Zakończ Obserwowanie'));
        }
    }
}
