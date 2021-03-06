<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 24.05.15
 * Time: 16:30
 * Сущность операторов и агентов
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UserRepository")
 */
class User extends BaseEntity implements UserInterface{

    # Данные свойства необходимы для построения дерева
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    protected $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    protected $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    protected $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    protected $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="User", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    # Теперь все остальные свойства

    /**
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="users")
     * @ORM\JoinTable(name="user_project",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")}
     * )
     */
    protected $projects;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank( message = "поле E-mail обязательно для заполнения" )
     */
    protected $username;

    /**
     * @Assert\NotBlank( message = "Поле Email обязательно для заполнения" )
     * @Assert\Length( max = "35", maxMessage = "Максимум  35 символов")
     * @Assert\Regex(pattern= "/^[a-zа-яA-ZА-Я]+$/u", message="Неверный формат ввода.")
     * @ORM\Column(type="string", length=100)
     */
    protected  $lastName;

    /**
     * @Assert\NotBlank( message = "Поле имя обязательно для заполнения" )
     * @Assert\Length( max = "35", maxMessage = "Максимум  35 символов")
     * @Assert\Regex(pattern= "/^[a-zа-яA-ZА-Я]+$/u", message="Неверный формат ввода.")
     * @ORM\Column(type="string", length=100)
     */
    protected  $firstName;

    /**
     * @Assert\Length( max = "35", maxMessage = "Максимум  35 символов")
     * @Assert\Regex(pattern= "/^[a-zа-яA-ZА-Я]+$/u", message="Неверный формат ввода.")
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected  $surName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected  $phone;

    /**
     * @Assert\NotBlank( message = "Поле пароль обязательно для заполнения" )
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $password = '1';


    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string salt
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="EventAnswer", mappedBy="user")
     */
    protected $answers;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="sender")
     */
    protected $sent;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="receiver")
     */
    protected $received;

    /**
     * @ORM\OneToMany(targetEntity="StatusLog", mappedBy="user")
     */
    protected $statusLog;

//    /**
//     * @ORM\ManyToOne(targetEntity="User", inversedBy="childs")
//     */
//    protected $parent;
//
//    /**
//     * @ORM\OneToMany(targetEntity="User", mappedBy="parent")
//     */
//    protected $childs;

    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="user")
     */
    protected $clients;

    public function __toString()
    {
        return $this->lastName . ' '
        . mb_substr($this->firstName, 0, 1, 'utf-8') . '.'
        . ($this->surName ? ' ' . mb_substr($this->surName, 0, 1, 'utf-8') . '.' : '');
    }

    public function __construct(){
        $this->projects = new ArrayCollection();
        $this->sent = new ArrayCollection();
        $this->received = new ArrayCollection();
        $this->statusLog = new ArrayCollection();
        $this->parent = new ArrayCollection();
        $this->clients = new ArrayCollection();

        $this->roles = 'ROLE_AGENT';
    }
    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
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


    public function checkRole($role)
    {
        $roles = explode(';', $this->roles);

        return in_array($role, $roles);
    }

    /**
     * Сброс прав пользователя.
     */
    public function eraseCredentials()
    {
//        $this->roles = 'ROLE_UNCONFIRMED';
//        $this->password = null;
    }

    public function isEqualTo(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    /**
     * Сериализуем только id, потому что UserProvider сам перезагружает остальные свойства пользователя по его id
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id
            ) = unserialize($serialized);
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return explode(';', $this->roles);
    }

    /**
     * @return mixed
     */
    public function getRolesString()
    {
        $roles =  explode(';', $this->roles);
        $role = '';
        foreach ($roles as $item){
            if ($item == 'ROLE_ADMIN') $role .= 'Администратор ';
            elseif ($item == 'ROLE_OPERATOR') $role .= 'Оператор ';
            elseif ($item == 'ROLE_AGENT') $role .= 'Агент ';
            else  $role .= 'Другое ';
        }
        return $role;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        if (is_array($roles)) {
            $roles = implode($roles, ';');
        }

        $this->roles = $roles;
    }

    public function getRole(){
        return explode(';', $this->roles)[0];
    }

    public function setRole($roles){
        $this->roles = $roles;

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
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param mixed $projects
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;
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
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * @param mixed $sent
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    }

    public function addSent($sent){
        $this->sent[] = $sent;
    }

    public function removeSent($sent){
        $this->sent->removeElement($sent);
    }


    /**
     * @return mixed
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * @param mixed $received
     */
    public function setReceived($received)
    {
        $this->received = $received;
    }

    public function addReceived($received){
        $this->received[] = $received;
    }

    public function removeReceived($received){
        $this->received->removeElement($received);
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
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChilds()
    {
        return $this->children;
    }

    /**
     * @param mixed $childs
     */
    public function setChilds($childs)
    {
        $this->children = $childs;
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
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param mixed $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * @param mixed $lvl
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }

    /**
     * @return mixed
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * @param mixed $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param mixed $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function __children(){
        return $this->getChildren();
    }

}