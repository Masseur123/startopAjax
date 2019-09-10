<?php

namespace App\Form;


use App\Entity\TransitHist;
use App\Entity\Transit;
use App\Entity\DocumentFile;
use App\Entity\Tax;
use App\Repository\TransitRepository;
use App\Repository\DocumentFileRepository;
use Doctrine\ORM\Mapping\AssociationOverride;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_NodeVisitor_SafeAnalysis;
use Twig_Sandbox_SecurityPolicyInterface;

class TransitHistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'amount',
                ),
                'trim' => true,
                'required' => true,
                'currency' => 'XAF',
            ))
            ->add('tax', EntityType::class, array(
                'class' => Tax::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('taxamount', MoneyType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'tax_amount',
                ),
                'trim' => true,
                'required' => false,
                'currency' => 'XAF',
            ))
            ->add('createdAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
                'data' => new \DateTime("now"),
            ))
            /*->add('is_valid')*/
            ->add('is_cash', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                    'checked' => 'checked',
                ),
                'required' => false,
            ))
            /*->add('payAt')*/
            ->add('description', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'description',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => false,
            ))
            /*->add('transit', EntityType::class, array(
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
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add( 'document', EntityType::class, array(
                'class' => DocumentFile::class,
                'query_builder' => function (DocumentFileRepository $d) {
                    return $d->createQueryBuilder('d')
                        ->andWhere('d.account = :val')
                        ->setParameter('val', notnull)
                        ->orderBy('d.id', 'DESC');
                },
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))*/;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TransitHist::class,
        ]);
    }
}
