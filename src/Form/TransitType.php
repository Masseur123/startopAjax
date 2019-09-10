<?php

namespace App\Form;

use App\Entity\Transit;
use App\Entity\Exporter;
use App\Entity\Country;

use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Extension_Escaper;

class TransitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'contract number',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('boat', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'boat',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('is_open',CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                    'checked' => 'is_open',
                ),
                'required' => false,
            ))
            ->add('countryfrom', EntityType::class, array(
                'class' => Country::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('comingfrom', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
					'placeholder' => 'origin town',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('countryto', EntityType::class, array(
                'class' => Country::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('goingto', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
					'placeholder' => 'destination town',
                ),
                'trim' => true,
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transit::class,
        ]);
    }
}
