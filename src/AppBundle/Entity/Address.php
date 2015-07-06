<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 30.06.15
 * Time: 13:15
 * Сущность описывает Адреса как отдельные сущности, так и связь с клиентами
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AddressRepository")
 */
class Address extends BaseEntity{


    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="adrs")
     */
    protected $clients;

    /**
     * @ORM\Column(type="text", nullable = true)
     */
    protected $title;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="adrs")
     */
    protected $project;


    public function __toString(){
        return $this->title;
    }

    public function __construct(){
        $this->clients = new ArrayCollection();
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
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }


}
