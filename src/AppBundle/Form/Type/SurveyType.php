<?php

// src/AppBundle/Form/Type/SurveyType.php
namespace AppBundle\Form\Type;

// http://symfony.com/doc/current/cookbook/form/form_collections.html

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
/*        $builder->add('surveyname'); */
        $builder->add('title');
        $builder->add('numberofvoters');

/*
        $builder->add('uiduser', 
	  \Symfony\Component\Form\Extension\Core\Type\TextType::class,
	  array("read_only" => true));

        $builder->add('uidmanager', 
	  \Symfony\Component\Form\Extension\Core\Type\TextType::class,
	  array("read_only" => true));

        $builder->add('status', 
	  \Symfony\Component\Form\Extension\Core\Type\TextType::class,
	  array("read_only" => true));
*/


        $builder->add('questions', CollectionType::class, array(
            'entry_type' => QuestionType::class,
	    'allow_add'  => true,
	    'by_reference' => false,
	    'label' => false,
        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Survey',
        ));
    }
}