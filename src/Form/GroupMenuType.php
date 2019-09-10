<?php

namespace App\Form;

use App\Entity\UserGroup;
use App\Entity\Component;
use App\Entity\Menu;
use App\Repository\ComponentRepository;
use App\Repository\MenuRepository;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Extension_Optimizer;

class GroupMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$value = $options['componentId'];
		
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required' => true,
                'trim' => true,
                'translation_domain' => 'admin',
            ))
            ->add('menus',EntityType::class, array(
                'class' => Menu::class,
                'query_builder' => function (MenuRepository $m) use ($value) {
                    return $m->createQueryBuilder('m')
                        ->andWhere('m.is_enabled = :isenable')
						->andWhere('m.component = :value')
                        ->setParameter('isenable', true)
						->setParameter('value', $value)
                        ->orderBy('m.position', 'ASC');
                },
                'placeholder' => 'assign_menus',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
				'translation_domain' => 'admin',
                'attr' => array(
                    'class' => 'form-control multiselect',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserGroup::class,
        ]);
		$resolver->setRequired(['componentId']);
    }
}
