<?php

namespace App\Form;

use App\Entity\TransitHist;
use App\Entity\Transit;
use App\Entity\DocumentFile;

use App\Repository\TransitRepository;

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
use Twig\Node\Expression\AssignNameExpression;
use Twig_Extension_Escaper;
use Twig_NodeVisitor_SafeAnalysis;
use Twig_TokenParser_Set;

class TransitCashExpensePaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
                'disabled' => true,
            ))
            ->add('taxamount', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
                'disabled' => true,
            ))
            /*->add('is_valid')
            ->add('is_cash')*/
            ->add('payAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
                'data' => new \DateTime("now"),
                'disabled' => false,
            ))
            ->add('description', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
                'disabled' => true,
            ))
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => false,
                'disabled' => false,
            ))
            ->add('transit', EntityType::class, array(
                'class' => Transit::class,
                'query_builder' => function (TransitRepository $t) {
                    return $t->createQueryBuilder('t')
                        ->andWhere('t.is_open = :val')
                        ->setParameter('val', true)
                        ->orderBy('t.id', 'DESC');
                },
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'disabled' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('document', EntityType::class, array(
                'class' => DocumentFile::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'disabled' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            /*->add('tax')
            ->add('cashier')
            ->add('bank')
            ->add('year')
            ->add('paymentMethod')*/;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TransitHist::class,
        ]);
    }
}
