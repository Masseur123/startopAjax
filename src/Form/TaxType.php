<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Extra;
use App\Entity\Tax;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Sandbox_SecurityPolicyInterface;

class TaxType extends AbstractType
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
            ->add('rate', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'rate',
                ),
                'trim' => true,
                'required' => true,
            ))
            //->add('createdAt')

            //->add('user')
            ->add('account', EntityType::class, array(
                'class' => Account::class,
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                    'placeholder' => 'Choose account',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tax::class,
        ]);
    }
}
