<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $role = array(
            'ROLE_ADMIN' => 'Администратор',
            'ROLE_OPERATOR' => 'Оператор',
            'ROLE_AGENT' => 'Агент',
        );

        $status = array(
            'ROLE_ADMIN' => 'Администратор',
            'ROLE_OPERATOR' => 'Оператор',
            'ROLE_AGENT' => 'Агент',
        );

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
