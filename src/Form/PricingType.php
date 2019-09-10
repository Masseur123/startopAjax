<?php

namespace App\Form;

use App\Entity\Pricing;
use App\Entity\Hotel;
use App\Entity\HousingType;
use App\Entity\Housing;
use App\Entity\PricingPlan;
use App\Entity\Season;
use Doctrine\ORM\Mapping\AssociationOverride;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Error;
use Twig_NodeVisitor_Escaper;
use Twig_NodeVisitor_Sandbox;
use Twig_Profiler_Dumper_Blackfire;

class PricingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', MoneyType::class, array(
                'currency' => 'XAF',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'price',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('extra_adult', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'extra_adult',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('extra_child', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'extra_child',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('extra_baby', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'extra_baby',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('date',DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
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
            ->add('season', EntityType::class, array(
                'class' => Season::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            
            ->add('housingtype', EntityType::class, array(
                'class' => HousingType::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pricing::class,
        ]);
    }
}
