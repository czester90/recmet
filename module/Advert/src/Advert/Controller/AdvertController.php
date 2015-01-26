<?php

namespace Advert\Controller;

use Advert\Entity\Observe;
use Advert\Entity\Offer;
use Application\Components\Image\ImageThumb;
use Application\Controller\BaseController;
use Application\Components\ViewModel;
use Advert\Entity\Advert;
use Advert\Entity\Image;
use Zend\Filter\File\RenameUpload;
use Library\HttpServiceCaller;

use Advert\Components\AddOffer;

class AdvertController extends BaseController
{

    private $category_ids = array();

    public function advertListAction()
    {
        $resultPage = 3;
        $page = $this->getParam('page');
        $offset = max(0, ($page - 1) * $resultPage);

        $subcategory = null;
        if ($this->getParam('id')) {
            $this->getParentIdFromCategory($this->getParam('id'));
            $this->category_ids[] = $this->getParam('id');
            $subcategory = $this->em('Advert\Entity\Category')->findBy(array('parent_id' => $this->category_ids));
        }
        $category = $this->em('Advert\Entity\Category')->findBy(array('parent_id' => null), array('position' => 'ASC'));

        $query = $this->em()->createQuery("SELECT count(m) FROM Advert\Entity\Advert m");
        $count = $query->getSingleResult();

        return new ViewModel(array(
            'adverts' => $this->em('Advert\Entity\Advert')->findBy(array(), array('id' => 'DESC'), $resultPage, $offset),
            'currentPage' => $page,
            'resultPage' => $resultPage,
            'resultCount' => $count[1],
            'pages' => ceil($count[1]/$resultPage),
            'categories' => $category,
            'subcategories' => $subcategory,
            'category_ids' => $this->category_ids
        ));
    }

    public function advertListCompanyAction()
    {

    }

    private function getParentIdFromCategory($id)
    {
        $category = $this->em('Advert\Entity\Category')->findOneBy(array('id' => $id), array('position' => 'ASC'));
        if ($category->getParentId() !== null) {
            $this->category_ids[] = $category->getParentId();
            $this->getParentIdFromCategory($category->getParentId());
        }
    }

    public function addAction()
    {

        if ($this->request->isPost()) {

            var_dump($this->request->getFiles());
            exit();
            $advert = $this->addAdvert();


            $path = getcwd() . Image::IMAGE_PATH . DIRECTORY_SEPARATOR . $this->getUserId();
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            foreach ($this->files['images'] as $file) {
                $filter = new RenameUpload(array(
                    "target" => $path . DIRECTORY_SEPARATOR . date('YmdHis') . "." . pathinfo($file['name'], PATHINFO_EXTENSION),
                    "randomize" => true,
                ));

                $img = new ImageThumb();

                $FileSaved = $filter->filter($file);
                $filePart = explode("/", $FileSaved['tmp_name']);

                $images = new Image();
                $images->setUser_id($this->getUserId());
                $images->setName($filePart[count($filePart) - 1]);
                $images->setType($file['type']);
                $images->getAdvert_id()->add($advert);
                $this->em()->persist($images);
                $this->em()->flush();
            }

            $this->session->alert = array('alert' => 'success', 'message' => 'Ogłoszenie zostało dodane.');
            return $this->redirect()->toRoute('advert/dashboard');
        }

        $category = $this->em('Advert\Entity\Category')->findBy(array('parent_id' => null), array('position' => 'ASC'));

        return new ViewModel(array(
            'categories' => $category,
            'action' => $this->params('action'),
        ));
    }

    private function addAdvert()
    {
        $advert = new Advert();
        $advert->setActive(Advert::ADVERT_ACTIVE);
        $advert->setAmount($this->post('amount'));
        $advert->setDays($this->post('days'));
        $advert->setDescription($this->post('description'));
        $advert->setName($this->post('name'));
        $advert->setAdvertType($this->post('advert_type'));
        $advert->setAmountType($this->post('amount_type'));
        $advert->setPieces($this->post('pieces'));
        $advert->setUrl(HttpServiceCaller::toAscii($this->post('name')));
        $advert->setUser_id($this->getUserId());

        $category = $this->em('Advert\Entity\Category')->find($this->request->getPost('category'));
        $advert->setCategory_id($category->getId());

        $this->em()->persist($advert);
        $this->em()->flush();

        return $advert;
    }

    private function addImage()
    {

    }

    public function deleteAction()
    {
        $advert = $this->em('Advert\Entity\Advert')->findOneBy(array('id' => $this->getParam('id'), 'user_id' => $this->user()->getIdentity()->getId()));
        $advert->getImages()->clear();
        $this->em()->remove($advert);
        $this->em()->flush();

        $this->redirect()->toRoute('advert/dashboard');
    }

    public function editAction()
    {
        $advert = $this->em('Advert\Entity\Advert')->findOneBy(array('id' => $this->getParam('id'), 'user_id' => $this->user()->getIdentity()->getId()));

        return new ViewModel(array(
            'advert' => $advert
        ));
    }

    public function dashboardAction()
    {
        $alert = array();
        $param_alert = $this->session->alert;

        if (isset($param_alert)) {
            $alert = $param_alert;
        }

        unset($this->session->alert);

        $adverts = $this->em('Advert\Entity\Advert')->findBy(array('user_id' => $this->user()->getIdentity()->getId()));
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

        $offer = new AddOffer($this->user()->getIdentity());
        $offer->setDBCompany($this->em('Company\Entity\Company'));

        $advert = $this->em('Advert\Entity\Advert')->find($advertId);
        $user = $this->em('User\Entity\User')->find($advert->getUser_id());
        $companyFromAdvert = $this->em('Company\Entity\Company')->find($user->getCompany_id());

        if($this->request->isPost()){
            if((boolean)$this->request->getPost('offer') && $this->request->getPost('amount') != null){
                $addOffer = new Offer();
                $addOffer->setAmount($this->request->getPost('amount'));
                $addOffer->setAdvertId($advert->getId());
                $addOffer->setCompanyId($companyId);
                $addOffer->setDescription($this->request->getPost('description'));
                $addOffer->setType(Offer::TYPE_SENT);
                $this->em()->persist($addOffer);
                $this->em()->flush();

                $offer->setIsSent(true);
                $offer->setText('<div class="alert alert-success text-center">Oferta została wysłana. Wyślemy wiadomość kiedy Firma oferta zostanie rozpatrzona.</div>');
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
            $offer->setText('<div class="alert alert-info text-center">Złożyłeś już ofertę do tego ogłoszenia.</div>');
        }

        return new ViewModel(array(
            'offer' => $offer,
            'offerYour' => $yourOffer,
            'offerCount' => $count[1],
            'companyFromAdvert' => $companyFromAdvert,
            'categories' => $this->em('Advert\Entity\Category')->findBy(array('parent_id' => null), array('position' => 'ASC')),
            'advert' => $this->em('Advert\Entity\Advert')->find($advertId),
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
