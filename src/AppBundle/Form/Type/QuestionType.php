<?php

// src/AppBundle/Form/Type/QuestionType.php
namespace AppBundle\Form\Type;

// http://symfony.com/doc/current/cookbook/form/form_collections.html

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');

	$builder->add('select', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,
          array(
	    'expanded' => true, 'multiple' => false, // to have radiobutton
	    'mapped' => false,
            'choices'  => array(
            'null' => null,
            'Yes' => true,
            'No' => false,
           ),
	   'data' => 'null',

           'choice_label' => function ($value, $key, $index) {
             if ($value == 'Yes') {
               return 'Yes';
             } elseif ($value == 'No') {
               return 'No';
             } else {
               return 'Undefined';
	     }

             // or if you want to translate some key
             //return 'form.choice.'.$key;
           },


	));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Question',
        ));
    }
}
