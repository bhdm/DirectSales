<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Query\ResultSetMapping;

class MessageRepository extends EntityRepository
{

    public function findMessage($operatorId)
    {
        $res = $this->getEntityManager()->createQueryBuilder()
            ->select('m')
            ->from('AppBundle:Message','m')
            ->where( 'm.sender = '.$operatorId.' OR '.'m.receiver= '.$operatorId )
            ->orderBy('m.created','DESC');
        return $res->getQuery()->getResult();
    }

    public function findUser($operatorId){
        $res = $this->getEntityManager()->createQueryBuilder()
            ->select('s.id sid, s.lastName sLastName, s.firstName sFirstName')
            ->addSelect('r.id rid, r.lastName rLastName, r.firstName rFirstName')
            ->from('AppBundle:Message','m')
            ->leftJoin('m.sender','s')
            ->leftJoin('m.receiver','r')
            ->where( 'm.sender = '.$operatorId.' OR '.'m.receiver= '.$operatorId )
            ->orderBy('m.created','DESC');
        $res =  $res->getQuery()->getResult();

        $users = array();
        foreach ($res as $item){
            $users[$item['sid']] = $item['sLastName'].' '.$item['sFirstName'];
            $users[$item['rid']] = $item['rLastName'].' '.$item['rFirstName'];
        }

        return $users;
    }
}