<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\Institution;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BranchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code',
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
            ->add('location', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'address',
                ),
                'trim' => true,
                'required' => true,
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
            ->add('email', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'email',
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
                'required' => true,
            ))
            ->add('institution', EntityType::class, array(
                'class' => Institution::class,
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
            'data_class' => Branch::class,
        ]);
    }
}
