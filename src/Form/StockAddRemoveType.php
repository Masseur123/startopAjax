<?php

namespace App\Form;

use App\Entity\Stock;
use App\Entity\Store;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\Asset\Context\NullContext;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Profiler_Dumper_Html;

class StockAddRemoveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('stockAt', DateType::class, array(
                'widget' => 'single_text',
            ))
            ->add('stockprice')
            ->add('store',EntityType::class, array(
            'class'=> Store::class,
            'placeholder' => 'Choose an option',
            'expanded' => false,
            'multiple' => false,
            'attr' => array(
                'class' => 'form-control select-search',
            ),
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
