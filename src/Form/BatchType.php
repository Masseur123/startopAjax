<?php

namespace App\Form;

use App\Entity\Batch;
use App\Entity\ShippingLine;
use App\Entity\Transit;
use App\Entity\Containerlength;
use App\Repository\TransitRepository;

use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\AssignNameExpression;
use Twig_Extension_Escaper;
use Twig_Profiler_Dumper_Base;
use Twig_TokenParser_Include;
use Twig_TokenParser_Use;

class BatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transit', EntityType::class, array(
                'class' => Transit::class,
                'query_builder' => function (TransitRepository $t) {
                    return $t->createQueryBuilder('t')
                        ->andWhere('t.is_open = :val')
                        ->setParameter('val', true)
                        ->orderBy('t.id', 'DESC');
                },
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('shippingline', EntityType::class, array(
                'class' => ShippingLine::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('containerlength', EntityType::class, array(
                'class' => Containerlength::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'booking number',
                ),
                'trim' => true,
                'required' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Batch::class,
        ]);
    }
}
