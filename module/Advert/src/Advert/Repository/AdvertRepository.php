<?php

namespace Advert\Repository;

use Advert\Entity\Advert;
use Application\Repository\BaseRepository;
use Company\Entity\Message;
use Library\HttpServiceCaller;
use Advert\Entity\Image;
use WebinoImageThumb\WebinoImageThumb;
use Zend\Filter\File\RenameUpload;

class AdvertRepository extends BaseRepository {

    private $imagePath;
    private $thumbnailsSize = array(array('width' => 160, 'height' => 90, 'folder' => '90x160'));

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
        $advert->setUserId($this->getController()->getUserId());

        $date = new \DateTime();
        $advert->setFinishedAt(
            // Add Days to the date of created advert
            $date->add(new \DateInterval('P' . $this->getController()->post('days') . 'D'))
        );

        $category = $this->getController()->em('Advert\Entity\Category')->find($this->getController()->request->getPost('category_id'));
        $advert->setCategory($category);

        $this->getController()->em('Advert\Entity\Category')->updateCategoryCount($category);

        $this->getController()->em()->persist($advert);
        $this->getController()->em()->flush();

        //$message = new Message();

        return $advert;
    }

    public function saveImage($files, $advert, $advertType, $profileImage = null)
    {
        if(!count($files)) return false;

        $index = 1;

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
                if (isset($profileImage) && $profileImage != null) {
                    if ($index == $profileImage) {
                        $images->setProfile(1);
                    }
                } else {
                    if ($index == 1) {
                        $images->setProfile(1);
                    }
                }
                $images->setType($file['type']);
                $images->setAdvertType($advertType);
                $images->getAdvert_id()->add($advert);
                $this->getController()->em()->persist($images);
                $this->getController()->em()->flush();

                if($advertType == Image::ADVERT_TYPE_PHOTO) {
                    $webImage = new WebinoImageThumb();
                    $thumb = $webImage->create($this->imagePath . $images->getName());
                    foreach($this->thumbnailsSize as $tb){
                        $thumb->adaptiveResize($tb['width'], $tb['height']);
                        $thumb->save($this->imagePath . $tb['folder'] . DIRECTORY_SEPARATOR . $images->getName());
                    }
                }

                $index++;
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