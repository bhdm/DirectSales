<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Query\ResultSetMapping;

class EventRepository extends EntityRepository
{

    public function pollStats($eventId)
    {
        $res = $this->getEntityManager()->createQueryBuilder()
            ->select('q.title qTitle, a.title aTitle, COUNT(a.title) countAnswer')
            ->from('AppBundle:Event','e')
            ->leftJoin('e.questions','q')
            ->leftJoin('q.answers','a')
            ->where( 'e.id ='.$eventId )
            ->groupBy('a.id')
            ->orderBy('q.title','ASC')
            ->addOrderBy('q.title','ASC');
        return $res->getQuery()->getResult();
    }

}