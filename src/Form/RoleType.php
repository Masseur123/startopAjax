<?php

namespace App\Form;

use App\Entity\Role;

use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\MenuRepository;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('title', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'title',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('icon', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'icon',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('route', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'route',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('position', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required' => false,
            ))
            ->add('menu', EntityType::class, array(
                'class' => Role::class,
                'placeholder' => 'Choose an option',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('titleEn', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'title_en',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('titleFr', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'title_fr',
                ),
                'trim' => true,
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
