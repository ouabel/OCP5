<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FilterDistrictsForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('province', EntityType::class, array(
			'class' => 'AppBundle:Province',
			'choice_label' => 'name',
            'required'   => false,
            'label' => false
		));
    }

}
