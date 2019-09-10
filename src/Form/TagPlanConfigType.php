<?php

namespace App\Form;

use App\Entity\PricingPlan;
use App\Entity\Tag;
use App\Entity\TagPlanConfiguration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagPlanConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'extra_adult',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('tag', EntityType::class, array(
                'class' => Tag::class,
                'expanded' => false,
                'multiple' => true,
                'choice_label' => 'name',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('pricingplan', EntityType::class, array(
                'class' => PricingPlan::class,
                'expanded' => false,
                'multiple' => true,
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
            'data_class' => TagPlanConfiguration::class,
        ]);
    }
}
