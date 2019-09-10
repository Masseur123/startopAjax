<?php

namespace App\Form;

use App\Entity\Extra;
use App\Entity\ExtraCategory;
use App\Entity\Branch;
use App\Entity\GeneralArea;
use App\Entity\Meal;
use App\Entity\Reservation;
use Doctrine\ORM\Mapping\AssociationOverride;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Profiler_Node_EnterProfile;
use Twig_TokenParser_Block;

class ExtraType extends AbstractType
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
            ->add('code', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('property', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'property',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('price', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'price',
                ),
                'currency' => 'XAF',
            ))
            ->add('generalArea', EntityType::class, array(
                'class' => GeneralArea::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'choose an option',
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
            ->add('meal', EntityType::class, array(
                'class' => Meal::class,
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
            'data_class' => Extra::class,
        ]);
    }
}
