<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\CashDesk;
use App\Entity\Currency;
use App\Entity\Customer;
use App\Entity\Delevery;
use App\Entity\Invoice;
use App\Entity\Order;
use App\Entity\PaymentMethod;
use App\Entity\Provider;
use App\Repository\DeleveryRepository;
use App\Repository\OrderRepository;
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
use Twig\Node\Expression\Binary\EndsWithBinary;
use Twig\Node\Expression\BlockReferenceExpression;
use Twig_Profiler_Profile;

class InvoiceType extends AbstractType
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
                'class' => Order::class,
                'query_builder' => function (OrderRepository $s) {
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
                'class' => Delevery::class,
                'query_builder' => function (DeleveryRepository $s) {
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
            'data_class' => Invoice::class,
        ]);
    }
}
