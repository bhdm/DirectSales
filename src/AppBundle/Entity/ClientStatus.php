<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Статусы
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ClientStatus extends BaseEntity{

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="status")
     */
    protected $clients;

    /**
     * @ORM\OneToMany(targetEntity="StatusLog", mappedBy="status")
     */
    protected $statusLog;


    public function __toString(){
        return $this->title;
    }

    public function __construct(){
        $this->clients = new ArrayCollection();
        $this->statusLog = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * @return mixed
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param mixed $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    /**
     * @return mixed
     */
    public function getStatusLog()
    {
        return $this->statusLog;
    }

    /**
     * @param mixed $statusLog
     */
    public function setStatusLog($statusLog)
    {
        $this->statusLog = $statusLog;
    }


}
