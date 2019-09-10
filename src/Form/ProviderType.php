<?php

namespace App\Form;

use App\Entity\Civility;
use App\Entity\Person;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;
use Twig_Extension_Debug;

class ProviderType extends AbstractType
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
            ->add('civility', EntityType::class, array(
                'class' => Civility::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('name',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'name',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('bornAt',DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
            ->add('passport',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'passport',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('idcardnumber',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'cardnumberid',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('job',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'job',
                ),
                'trim' => true,
                'required' => false,
            ))

            ->add('fixphone',  TelType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'fixphone',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('mobilephone',  TelType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'mobilephone',
                ),
                'trim' => true,
                'required' => false,
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
                'required' => false,//
            ))
            ->add('town',  TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'town',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('note',  TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'note',
                ),
                'trim' => true,
                'required' => false,
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
