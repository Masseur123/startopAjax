<?php

namespace App\Form;

use App\Entity\Currency;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyType extends AbstractType
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
            ->add('number', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'number',
                ),
                'trim' => true,
                'required' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Currency::class,
        ]);
    }
}
