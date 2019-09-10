<?php

namespace App\Form;

use App\Entity\LoadingLog;
use App\Entity\Container;
use App\Entity\Wood;
use App\Entity\Transit;

use App\Repository\ContainerRepository;
use App\Repository\TransitRepository;


use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Firewall;
use Twig\Node\Expression\AssignNameExpression;
use Twig\Node\ImportNode;
use Twig_Extension_Escaper;
use Twig_Filter;
use Twig_Node;

class LoadingLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('loadAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
            ->add('nbrofpiece', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'number of log',
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
            ->add('volume', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'volume',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('container', EntityType::class, array(
                'class' => Container::class,
                'placeholder' => 'Choose an option',
                'query_builder' => function (ContainerRepository $c) {
                    return $c->createQueryBuilder('c')
                        ->andWhere('c.reference != :val')
                        ->setParameter('val', '')
                        ->orderBy('c.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'required' => false,
            ))
            ->add('wood', EntityType::class, array(
                'class' => Wood::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'required' => true,
            ))
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
            'data_class' => LoadingLog::class,
        ]);
    }
}
