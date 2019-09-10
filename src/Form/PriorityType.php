<?php

namespace App\Form;

use App\Entity\Priority;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriorityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code',
                    'cursor' => 'pointer',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('description', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'description',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('level', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'level',
                ),
                'required' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Priority::class,
        ]);
    }
}
