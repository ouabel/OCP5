<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FilterProfilesForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('specialty', EntityType::class, array(
			'class' => 'AppBundle:Specialty',
			'choice_label' => 'name',
            'required'   => false,
            'label' => false,
            'attr' => ['placeholder' => 'ss']
		))->add('province', EntityType::class, array(
            'class' => 'AppBundle:Province',
            'choice_label' => 'name',
            'required'   => false,
            'label' => false
        ))->add('district', ChoiceType::class, array(
            'required'   => false,
            'label' => false
        ));
    }

}
