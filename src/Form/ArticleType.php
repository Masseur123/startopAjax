<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Family;
use App\Entity\Itemtype;
use App\Entity\Unity;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Monolog\Handler\GelfMockMessagePublisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, array(
                'class' => Itemtype::class,
                'choice_label'=> 'name',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choose an option',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('unity', EntityType::class, array(
                'class' => Unity::class,
                'choice_label'=> 'name',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choose an option',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('family', EntityType::class, array(
                'class' => Family::class,
                'choice_label'=> 'name',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choose an option',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('account',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'account',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('account_var',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'account variable',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('account_pur',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'account purchase',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('account_sale',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'account sale',
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
                'required' => true,
            ))
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('pua', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pua',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('puv', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'puv',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('puvmin', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'puv_min',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('puvmax', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'puv_max',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('weight', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'weight',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('dimensions', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'dimensions',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('symbol', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'business number',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('is_storable', CheckboxType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
            //->add('stock')
            ->add('stockAlert', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'stock_alert',
                ),
                'trim' => true,
                'required' => false,
            ))
            //->add('reserv', TextType::class, array('required' => false))
            ->add('is_lost', CheckboxType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
            ->add('lostAt', DateType::class, array(
                'required' => false,
                'widget' => 'single_text',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
