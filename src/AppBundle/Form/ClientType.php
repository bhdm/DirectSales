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

        $education = array(
            'Нету' => 'Нету',
            'Среднее' => 'Среднее',
            'Среднее специальное' => 'Среднее специальное',
            'Неоконченное высшее' => 'Неоконченное высшее',
            'Неполное высшее' => 'Неполное высшее',
            'Высшее' => 'Высшее',
        );

        $loyalty = array(
            'Сторонник' => 'Сторонник',
            'Противник' => 'Противник',
            'Неопределился' => 'Неопределился',
        );

        $builder
            ->add('lastName', null, array('label' => 'Фамилия'))
            ->add('firstName', null, array('label' => 'Имя'))
            ->add('surName', null, array('label' => 'Отчество'))
            ->add('birthDate', 'date', array(
                'label'  => 'Дата рождения',
                'years'  => range(date('Y') - 111, date('Y')-17),
                'data'   => new \DateTime('1970-01-01'),
                'format' => 'dd MMMM yyyy',
                'attr' => array('class' => 'date-select')
            ))
            ->add($builder->create('gender',  'choice', array('required' => true,    'label' => 'Пол', 'choices' => $gender, 'attr'=> array('data-placeholder'=>'Выберите пол'))))
            ->add('jobPlace', null, array('label' => 'Место работы'))
            ->add('jobPost', null, array('label' => 'Специальность'))
            ->add($builder->create('education',  'choice', array('required' => true,    'label' => 'Образование', 'choices' => $education, 'attr'=> array('data-placeholder'=>'Выберите образование'))))
            ->add($builder->create('loyalty',  'choice', array('required' => true,    'label' => 'Статус лояльности', 'choices' => $loyalty, 'attr'=> array('data-placeholder'=>'Выберите статус'))))
//            ->add('jobPost', null, array('label' => 'Образование'))
//            ->add('jobPost', null, array('label' => 'Статус лояльности'))
            ->add('experience', 'integer', array('label' => 'Стаж'))
            ->add('adrs', null, array('label' => 'Адрес проживания'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('email', null, array('label' => 'Email'))
            ->add('status', null, array('label' => 'Статус'))
            ->add('comment', null, array('label' => 'Комментарий'))
            ->add('submit', 'submit', array('label' => 'Сохранить', 'attr' => array('class' => 'btn-primary pull-right')))
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
