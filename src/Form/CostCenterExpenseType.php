<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\CostCenter;
use App\Repository\AccountRepository;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\ArrayExpression;
use Twig_Extension_InitRuntimeInterface;

class CostCenterExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
        $builder
            ->add('code', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code',
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
            ->add('is_enabled', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                    'checked' => 'checked',
                ),
                'required' => false,
            ))
            ->add('has_control', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
                'required' => false,
            ))
            ->add('account', EntityType::class, array(
                'class' => Account::class,
                'query_builder' => function (AccountRepository $a) {
                    return $a->createQueryBuilder('a')
                        ->andWhere('a.is_final = :val1')
                        ->andWhere('a.is_lock = :val2')
                        ->setParameter('val1', true)
                        ->setParameter('val2', false)
                        ->orderBy('a.number', 'DESC');
                },
                'placeholder' => 'Choose an account',
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
            'data_class' => CostCenter::class,
        ]);
    }
}
