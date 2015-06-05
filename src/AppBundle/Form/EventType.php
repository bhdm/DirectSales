<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = array(
            'Собрание' => 'Собрание',
            'Подписи' => 'Подписи',
            'Приход' => 'Приход',
        );

        $builder
            ->add('title', null, array('label'=> 'Название события'))
            ->add($builder->create('type',  'choice', array('required' => true,    'label' => 'Тип события', 'choices' => $type, 'attr'=> array('data-placeholder'=>'Выберите тип'))))
            ->add('submit', 'submit', array('label' => 'Сохранить', 'attr' => array('class' => 'btn-primary pull-right')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_event';
    }
}
