<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.06.15
 * Time: 22:45
 * Сущность описывает дополнительные поля клиента (мероприятия)
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Field extends BaseEntity{

    /**
     * Название поля
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * тип поля (string, select, area)
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * значения для select
     * @ORM\Column(type="array")
     */
    protected $values;


}