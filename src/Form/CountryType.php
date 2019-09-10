<?php

namespace App\Form;

use App\Entity\Country;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('code_2', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code 2',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('code_3', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code 3',
                ),
                'trim' => true,
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }
}
