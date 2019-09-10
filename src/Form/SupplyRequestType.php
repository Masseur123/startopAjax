<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\Service;
use App\Entity\SupplyRequest;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Extension_Core;
use Twig_TokenParser_Filter;

class SupplyRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array(
                'required' => true,
                'widget' => 'single_text',
            ))
            ->add('applicant', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'applicant',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('service', EntityType::class, array(
                'class' => Service::class,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choose a service',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SupplyRequest::class,
        ]);
    }
}
