<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Query\ResultSetMapping;

class ClientRepository extends EntityRepository
{

    public function getClients($projectId, $userId)
    {
        $res = $this->getEntityManager()->createQueryBuilder()
            ->select('c')
            ->from('AppBundle:Client','c')
            ->leftJoin('u.projects', 'p')
            ->leftJoin('c.user', 'u')
            ->where('( u.parent = :userId  OR u.id = :userId ) AND p.id = :projectId')
            ->orderBy('u.created','DESC')
            ->setParameter('userId',$userId)
            ->setParameter('projectId',$projectId);
        return $res->getQuery()->getResult();
    }

}