<?php

namespace App\Form;

use App\Entity\CostCenter;
use App\Entity\CostCenterHist;
use App\Entity\Currency;
use App\Entity\Tax;
use App\Entity\Year;
use App\Repository\CostCenterRepository;
use App\Repository\CurrencyRepository;
use App\Repository\YearRepository;
use Doctrine\ORM\Mapping\AssociationOverride;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\AutoEscapeNode;
use Twig\Node\Expression\Binary\ConcatBinary;
use Twig\Node\Expression\MethodCallExpression;
use Twig_Extension_InitRuntimeInterface;
use Twig_Sandbox_SecurityNotAllowedPropertyError;
use Twig_Sandbox_SecurityPolicyInterface;
use Twig_TokenParser_Extends;

class ExpenseHistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'amount',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('doingAt',DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
                'data' => new \DateTime("now"),
            ))
            ->add('cashpay', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                    'checked' => 'checked',
                ),
                'required' => false,
            ))
            ->add('currency', EntityType::class, array(
                'class' => Currency::class,
                'query_builder' => function (CurrencyRepository $c) {
                    return $c->createQueryBuilder('c')
                        ->andWhere('c.is_current = :val')
                        ->setParameter('val', 1)
                        ->orderBy('c.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('year', EntityType::class, array(
                'class' => Year::class,
                'query_builder' => function (YearRepository $y) {
                    return $y->createQueryBuilder('y')
                        ->andWhere('y.is_current = :val')
                        ->setParameter('val', true)
                        ->orderBy('y.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('costcenter', EntityType::class, array(
                'class' => CostCenter::class,
                'query_builder' => function (CostCenterRepository $cc) {
                    return $cc->createQueryBuilder('c')
                        ->andWhere('c.type = :val1')
                        ->andWhere('c.is_enabled = :val2')
                        ->setParameter('val1', 'EXPENSE')
                        ->setParameter('val2', true)
                        ->orderBy('c.id', 'DESC');
                },
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('tax', EntityType::class, array(
                'class' => Tax::class,
                'placeholder' => 'Choose an option (T.V.A)',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CostCenterHist::class,
        ]);
    }
}
