<?php
namespace Advert\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class AdvertRepository extends EntityRepository {

    public function getAdvertByCategory($categoryIds, $offset = 0, $limit = 2, $getQuery)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $cp = $em->createQueryBuilder();

        $qb->select('a')
            ->from('Advert\Entity\Advert', 'a')
            ->where('a.category_id IN(:categoryIds)')
            ->setParameter('categoryIds', $categoryIds);

        foreach($getQuery as $key => $value) {
            if($value != ''){
                switch($key){
                    case 'amount_min':
                        $qb->andWhere('a.amount > :' . $key)->setParameter($key, $value);
                        break;
                    case 'amount_max':
                        $qb->andWhere('a.amount < :' . $key)->setParameter($key, $value);
                        break;
                    case 'advert_type':
                        $qb->andWhere('a.advert_type = :' . $key)->setParameter($key, $value);
                        break;
                    case 'pieces_min':
                        $qb->andWhere('a.pieces > :' . $key)->setParameter($key, $value);
                        break;
                    case 'pieces_max':
                        $qb->andWhere('a.pieces < :' . $key)->setParameter($key, $value);
                        break;
                    case 'city':
                        $cp->select(array('c.id'))
                            ->from('Company\Entity\Company', 'c')
                            ->where('c.city = :' . $key)
                            ->setParameter($key, $value);
                        $qb->andWhere('a.company_id IN(:companyId)')->setParameter('companyId', $cp->getQuery()->getResult());
                        break;
                }
            }
        }

        if(isset($getQuery['sort'])){
            $sort = explode('_', $getQuery['sort']);
            $name = count($sort) > 3 ? $sort[1].'_'.$sort[2] : $sort[1];
            $qb->orderBy('a.'.$name, strtoupper($sort[count($sort)-1]));
        }else{
            $qb->orderBy('a.created_at');
        }

        $qb->setMaxResults($limit)
            ->setFirstResult($offset);

        $query = $qb->getQuery()->getResult();

        return $query;
    }

    public function getCountAdvertsByCategory($categoryIds, $getQuery)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('COUNT(a)')
            ->from('Advert\Entity\Advert', 'a');

        if($categoryIds != null){
            $qb->where('a.category_id IN(:categoryIds)')
                ->setParameter('categoryIds', $categoryIds);
        }

        foreach($getQuery as $key => $value) {
            if($value != ''){
                switch($key){
                    case 'amount_min':
                        $qb->andWhere('a.amount > :' . $key)->setParameter($key, $value);
                        break;
                    case 'amount_max':
                        $qb->andWhere('a.amount < :' . $key)->setParameter($key, $value);
                        break;
                    case 'advert_type':
                        $qb->andWhere('a.advert_type = :' . $key)->setParameter($key, $value);
                        break;
                    case 'pieces_min':
                        $qb->andWhere('a.pieces > :' . $key)->setParameter($key, $value);
                        break;
                    case 'pieces_max':
                        $qb->andWhere('a.pieces < :' . $key)->setParameter($key, $value);
                        break;
                }
            }
        }

        $query = $qb->getQuery()->getSingleScalarResult();

        return $query;
    }
} 