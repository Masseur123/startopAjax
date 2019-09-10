<?php

namespace App\Form;

use App\Entity\Bank;
use App\Entity\Civility;
use App\Entity\Invoice;
use App\Entity\SalesSettlement;
use App\Entity\Settlement;
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
use Twig_Function;
use Twig_Sandbox_SecurityNotAllowedMethodError;

class SalesSettlementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    //'placeholder' => 'amount',
                ),
                'trim' => true,
                'disabled' => true,
                'required' => false
            ))
            ->add('amountPay', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pay'
                ),
                'trim' => true,
                'required' => false
            ))
            /*->add('reference',TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => true,
            ))*/
            ->add('tva',IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'tva',
                ),
                'trim' => true,
                'required' => false,
            ))
            //->add('is_approved')
           // ->add('settleAt')
            //->add('status')
            //->add('invoice')
            //->add('hotel')
            //->add('provider')
            //->add('customer')
            //->add('cashier')
            ->add('bank', EntityType::class, array(
                'class' => Bank::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => false
            ))
            //->add('branch')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SalesSettlement::class,
        ]);
    }
}
