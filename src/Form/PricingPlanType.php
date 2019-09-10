<?php

namespace App\Form;

use App\Entity\PricingPlan;
use App\Entity\Hotel;
use App\Entity\HousingType;
use App\Entity\Pricing;
use App\Entity\Tag;
use App\Entity\TagPlanConfiguration;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_NodeVisitor_Sandbox;

class PricingPlanType extends AbstractType
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
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'description',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('reduction', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reduction',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('housingType', EntityType::class, array(
                'class' => HousingType::class,
                'expanded' => false,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('tags', EntityType::class, array(
                'class' => Tag::class,
                'expanded' => false,
                'multiple' => true,
                'choice_label' => 'name',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            /*->add('tagPlanConfigurations', EntityType::class, array(
                'class' => TagPlanConfiguration::class,
                'expanded' => false,
                'multiple' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))*/
            ->add('pricing', EntityType::class, array(
                'class' => Pricing::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name',
                'placeholder' => 'Choose an option',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PricingPlan::class,
        ]);
    }
}
