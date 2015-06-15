<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class UserType extends AbstractType
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->securityContext->getToken()->getUser();

        if ($user->getRole() == 'ROLE_ADMIN'){
            $role = array(
                'ROLE_ADMIN' => 'Администратор',
                'ROLE_OPERATOR' => 'Оператор',
                'ROLE_AGENT' => 'Агент',
            );
        }else{
            $role = array(
                'ROLE_AGENT' => 'Агент',
            );
        }



        $builder
            ->add('username', null, array('label' => 'Email'))
            ->add('lastName', null, array('label' => 'Фамилия'))
            ->add('firstName', null, array('label' => 'Имя'))
            ->add('surName', null, array('label' => 'Отчество'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('password', 'repeated', array('type' => 'password', 'invalid_message' => 'пароли не совпадают', 'first_options'  => array('label' => 'Пароль'),
                'second_options' => array('label' => 'Повторите пароль'),))
            ->add('role', 'choice', array('label' => 'Роль', 'choices' => $role))
            ->add('submit', 'submit', array('label' => 'Сохранить', 'attr' => array('class' => 'btn-primary pull-right')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_user';
    }
}
