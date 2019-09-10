<?php

namespace App\Form;

use App\Entity\Delevery;
use App\Entity\Order;
use App\Entity\Provider;
use App\Entity\SalesDelevery;
use App\Entity\SalesOrder;
use App\Repository\OrderRepository;
use App\Repository\QuotationRepository;
use App\Repository\SalesOrderRepository;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\BlockReferenceNode;

class SalesDeleveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
            ->add('deleverAt',DateType::class, array(
                'required' => true,
                'widget' => 'single_text',
            ))
            ->add('commande', EntityType::class, array(
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
                'required' => false
            ))
            ->add('provider', EntityType::class, array(
                'class' => Provider::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'person.name',
                'placeholder' => 'Choose a provider',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SalesDelevery::class,
        ]);
    }
}
