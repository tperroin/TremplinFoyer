<?php

namespace Tperroin\TremplinFoyerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AssociationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PartenaireRepository extends EntityRepository
{
    
    public function getActivePartenaires()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder(array('p', 'm'))
                    ->select('p.id, p.nom, p.site, IDENTITY(p.image) as image')
                    ->from('TperroinTremplinFoyerBundle:Partenaire', 'p')
                    ->innerJoin('ApplicationSonataMediaBundle:Media m', $alias = null) 
                    ->where('p.active = :activated')
                    ->andWhere('m.id = IDENTITY(p.image)')
                   ->setParameter('activated', 1);

        $query = $qb->getQuery();

        return $query->getResult();
    }
    
}
