<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Component;

use Doctrine\ORM\Utility\HierarchyDiscriminatorResolver;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'title',
                ),
                'trim' => true,
                'required' => true,
            ))
			->add('type', ChoiceType::class, [
				'choices' => [
					'Main Menu' => true, 
					'Sub Menu' => false
					],
				'choice_translation_domain' => 'system',
				'attr' => array(
                    'class' => 'form-control select-search',
                ),
			])
            ->add('position', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required' => false,
            ))
            ->add('icon', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'icon',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('route', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'route',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('titleEn', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'title (en)',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('titleFr', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'title (fr)',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('component', EntityType::class, array(
                'class' => Component::class,
                'placeholder' => 'Choose a component',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
