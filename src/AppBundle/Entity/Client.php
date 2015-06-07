<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Client extends BaseEntity{

    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string")
     */
    protected $surName;

    /**
     * @ORM\Column(type="date")
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="integer")
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $jobPlace;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $jobPost;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $education;

    /**
     * @ORM\Column(type="string")
     */
    protected $loyalty;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * Стаж в годах
     */
    protected $experience;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $adrs;


    /**
     * @ORM\Column(type="text", nullable = true)
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="clients")
     */
    protected $project;

    /**
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="clients")
     */
    protected $events;

    /**
     * @ORM\OneToMany(targetEntity="EventAnswer", mappedBy="client")
     */
    protected $answers;

    /**
     * @ORM\ManyToOne(targetEntity="ClientStatus", inversedBy="clients")
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="clients")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="StatusLog", mappedBy="client")
     */
    protected $statusLog;


    public function __toString()
    {
        return $this->lastName . ' '
        . mb_substr($this->firstName, 0, 1, 'utf-8') . '.'
        . ($this->surName ? ' ' . mb_substr($this->surName, 0, 1, 'utf-8') . '.' : '');
    }

    public function __construct(){
        $this->statusLog = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getJobPlace()
    {
        return $this->jobPlace;
    }

    /**
     * @param mixed $jobPlace
     */
    public function setJobPlace($jobPlace)
    {
        $this->jobPlace = $jobPlace;
    }

    /**
     * @return mixed
     */
    public function getJobPost()
    {
        return $this->jobPost;
    }

    /**
     * @param mixed $jobPost
     */
    public function setJobPost($jobPost)
    {
        $this->jobPost = $jobPost;
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience = null)
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getAdrs()
    {
        return $this->adrs;
    }

    /**
     * @param mixed $adrs
     */
    public function setAdrs($adrs)
    {
        $this->adrs = $adrs;
    }

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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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

    /**
     * @return mixed
     */
    public function getSurName()
    {
        return $this->surName;
    }

    /**
     * @param mixed $surName
     */
    public function setSurName($surName)
    {
        $this->surName = $surName;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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

    /**
     * @return mixed
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param mixed $education
     */
    public function setEducation($education)
    {
        $this->education = $education;
    }

    /**
     * @return mixed
     */
    public function getLoyalty()
    {
        return $this->loyalty;
    }

    /**
     * @param mixed $loyalty
     */
    public function setLoyalty($loyalty)
    {
        $this->loyalty = $loyalty;
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param mixed $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
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
