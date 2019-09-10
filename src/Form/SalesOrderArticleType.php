<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\OrderArticle;
use App\Entity\SalesOrderArticle;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_TokenParser;

class SalesOrderArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity',IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'quantity',
                ),
                'trim' => true,
                'required' => true,
            ))
            //->add('ord')
            ->add('article', EntityType::class, array(
                'class' => Article::class,
                'expanded' => false,
                //'choice_label' => 'reference',
                'multiple' => false,
                'placeholder' => 'Choose an article',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('pu',IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pu',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('pt',IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pt',
                ),
                'trim' => true,
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SalesOrderArticle::class,
        ]);
    }
}
