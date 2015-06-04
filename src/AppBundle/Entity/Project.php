<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.06.15
 * Time: 22:54
 * Проекты
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Project extends BaseEntity{

    /**
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="project")
     */
    protected $events;

    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="project")
     */
    protected $clients;

    public function __construct(){
        $this->events = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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



    public function getEvents()
    {
        return $this->events;
    }

    public function setEvents($events)
    {
        $this->events = $events;
    }

    public function addEvent($action){
        $this->events[] = $action;
    }

    public function removeEvent($action){
        $this->events->removeElement($action);
    }


    public function getClients()
    {
        return $this->clients;
    }

    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    public function addClient($client){
        $this->clients[] = $client;
    }

    public function removeClient($client){
        $this->clients->removeElement($client);
    }

}