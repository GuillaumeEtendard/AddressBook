<?php

namespace ContactsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName','text',array('label'=>'Prénom'))
            ->add('lastName','text',array('label'=>'Nom de famille'))
            ->add('email','email',array('label'=>'Email'))
            ->add('address','text',array('label'=>'Adresse'))
            ->add('phoneNumber','text',array('label'=>'Téléphone'))
            ->add('webSite','url',array('label'=>'Site web'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EntitiesBundle\Entity\Contacts'
        ));
    }
}
