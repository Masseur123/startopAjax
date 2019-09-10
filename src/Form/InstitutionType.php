<?php

namespace App\Form;

use App\Entity\BusinessType;
use App\Entity\Institution;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cigle', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'cigle',
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
                'required' => true,
            ))
            ->add('email', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'email',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('fixphone', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'fix_phone',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('mobilephone', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'mobile_phone',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('address', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'address',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('town', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'town',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('pobox', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pobox',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('website', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'website',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('taxpayernumber', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'tax_payer_number',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('businessnumber', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'business_number',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('businessType', EntityType::class, array(
                'class' => BusinessType::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Institution::class,
        ]);
    }
}
