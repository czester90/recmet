<?php

namespace Advert\Repository;


use Application\Repository\BaseRepository;

class CategoryRepository extends BaseRepository {

    public $categoryArr = array();

    public function generateCategory($request)
    {
        $sub = array();
        $id = $request->getId();
        $category = $this->getController()->em('Advert\Entity\Category')->findBy(array('parent_id' => null), array('position' => 'ASC'));

        foreach($category as $cat){
            $this->categoryArr[] = $this->getArrayCategory($cat);

            if($id != null && ($cat->getId() == $id || $cat->getId() == $request->getParentId())){
                $this->categoryArr[count($this->categoryArr)-1]['sub'] = $this->getSubCategory($id, $cat->getId(), $sub);
            }
        }

        return $this->categoryArr;
    }

    public function getSubCategory($id, $requestId, array $enter)
    {
        $subCategory = $this->getController()->em('Advert\Entity\Category')->findBy(array('parent_id' => $requestId), array('position' => 'ASC'));

        foreach($subCategory as $subCat){
            $enter[] = $this->getArrayCategory($subCat);

            if($subCat->getId() == $id){
                $enter[count($enter)-1]['sub'] = $this->getSubCategory($id, $subCat->getId(), array());
            }
        }

        return $enter;
    }

    public function getArrayCategory($cat)
    {
        return array(
            'id' => $cat->getId(),
            'name' => $cat->getName(),
            'url' => $cat->getUrl(),
            'count' => $cat->getAdvertsCount(),
            'child' => $cat->getHaveChild() == 0 ? false : true
        );
    }
} 