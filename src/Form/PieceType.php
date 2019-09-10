<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Branch;
use App\Entity\Journal;
use App\Entity\Piece;
use App\Entity\Year;
use App\Repository\AccountRepository;
use App\Repository\YearRepository;
use Doctrine\ORM\Mapping\AssociationOverride;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\Binary\ConcatBinary;
use Twig_Profiler_Node_LeaveProfile;
use Twig_Sandbox_SecurityNotAllowedPropertyError;

class PieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('description', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'description',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('amount', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'amount',
                ),
                'trim' => true,
                'required' => true,
                'currency' => 'XAF',
            ))
            ->add('doingAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
                'data' => new \DateTime("now"),
            ))
            ->add('journal', EntityType::class, array(
                'class' => Journal::class,
                'placeholder' => 'Choose a journal',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('accountDb', EntityType::class, array(
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
            ->add('accountCr', EntityType::class, array(
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
            ->add('year', EntityType::class, array(
                'class' => Year::class,
                'query_builder' => function (YearRepository $y) {
                    return $y->createQueryBuilder('y')
                        ->andWhere('y.is_current = :val')
                        ->setParameter('val', true)
                        ->orderBy('y.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('branch', EntityType::class, array(
                'class' => Branch::class,
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
            'data_class' => Piece::class,
        ]);
    }
}
