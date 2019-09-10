<?php

namespace App\Form;

use App\Entity\DocumentFile;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping\AssociationOverride;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\ArrayExpression;
use Twig_TokenParser_Set;

class DocumentFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('cost', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'expense',
                ),
                'trim' => true,
                'required' => false,
                'currency' => 'XAF',
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
            'data_class' => DocumentFile::class,
        ]);
    }
}
