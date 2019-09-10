<?php

namespace App\Form;

use App\Entity\Provider;
use App\Entity\Quotation;
use App\Entity\SupplyRequest;
use App\Repository\SupplyRequestRepository;
use App\Repository\YearRepository;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Extension\EscaperExtension;
use Twig_TokenParser_Filter;

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplyRequest', EntityType::class, array(
                'class' => SupplyRequest::class,
                'query_builder' => function (SupplyRequestRepository $s) {
                    return $s->createQueryBuilder('s')
                        ->andWhere('s.is_valid = :val')
                        ->setParameter('val', true)
                        ->orderBy('s.id', 'DESC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'reference',
                'placeholder' => 'Choose a supply',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'reference',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('date',DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
            ->add('provider', EntityType::class, array(
                'class' => Provider::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label'=> 'person.name',
                'placeholder' => 'Choose a provider',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'trim' => true,
                'required' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quotation::class,
        ]);
    }
}
