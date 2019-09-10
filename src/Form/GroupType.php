<?php

namespace App\Form;

use App\Entity\UserGroup;
use App\Entity\Component;
use App\Repository\ComponentRepository;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\EmbedNode;
use Twig_Extension_Optimizer;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'testo',
                ),
                'required' => true,
                'trim' => true,
                'translation_domain' => 'admin',
            ))
			->add('components',EntityType::class, array(
                'class' => Component::class,
                'query_builder' => function (ComponentRepository $c) {
                    return $c->createQueryBuilder('c')
                        ->andWhere('c.is_enabled = :val')
                        ->setParameter('val', true)
                        ->orderBy('c.position', 'ASC');
                },
                'placeholder' => 'Choose a group',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control multiselect',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserGroup::class,
        ]);
    }
}
