<?php

namespace App\Form;

use App\Entity\LogProcurement;
use App\Entity\Park;
use App\Entity\Transit;
use App\Entity\Wood;
use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Node;
use Twig_Sandbox_SecurityNotAllowedTagError;
use Twig_TokenParser_Flush;

class LogProcurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('source', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'source',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('consignment_note', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'consignment note',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('quantity', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'quantity',
                ),
                'required' => true,
            ))
            ->add('volume', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'volume',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('byingAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
            ->add('destination', EntityType::class, array(
                'class' => Park::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('wood', EntityType::class, array(
                'class' => Wood::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LogProcurement::class,
        ]);
    }
}
