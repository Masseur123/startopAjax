<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\CashDesk;
use App\Entity\Journal;
use App\Entity\User;
use App\Repository\AccountRepository;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\ArrayExpression;

class CashDeskType extends AbstractType
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
            ->add('is_main', CheckboxType::class, array(
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
            ->add('journal', EntityType::class, array(
                'class' => Journal::class,
                'placeholder' => 'Choose an account',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('operator', EntityType::class, array(
                'class' => User::class,
                'placeholder' => 'Choose an operator',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CashDesk::class,
        ]);
    }
}
