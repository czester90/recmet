<?php

namespace Advert\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Advert\Entity\Advert;
use Advert\Entity\Image;
use Zend\Filter\File\RenameUpload;

class AdvertController extends BaseController {
  
  public function advertListAction() {
    return new ViewModel();
  }
  
  public function addAction() {
    $request = $this->getRequest();
    if($request->isPost()){
      
      $files = $request->getFiles();
      
      $advert = new Advert();
      $advert->setActive(1);
      $advert->setAmount($request->getPost('amount'));
      $advert->setDays(20);//TODO
      $advert->setDescription($request->getPost('description'));
      $advert->setName($request->getPost('name'));
      $advert->setAdvertType($request->getPost('advert_type'));
      $advert->setAmountType($request->getPost('amount_type'));
      $advert->setPieces($request->getPost('pieces'));
      $advert->setUrl('test-test');
      $advert->setUser_id($this->user()->getIdentity()->getId());
      $advert->setCategory_id(1);
      $this->em()->persist($advert);
      $this->em()->flush();
      
      $path = getcwd().Image::IMAGE_PATH.DIRECTORY_SEPARATOR.$this->user()->getIdentity()->getId();
      if(!is_dir($path)){
        mkdir($path);
      }

      foreach($files['images'] as $file) {
        $filter = new RenameUpload(array(
            "target"    => $path.DIRECTORY_SEPARATOR.date('YmdHis').".".pathinfo($file['name'], PATHINFO_EXTENSION),
            "randomize" => true,
        ));
        $FileSaved = $filter->filter($file);
        $filePart = explode("/", $FileSaved['tmp_name']);
        
        $images = new Image();
        $images->setUser_id($this->user()->getIdentity()->getId());
        $images->setName($filePart[count($filePart)-1]);
        $images->setType($file['type']);
        $images->getAdvert_id()->add($advert);
        $this->em()->persist($images);
        $this->em()->flush();
      }
    }
    return new ViewModel(array(
      'action' => $this->params('action')
    ));
  }
  
  public function deleteAction() {
    $id = $this->params()->fromRoute('id');
    $advert = $this->em('Advert\Entity\Advert')->findOneBy(array('id' => $id, 'user_id' => $this->user()->getIdentity()->getId()));
    $advert->getImages()->clear();
    $this->em()->remove($advert);
    $this->em()->flush();
    
    $this->redirect()->toRoute('advert/dashboard');
  }
  
  public function editAction() {
    $id = $this->params()->fromRoute('id');
    $advert = $this->em('Advert\Entity\Advert')->findOneBy(array('id' => $id, 'user_id' => $this->user()->getIdentity()->getId()));

    return new ViewModel(array(
      'advert' => $advert
    ));
  }
  
  public function dashboardAction() {
      $adverts = $this->em('Advert\Entity\Advert')->findBy(array('user_id' => $this->user()->getIdentity()->getId()));
      return new ViewModel(array(
          'adverts' => $adverts,
          'action' => $this->params('action'),
          'images' => $this->em('Advert\Entity\Image'),
          'company' => $this->em('Company\Entity\Company'),
          'category' => $this->em('Advert\Entity\Category')
      ));
  }
  
  public function viewAction() {
    $id = $this->params()->fromRoute('id');
    
    return new ViewModel(array(
      'advert' => $this->em('Advert\Entity\Advert')->find($id)
    ));
  }

    public function offerAction() {
        return new ViewModel(array(

        ));
    }

    public function magazineAction() {

    }

    public function transationsAction() {

    }
}
