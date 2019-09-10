<?php

namespace App\Form;

use App\Entity\CostCenter;
use App\Entity\CostCenterVariation;
use App\Entity\Year;
use App\Repository\CostCenterRepository;
use App\Repository\YearRepository;
use Doctrine\ORM\Mapping\AssociationOverride;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\AutoEscapeNode;
use Twig\Node\Expression\Binary\ConcatBinary;
use Twig_Extension_InitRuntimeInterface;
use Twig_NodeTraverser;
use Twig_Sandbox_SecurityNotAllowedPropertyError;

class CostCenterVariationType extends AbstractType
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
            ->add('is_increase', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
                'required' => false,
            ))
            ->add('year', EntityType::class, array(
                'class' => Year::class,
                'query_builder' => function (YearRepository $y) {
                    return $y->createQueryBuilder('y')
                        ->andWhere('y.is_open = :val1')
                        ->andWhere('y.is_current = :val2')
                        ->setParameter('val1', true)
                        ->setParameter('val2', true)
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
                'query_builder' => function (CostCenterRepository $c) {
                    return $c->createQueryBuilder('c')
                        ->andWhere('c.is_enabled = :val2')
                        ->setParameter('val2', true)
                        ->orderBy('c.id', 'DESC');

                    //return $c->costCenterAllocated($years);
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
            'data_class' => CostCenterVariation::class,
        ]);
    }
}
