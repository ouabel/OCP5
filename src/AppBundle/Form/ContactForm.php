<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'Votre nom'))
                ->add('email', EmailType::class, array('label' => 'Email'))
                ->add('subject', TextType::class, array('label' => 'Sujet'))
				->add('message', TextareaType::class, array('label' => 'Message'))
				->add('send', SubmitType::class, array('label' => 'Envoyer'));
    }

}
