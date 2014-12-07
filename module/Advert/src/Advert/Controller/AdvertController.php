<?php

namespace Advert\Controller;

use Advert\Entity\Observe;
use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Advert\Entity\Advert;
use Advert\Entity\Image;
use Zend\Filter\File\RenameUpload;
use Library\HttpServiceCaller;

class AdvertController extends BaseController
{

    private $category_ids = array();

    public function advertListAction()
    {
        $subcategory = null;
        if ($this->getParam('id')) {
            $this->getParentIdFromCategory($this->getParam('id'));
            $this->category_ids[] = $this->getParam('id');
            $subcategory = $this->em('Advert\Entity\Category')->findBy(array('parent_id' => $this->category_ids));
        }
        $category = $this->em('Advert\Entity\Category')->findBy(array('parent_id' => null), array('position' => 'ASC'));

        return new ViewModel(array(
            'adverts' => $this->em('Advert\Entity\Advert')->findBy(array(), array('id' => 'ASC')),
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

            $files = $this->request->getFiles();

            $advert = new Advert();
            $advert->setActive(1);
            $advert->setAmount($this->request->getPost('amount'));
            $advert->setDays(20); //TODO
            $advert->setDescription($this->request->getPost('description'));
            $advert->setName($this->request->getPost('name'));
            $advert->setAdvertType($this->request->getPost('advert_type'));
            $advert->setAmountType($this->request->getPost('amount_type'));
            $advert->setPieces($this->request->getPost('pieces'));
            $advert->setUrl(HttpServiceCaller::toAscii($this->request->getPost('name')));
            $advert->setUser_id($this->user()->getIdentity()->getId());
            $category = $this->em('Advert\Entity\Category')->find($this->request->getPost('category'));
            $advert->setCategory_id($category->getId());
            $this->em()->persist($advert);
            $this->em()->flush();

            $path = getcwd() . Image::IMAGE_PATH . DIRECTORY_SEPARATOR . $this->user()->getIdentity()->getId();
            if (!is_dir($path)) {
                mkdir($path);
            }

            foreach ($files['images'] as $file) {
                $filter = new RenameUpload(array(
                    "target" => $path . DIRECTORY_SEPARATOR . date('YmdHis') . "." . pathinfo($file['name'], PATHINFO_EXTENSION),
                    "randomize" => true,
                ));
                $FileSaved = $filter->filter($file);
                $filePart = explode("/", $FileSaved['tmp_name']);

                $images = new Image();
                $images->setUser_id($this->user()->getIdentity()->getId());
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

    public function viewAction()
    {
        if($this->isUser()) return $this->isUser();
        $advertId = $this->getParam('id');
        $companyId = $this->user()->getIdentity()->getCompanyId();

        return new ViewModel(array(
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
