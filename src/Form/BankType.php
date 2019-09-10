<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Bank;
use App\Entity\Journal;
use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\ArrayExpression;
use Twig_Sandbox_SecurityNotAllowedMethodError;

class BankType extends AbstractType
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
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('location', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'location',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'email',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('fixphone', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'fixphone',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('mobilephone', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'mobilephone',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('pobox', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pobox',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('taxpayernumber', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'taxpayernumber',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('businessnumber', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'businessnumber',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('code_swift', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code_swift',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('code_ibam', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code_ibam',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('code_bank', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code_bank',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('code_branch', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code_branch',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('account_number', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'account_number',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('code_rib', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code_rib',
                ),
                'trim' => true,
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
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bank::class,
        ]);
    }
}
