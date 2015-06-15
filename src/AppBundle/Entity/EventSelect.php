<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.06.15
 * Time: 22:53
 * Варианты ответов на вопросы
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class EventSelect extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="EventQuestion", inversedBy="selects")
     */
    protected $question;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
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




}