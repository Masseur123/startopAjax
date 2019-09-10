<?php

namespace App\Form;

use App\Entity\HousingType;
use App\Entity\Branch;

use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_NodeVisitor_Escaper;

class HousingTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', ColorType::class, array(
                'attr' => array(
                    'type' => 'color',
                    'class' => 'form-control',
                ),
            ))
            ->add('min_stay', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('maxclient', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('max_adults', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('max_children', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('max_babies', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('state', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                    'checked' => 'is_open',
                ),
            ))
            /*->add('is_enabled', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                    'checked' => 'is_open',
                ),
            ))*/
            ->add('ordering', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'ordering',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('smoking', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
            ->add('is_private', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                ),
            ))
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HousingType::class,
        ]);
    }
}
