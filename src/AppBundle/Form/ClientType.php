<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gender = array(
            '1' => 'Мужской',
            '0' => 'Женский',
        );

        $status = array(
            '0' => 'Новый',
            '1' => 'совершена первая беседа,',
            '2' => 'СОбрана подпись',
            '3' => 'Заключен договор',
            '4' => 'Отменен',
        );

        $builder
            ->add('lastName', null, array('label' => 'Фамилия'))
            ->add('firstName', null, array('label' => 'Имя'))
            ->add('surName', null, array('label' => 'Отчество'))
            ->add('birthDate', null, array('label' => 'Фамилия'))
            ->add($builder->create('gender',  'choice', array('required' => true,    'label' => 'Пол', 'choices' => $gender, 'attr'=> array('data-placeholder'=>'Выберите пол'))))
            ->add('jobPlace', null, array('label' => 'Место работы'))
            ->add('jobPost', null, array('label' => 'Специальность'))
            ->add('experience', 'integer', array('label' => 'Стаж'))
            ->add('adrs', null, array('label' => 'Адрес проживания'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('email', null, array('label' => 'Email'))
            ->add($builder->create('status',  'choice', array('required' => true, 'label' => 'Статус', 'choices' => $status)))
            ->add('comment', null, array('label' => 'Комментарий'));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_client';
    }
}
