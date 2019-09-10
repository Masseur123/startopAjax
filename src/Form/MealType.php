<?php

namespace App\Form;

use App\Entity\Meal;

use Doctrine\ORM\Mapping\AssociationOverride;
use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Sandbox_SecurityNotAllowedFilterError;

class MealType extends AbstractType
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
            ->add('ordering', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'ordering',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('on_checkin', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
            /*->add('is_enabled', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                    'checked' => 'is_open',
                ),
            ))*/
            ->add('on_checkout', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
            ->add('on_stay', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
            ->add('price', MoneyType::class, array(
                'currency' => 'XAF',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('price_adult', MoneyType::class, array(
                'currency' => 'XAF',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('price_child', MoneyType::class, array(
                'currency' => 'XAF',
                'attr' => array(
                    'class' => 'form-control ',
                ),
            ))
            ->add('daily_chargable', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
            ->add('mandatory', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
        ]);
    }
}
