<?php

namespace App\Form;

use App\Entity\Family;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamilyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Family::class,
        ]);
    }
}
