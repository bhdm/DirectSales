<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.06.15
 * Time: 22:53
 * Вопросы для мероприятия - опроса
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class EventQuestion extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="questions")
     */
    protected $event;
    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="EventAnswer", mappedBy="question")
     */
    protected $answers;

    public function __construct(){
        $this->answers = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
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
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }



}