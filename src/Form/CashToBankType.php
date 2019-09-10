<?php

namespace App\Form;

use App\Entity\CashDesk;
use App\Entity\Bank;
use App\Entity\InterCashTransfer;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Environment;
use Twig_Sandbox_SecurityNotAllowedMethodError;

class CashToBankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',  NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Amount',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('description',  TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'description',
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
                'required' => false,
            ))
            ->add('cashdeskSrc', EntityType::class, array(
                'class' => CashDesk::class,
                'placeholder' => 'Choose a Cashdesk',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('bankDes', EntityType::class, array(
                'class' => Bank::class,
                'placeholder' => 'Choose a Bank',
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
            'data_class' => InterCashTransfer::class,
        ]);
    }
}
