<?php

namespace App\Form;

use App\Entity\CostCenter;
use App\Entity\ParamCostCenter;
use App\Entity\Year;
use App\Repository\CostCenterRepository;
use App\Repository\YearRepository;
use Doctrine\ORM\Mapping\AssociationOverride;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\AccountRepository;
use Twig\Node\AutoEscapeNode;
use Twig\Node\Expression\Binary\ConcatBinary;
use Twig_Extension_InitRuntimeInterface;
use Twig_Sandbox_SecurityNotAllowedPropertyError;
use Twig_TokenParser_Spaceless;

class ParamCostCenterType extends AbstractType
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
                'currency' => 'XAF',
            ))
            ->add('year', EntityType::class, array(
                'class' => Year::class,
                'query_builder' => function (YearRepository $y) {
                    return $y->createQueryBuilder('y')
                        ->andWhere('y.is_open = :open')
                        ->andWhere('y.is_current = :current')
                        ->setParameter('open', true)
                        ->setParameter('current', true)
                        ->orderBy('y.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            /*->add('costcenter', EntityType::class, array(
                'class' => CostCenter::class,
                'query_builder' => function (CostCenterRepository $c) {
                    return $c->findCostCenterWithAccountConfigure();
                },
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))*/
            ->add('costcenter', EntityType::class, array(
                'class' => CostCenter::class,
                'query_builder' => function (CostCenterRepository $c) {
                    //return $c->costCenterWithAccountConfigured();
                    return $c->createQueryBuilder('c')
                        ->andWhere('c.is_enabled = :enabled')
                        ->andWhere('c.account <> :account')
                        ->setParameter('enabled', true)
                        ->setParameter('account', 'NULL')
                        ->orderBy('c.id', 'DESC');
                },
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
            'data_class' => ParamCostCenter::class,
        ]);
    }
}
