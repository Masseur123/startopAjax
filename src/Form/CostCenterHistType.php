<?php

namespace App\Form;

use App\Entity\CostCenter;
use App\Entity\CostCenterHist;
use App\Entity\Tax;
use App\Repository\CostCenterRepository;
use Doctrine\ORM\Mapping\AssociationOverride;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\AutoEscapeNode;
use Twig_Extension_InitRuntimeInterface;
use Twig_Sandbox_SecurityPolicyInterface;
use Twig_TokenParser_Extends;

class CostCenterHistType extends AbstractType
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
            ->add('taxamount', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'tax_amount',
                ),
                'trim' => true,
                'required' => false,
                'currency' => 'XAF',
            ))
            ->add('doingAt', DateType::class, array(
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
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('tax', EntityType::class, array(
                'class' => Tax::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('description', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'description',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('refNumber', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CostCenterHist::class,
        ]);
    }
}
