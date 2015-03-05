<?php
namespace Advert\Entity\Repository;

use Advert\Entity\Advert;
use Doctrine\ORM\EntityRepository;

class AdvertRepository extends EntityRepository {

    public function getAdvertByCategory($categoryIds, $offset = 0, $limit = 2, $getQuery)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $cp = $em->createQueryBuilder();

        $qb->select('a')
            ->from('Advert\Entity\Advert', 'a')
            ->where('a.category IN(:categoryIds)')
            ->andWhere('a.active = ' . Advert::ADVERT_ACTIVE)
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
            $qb->where('a.category IN(:categoryIds)')
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

    public function getCompanyAdverts($companyId, $limit = 5)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $now = new \DateTime();

        $qb->select('a')
            ->from('Advert\Entity\Advert', 'a')
            ->where('a.company_id = ' . $companyId)
            ->andWhere('a.created_at + a.days > ' . $now->format('Y-m-d'))
            ->setMaxResults($limit)
            ->orderBy('a.created_at', 'DESC');

        return $qb->getQuery()->getResult();
    }
} 