<?php

namespace App\Form;

use App\Entity\Component;

use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentType extends AbstractType
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
            ->add('position', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required' => false,
            ))
            ->add('icon', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'icon',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('route', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'route',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('nameEn', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name (en)',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('nameFr', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name (fr)',
                ),
                'trim' => true,
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Component::class,
        ]);
    }
}
