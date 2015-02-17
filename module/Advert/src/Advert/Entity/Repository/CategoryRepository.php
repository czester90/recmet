<?php
namespace Advert\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {

    public function getCategoryArray($category)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('c')
            ->from('Advert\Entity\Category', 'c');

        if($category->getOriginalId()){
            if($category->getHaveChild()){
                $qb->where('c.parent_id = :id');
            }else{
                $qb->where('c.id = :id');
            }
        }else{
            $qb->where('c.original_id = :id');
        }
        $qb->setParameter('id', $category->getId());

        $query = $qb->getQuery()->getScalarResult();

        return array_column($query, "c_id");
    }
} 