<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname',TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('username',TextType::class, array(
                'attr' => array(
                    'class' => 'form-control text-danger font-weight-bold',
                    'readonly' => 'readonly',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('email',EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('phone',TelType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                ),
                'trim' => true,
                'required' => false,
            ))
            //->add('is_enabled')
            //->add('is_lock')
            //->add('userGroups')
            //->add('branch')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
