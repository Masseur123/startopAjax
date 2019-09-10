<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Bank;
use App\Entity\CostCenter;
use App\Entity\CostCenterHist;

use App\Entity\PaymentMethod;
use App\Repository\AccountRepository;
use App\Repository\CostCenterRepository;


use Doctrine\ORM\Mapping\AssociationOverride;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\AutoEscapeNode;
use Twig_Extension_InitRuntimeInterface;
use Twig_Sandbox_SecurityNotAllowedMethodError;
use Twig_TokenParser_Extends;
use Twig_TokenParser_Macro;

class BankExpensePaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'amount',
                ),
                'trim' => true,
                'required' => true,
                'disabled' => true,
            ))
            ->add('taxamount', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'tva_amount',
                ),
                'trim' => true,
                'required' => true,
                'disabled' => true,
            ))
            ->add('costcenter', EntityType::class, array(
                'class' => CostCenter::class,
                'query_builder' => function (CostCenterRepository $cc) {
                    return $cc->createQueryBuilder('c')
                        ->andWhere('c.type = :val')
                        ->setParameter('val', 'EXPENSE')
                        ->orderBy('c.id', 'DESC');
                },
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'disabled' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('payAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
                'data' => new \DateTime("now"),
                'disabled' => false,
            ))
            ->add('description', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'description',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('paymentmethod', EntityType::class, array(
                'class' => PaymentMethod::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('refNumber', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'refNumber',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('bank', EntityType::class, array(
                'class' => Bank::class,
                'choice_value' => 'id',
                'placeholder' => 'Choose an account',
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
            'data_class' => CostCenterHist::class,
        ]);
    }
}
