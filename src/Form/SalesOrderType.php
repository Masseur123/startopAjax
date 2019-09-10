<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Order;
use App\Entity\Provider;
use App\Entity\Quotation;
use App\Entity\SalesOrder;
use App\Entity\SalesQuotation;
use App\Repository\QuotationRepository;
use App\Repository\SalesQuotationRepository;
use App\Repository\SupplyRequestRepository;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Twig_TokenParser_Sandbox;

class SalesOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('quotation', EntityType::class, array(
                'class' => SalesQuotation::class,
                'query_builder' => function (SalesQuotationRepository $s) {
                    return $s->createQueryBuilder('s')
                        ->andWhere('s.isvalid = :val')
                        ->setParameter('val', true)
                        ->orderBy('s.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'reference',
                'placeholder' => 'Choose a quotation',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('provider', EntityType::class, array(
                'class' => Provider::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label'=> 'person.name',
                'placeholder' => 'Choose a provider',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => false
            ))
            ->add('date',DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SalesOrder::class,
        ]);
    }
}
