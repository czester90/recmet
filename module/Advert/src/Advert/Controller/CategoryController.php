<?php

namespace Advert\Controller;

use Application\Controller\BaseController;

class CategoryController extends BaseController
{
    public function generateCategoryAction()
    {
        if($this->request->isXmlHttpRequest()) {
            $id = $this->request->getPost('id', null);

            $request = $this->em('Advert\Entity\Category')->find($id);
            return $this->jsonResponse($this->getCategoryRepository()->generateCategory($request));
        }
    }

}