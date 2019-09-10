<?php

namespace App\Form;

use App\Entity\LogRouting;
use App\Entity\Park;
use App\Entity\Port;
use App\Entity\Log;
use App\Repository\LogRepository;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\Binary\MulBinary;
use Twig_Sandbox_SecurityNotAllowedFunctionError;
use Twig_Sandbox_SecurityNotAllowedTagError;
use Twig_Sandbox_SecurityPolicy;
use Twig_Token;

class LogRoutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logreferences', EntityType::class, array(
                'class' => Log::class,
                'query_builder' => function (LogRepository $l) {
                    return $l->createQueryBuilder('l')
                        ->andWhere('l.reference is not null')
                        ->orderBy('l.id', 'DESC');
                },
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => true,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control multiselect',
                ),
            ))
            ->add('consignment_note', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'consignment note',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('routingAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
            ->add('source', EntityType::class, array(
                'class' => Park::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('destination', EntityType::class, array(
                'class' => Port::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LogRouting::class,
        ]);
    }
}
