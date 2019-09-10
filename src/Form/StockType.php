<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Stock;
use App\Entity\Store;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Asset\Context\NullContext;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Profiler_Dumper_Html;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'designation',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('store',EntityType::class, array(
                'class' => Store::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'designation',
                'attr' => array(
                'class' => 'form-control select-search',
                ),
            ))
            ->add('article', EntityType::class, array(
                'class' => Article::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name',
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
