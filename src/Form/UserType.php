<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\User;
use App\Entity\UserGroup;
use App\Repository\UserGroupRepository;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\SandboxNode;
use Twig_Extension_Optimizer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname',TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('username',TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('email',EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('userGroups',EntityType::class, array(
                'class' => UserGroup::class,
                'query_builder' => function (UserGroupRepository $g) {
                    return $g->createQueryBuilder('g')
                        ->andWhere('g.is_enabled = :val')
                        ->setParameter('val', true)
                        ->orderBy('g.id', 'DESC');
                },
                'placeholder' => 'Choose a group',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control multiselect',
                ),
            ))
            ->add('branch',EntityType::class, array(
                'class' => Branch::class,
                'placeholder' => 'Choose a branch',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
