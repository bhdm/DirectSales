<?php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('role', array($this, 'roleFilter')),
        );
    }

    public function roleFilter($role)
    {
        switch ($role){
            case 'ROLE_ADMIN': return 'Администратор';
            case 'ROLE_OPERATOR': return 'Оператор';
            case 'ROLE_AGENT': return 'Агент';
        }

        return $role;
    }

    public function getName()
    {
        return 'app_extension';
    }
}