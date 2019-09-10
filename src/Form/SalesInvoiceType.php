<?php

namespace App\Form;

use App\Entity\SalesDelevery;
use App\Entity\SalesInvoice;
use App\Entity\SalesOrder;
use App\Repository\SalesDeleveryRepository;
use App\Repository\SalesOrderRepository;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\BlockReferenceNode;
use Twig\Profiler\NodeVisitor\ProfilerNodeVisitor;
use Twig_TokenParser_If;

class SalesInvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'number',
                ),
                'trim' => true,
                'required' => true,
            ))

            ->add('payAt',DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
            ->add('amount_tva',IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'account tva',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('payment_delay', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'payment delay',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('reduction', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reduction',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('discount', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'discount',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('ord', EntityType::class, array(
                'class' => SalesOrder::class,
                'query_builder' => function (SalesOrderRepository $s) {
                    return $s->createQueryBuilder('s')
                        ->andWhere('s.isvalid = :val')
                        ->setParameter('val', true)
                        ->orderBy('s.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'reference',
                'placeholder' => 'Choose an order',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('delevery', EntityType::class, array(
                'class' => SalesDelevery::class,
                'query_builder' => function (SalesDeleveryRepository $s) {
                    return $s->createQueryBuilder('s')
                        ->andWhere('s.isvalid = :val')
                        ->setParameter('val', true)
                        ->orderBy('s.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'reference',
                'placeholder' => 'Choose a delivery',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => false,
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SalesInvoice::class,
        ]);
    }
}
