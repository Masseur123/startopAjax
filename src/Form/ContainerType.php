<?php

namespace App\Form;

use App\Entity\Batch;
use App\Entity\Container;
use App\Entity\Containerlength;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Firewall;
use Twig_Profiler_Dumper_Base;
use Twig_TokenParser_Include;

class ContainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('length', EntityType::class, array(
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
                    'placeholder' => 'container number',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('batch', EntityType::class, array(
                'class' => Batch::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('plumb', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'plumb number',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('is_certified', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Container::class,
        ]);
    }
}
