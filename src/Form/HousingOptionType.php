<?php

namespace App\Form;
use App\Entity\Branch;

use App\Entity\HousingOption;
use App\Entity\HousingType;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_TokenParser_With;

class HousingOptionType extends AbstractType
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
            ->add('housingtype', EntityType::class, array(
                'class' => HousingType::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name',
                'placeholder' => 'Choose a option',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HousingOption::class,
        ]);
    }
}
