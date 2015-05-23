<?php
namespace Advert\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {

    public function getCategoryArray($category)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('c')->from('Advert\Entity\Category', 'c');

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

    public function updateCategoryCount($category)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->update('Advert\Entity\Category', 'c')
            ->set('c.adverts_count', 'c.adverts_count + 1')
            ->where('c.id = :id')
            ->orWhere('c.id = :original_id')
            ->orWhere('c.id = :parent_id')
            ->setParameter('id', $category->getId())
            ->setParameter('original_id', $category->getOriginalId())
            ->setParameter('parent_id', $category->getParentId())
            ->getQuery()
            ->getResult();
    }
} 