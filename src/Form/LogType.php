<?php

namespace App\Form;

use App\Entity\LogProcurement;
use App\Entity\Log;
use App\Entity\Wood;
use App\Entity\Transit;
use App\Repository\TransitRepository;

use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\AssignNameExpression;
use Twig_Extension_Escaper;
use Twig_Node;
use Twig_Sandbox_SecurityPolicy;
use Twig_TokenParser_Flush;

class LogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'log number',
                ),
                'trim' => true,
                'required' => true,
            ))
			->add('internum', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Internal log number',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('length', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'length',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('gb', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'gb',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('pb', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pb',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('dm', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'dm',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('volume', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'volume',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('wood', EntityType::class, array(
                'class' => Wood::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('logProcurement', EntityType::class, array(
                'class' => LogProcurement::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
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
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Log::class,
        ]);
    }
}
