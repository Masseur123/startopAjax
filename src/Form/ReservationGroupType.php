<?php

namespace App\Form;

use App\Entity\ReservationGroup;

use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Compiler;

class ReservationGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, array(
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'name',
            ),
            'trim' => true,
            'required' => true,
        ))
            ->add('color', ColorType::class, array(
                'attr' => array(
                    'type' => 'color',
                    'class' => 'form-control',
                ),
            ))
            ->add('phone', TelType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'phone',
                ),
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'email',
                ),
            ))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'description',
                ),
                'trim' => true,
                'required' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReservationGroup::class,
        ]);
    }
}
