<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Query\ResultSetMapping;

class AddressRepository extends EntityRepository
{

    public function getClients($userId)
    {
        $res = $this->getEntityManager()->createQueryBuilder()
            ->select('p.title projectTitle, a.title AdrsTitle, COUNT(c.id) countClient')
            ->from('AppBundle:Address','a')
            ->leftJoin('a.clients', 'c')
            ->leftJoin('c.user', 'u')
            ->leftJoin('a.project', 'p')
            ->where('u.id = :userID')
            ->groupBy('AdrsTitle')
            ->orderBy('projectTitle','ASC')
            ->setParameter('userId',$userId);
        $result = $res->getQuery()->getResult();
        $array = array();
        foreach ($result as $val) {
            $array[$val['projectTitle']] = array('adrs' => $val['AdrsTitle'], 'count' => $val['countClient']);
        }
        return $array;
    }

}