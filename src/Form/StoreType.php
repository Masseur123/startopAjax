<?php

namespace App\Form;

use App\Entity\Store;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Asset\Context\NullContext;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'designation',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('location', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'location',
                ),
                'trim' => true,
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Store::class,
        ]);
    }
}
