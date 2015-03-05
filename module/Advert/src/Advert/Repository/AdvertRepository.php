<?php

namespace Advert\Repository;

use Advert\Entity\Advert;
use Library\HttpServiceCaller;
use Advert\Entity\Image;
use WebinoImageThumb\WebinoImageThumb;
use Zend\Filter\File\RenameUpload;

class AdvertRepository {

    private $controller;
    private $imagePath;
    private $thumbnailsSize = array(array('width' => 160, 'height' => 90, 'folder' => '90x160'));

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    public function saveAdvert()
    {
        $preview = $this->getController()->post('preview');
        $advert = new Advert();
        $advert->setActive(isset($preview) ? Advert::ADVERT_PREVIEW : Advert::ADVERT_ACTIVE);
        $advert->setAmount($this->getController()->post('amount'));
        $advert->setCompanyId($this->getController()->getCompanyId());
        $advert->setDays($this->getController()->post('days'));
        $advert->setDescription($this->getController()->post('description'));
        $advert->setName($this->getController()->post('name'));
        $advert->setAdvertType($this->getController()->post('advert_type'));
        $advert->setAmountType($this->getController()->post('amount_type'));
        $advert->setPieces($this->getController()->post('pieces'));
        $advert->setLocation($this->getController()->post('location'));
        $advert->setSellOption($this->getController()->post('sell_option'));
        $advert->setTransport($this->getController()->post('transport'));
        $advert->setTransportAmount($this->getController()->post('transport_amount'));
        $advert->setUrl(HttpServiceCaller::toAscii($this->getController()->post('name')));
        $advert->setUser_id($this->getController()->getUserId());

        $category = $this->getController()->em('Advert\Entity\Category')->find($this->getController()->request->getPost('category_id'));
        $advert->setCategory($category);

        $this->getController()->em()->persist($advert);
        $this->getController()->em()->flush();

        return $advert;
    }

    public function saveImage($files, $advert, $advertType)
    {
        if(!count($files)) return false;

        foreach ($files as $file) {
            if(isset($file['name']) && $file['name'] != ''){
                $filter = new RenameUpload(array(
                    "target" => $this->imagePath . date('YmdHis') . "." . pathinfo($file['name'], PATHINFO_EXTENSION),
                    "randomize" => true,
                ));

                $fileSaved = $filter->filter($file);
                $filePart = explode("/", $fileSaved['tmp_name']);

                $images = new Image();
                $images->setUser_id($this->getController()->getUserId());
                $images->setName($filePart[count($filePart) - 1]);
                $images->setType($file['type']);
                $images->setAdvertType($advertType);
                $images->getAdvert_id()->add($advert);
                $this->getController()->em()->persist($images);
                $this->getController()->em()->flush();

                $webImage = new WebinoImageThumb();
                $thumb = $webImage->create($this->imagePath . $images->getName());
                foreach($this->thumbnailsSize as $tb){
                    $thumb->adaptiveResize($tb['width'], $tb['height']);
                    $thumb->save($this->imagePath . $tb['folder'] . DIRECTORY_SEPARATOR . $images->getName());
                }
            }
        }
    }

    public function setDirectoryPath()
    {
        $this->imagePath  = getcwd() . Image::IMAGE_PATH . $this->getController()->getCompanyId(). DIRECTORY_SEPARATOR;
        if (!is_dir($this->imagePath)) {
            mkdir($this->imagePath, 0777, true);
        }
        //thumbnails 90x160
        foreach($this->thumbnailsSize as $tb){
            if (!is_dir($this->imagePath . $tb['folder'])) {
                mkdir($this->imagePath . $tb['folder'], 0777, true);
            }
        }
    }
} 