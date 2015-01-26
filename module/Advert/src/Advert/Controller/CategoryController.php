<?php

namespace Advert\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

class CategoryController extends BaseController
{
    private $categoryArr = array();

    public function generateCategoryAction()
    {
        $result = new ViewModel();
        $result->setTerminal(true);

        if($this->request->isXmlHttpRequest()) {
            $id = $this->request->getPost('id');

            $sub = array();

            $request = $this->em('Advert\Entity\Category')->find($id);
            $category = $this->em('Advert\Entity\Category')->findBy(array('parent_id' => null), array('position' => 'ASC'));

            foreach($category as $cat){
                $this->categoryArr[] = array('id' => $cat->getId(),'name' => $cat->getName());

                if($cat->getId() == $id || $cat->getId() == $request->getParentId()){
                    $this->categoryArr[count($this->categoryArr)-1]['sub'] = $this->getSubCategory($id, $cat->getId(), $sub);
                }
            }

            return $this->jsonResponse($this->categoryArr);
        }
    }

    private function getSubCategory($id, $requestId, array $enter)
    {
        $subCategory = $this->em('Advert\Entity\Category')->findBy(array('parent_id' => $requestId), array('position' => 'ASC'));

        foreach($subCategory as $subCat){
            $enter[] = array('id' => $subCat->getId(),'name' => $subCat->getName());

            if($subCat->getId() == $id){
                $enter[count($enter)-1]['sub'] = $this->getSubCategory($id, $subCat->getId(), array());
            }
        }

        return $enter;
    }

}