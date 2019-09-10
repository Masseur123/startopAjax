<?php

namespace App\Form;

use App\Entity\Container;
use App\Entity\Park;
use App\Entity\ContainerTracking;
use App\Repository\AccountRepository;

use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ContainerRepository;
use Symfony\Component\Security\Http\Firewall;
use Twig\Node\ImportNode;
use Twig_Extension;
use Twig_Sandbox_SecurityNotAllowedTagError;

class ContainerTrackingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('containers', EntityType::class, array(
                'class' => Container::class,
                'query_builder' => function (ContainerRepository $c) {
                    return $c->createQueryBuilder('c')
                        ->andWhere('c.reference is not null')
                        ->orderBy('c.id', 'DESC');
                },
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => true,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control multiselect',
                ),
            ))
            ->add('sources', EntityType::class, array(
                'class' => Park::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'required' => true,
            ))
            ->add('destination', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'destination',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('trackAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContainerTracking::class,
        ]);
    }
}
