<?php

namespace Tperroin\TremplinFoyerBundle\Entity;

use Doctrine\ORM\EntityRepository;


class GroupeRepository extends EntityRepository
{
    public function getActiveGroupes()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder(array('g', 'm'))
                    ->select('g.id, g.titre, g.contenu, g.teaser, IDENTITY(g.image) as image')
                    ->from('TperroinTremplinFoyerBundle:Groupe', 'g')
                    ->innerJoin('ApplicationSonataMediaBundle:Media m', $alias = null) 
                    ->where('g.active = :activated')
                    ->andWhere('m.id = IDENTITY(g.image)')
                   ->setParameter('activated', 1);

        $query = $qb->getQuery();

        return $query->getResult();
    }

   
}