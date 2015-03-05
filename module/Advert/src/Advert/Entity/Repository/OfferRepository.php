<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 26/02/15
 * Time: 23:58
 */

namespace Advert\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class OfferRepository extends EntityRepository {

    public function getOfferCountNotSeen($companyId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('COUNT(o)')
            ->from('Advert\Entity\Offer', 'o')
            ->where('o.company_id = ' . $companyId)
            ->andWhere('o.seen = 0');

        $query = $qb->getQuery()->getSingleScalarResult();

        return $query;
    }

    public function updateSetSeenOffer($companyId)
    {
        $em = $this->getEntityManager();

        $q = $em->createQuery('update Advert\Entity\Offer o set o.seen = 1 where o.company_id = ' . $companyId);
        $q->execute();
    }
} 