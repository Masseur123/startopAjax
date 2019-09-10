<?php

namespace App\Form;

use App\Entity\Housing;
use App\Entity\HousingType;
use App\Entity\Branch;
use App\Entity\Reservation;
use Doctrine\ORM\Mapping\AssociationOverride;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_NodeVisitor_Escaper;
use Twig_Profiler_Dumper_Blackfire;

class HousingfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'label',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('price', MoneyType::class, array(
                'currency' => 'XAF',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'price',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('price_adult', MoneyType::class, array(
                'currency' => 'XAF',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'price_adult',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('price_child', MoneyType::class, array(
                'currency' => 'XAF',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'price_child',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('housingtype', EntityType::class, array(
                'class' => HousingType::class,
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('reservations', EntityType::class, array(
                'class' => Reservation::class,
                'expanded' => false,
                'multiple' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Housing::class,
        ]);
    }
}
