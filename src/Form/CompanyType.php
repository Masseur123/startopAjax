<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\TypePerson;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('cigle',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'cigle',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('name',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('fixphone',  NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'fix',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('mobilephone',  NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'mobile',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('email',  EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'email',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('pobox',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pobox',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('address',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'address',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('town',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'town',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('type', EntityType::class, array(
                    'class' => TypePerson::class,
                    'placeholder' => 'Choose an option',
                    'choice_label' => 'code',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control select-search',
                    ),
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
