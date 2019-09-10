<?php

namespace App\Form;

use App\Entity\Person;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;

class ShippingLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('cigle')
            ->add('name')

            ->add('fixphone')
            ->add('mobilephone')
            ->add('busphone')
            ->add('email')
            ->add('pobox')

            ->add('address')
            ->add('town')

            ->add('taxpayernumber')
            ->add('businessnumber')
            ->add('website')
            ->add('logo')

            ->add('note')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
