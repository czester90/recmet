<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 21/01/15
 * Time: 19:17
 */

namespace Application\Components\Image;


use Application\Controller\BaseController;

class ImageThumb extends BaseController {

    public function __construct()
    {
        return $this->getServiceLocator()->get('WebinoImageThumb');
    }
} 