<?php

namespace Tperroin\TremplinFoyerBundle\Entity;

use Doctrine\ORM\EntityRepository;


class TremplinRepository extends EntityRepository
{
    public function getActiveTremplins()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder(array('g', 'm'))
                    ->select('t.id, t.titre, t.contenu, t.teaser, IDENTITY(t.image) as image')
                    ->from('TperroinTremplinFoyerBundle:Tremplin', 't')
                    ->innerJoin('ApplicationSonataMediaBundle:Media m', $alias = null) 
                    ->where('t.active = :activated')
                    ->andWhere('m.id = IDENTITY(t.image)')
                   ->setParameter('activated', 1);

        $query = $qb->getQuery();

        return $query->getResult();
    }

   
}