<?php

namespace App\Form;

use App\Entity\Container;
use App\Entity\Loading;
use App\Entity\Wood;
use App\Repository\ContainerRepository;
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
use Twig\Node\ImportNode;
use Twig_Node;
use Twig_Test;

class LoadingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('loadingAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => false,
            ))
            ->add('nbrofpiece', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'nbr_of_piece',
                ),
                'trim' => true,
                'required' => true,
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
                'required' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Loading::class,
        ]);
    }
}
