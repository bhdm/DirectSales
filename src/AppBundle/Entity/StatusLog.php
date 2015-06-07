<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.06.15
 * Time: 22:54
 * Логирование статусов
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class StatusLog extends BaseEntity{

    /**
     * @ORM\ManyToOne(targetEntity="ClientStatus", inversedBy="statusLog")
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="statusLog")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="statusLog")
     */
    protected $user;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}