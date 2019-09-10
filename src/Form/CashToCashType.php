<?php

namespace App\Form;

use App\Entity\CashDesk;
use App\Entity\InterCashTransfer;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Repository\CashDeskRepository;
use Twig\Node\Expression\NullCoalesceExpression;
use Twig_Environment;

class CashToCashType extends AbstractType
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
                'query_builder' => function (CashDeskRepository $c) {
                    return $c->createQueryBuilder('c')
                        ->andWhere('c.is_open = :val')
                        ->setParameter('val', true)
                        ->orderBy('c.id', 'DESC');
                },
                'placeholder' => 'Choose a Cashdesk',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('cashdeskDes', EntityType::class, array(
                'class' => CashDesk::class,
                'query_builder' => function (CashDeskRepository $c) {
                    return $c->createQueryBuilder('c')
                        ->andWhere('c.is_open = :val')
                        ->setParameter('val', true)
                        ->orderBy('c.id', 'DESC');
                },
                'placeholder' => 'Choose a Cashdesk',
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
