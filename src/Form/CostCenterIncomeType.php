<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\CostCenter;
use Doctrine\ORM\Utility\PersisterHelper;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Extension_InitRuntimeInterface;

class CostCenterIncomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
        $builder
            ->add('code', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'code',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('title', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'title',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('is_enabled', CheckboxType::class, array(
                'attr' => array(
                    'class' => 'form-check-input-styled',
                    'checked' => 'checked',
                ),
                'required' => false,
            ))
            ->add('account', EntityType::class, array(
                'class' => Account::class,
                'placeholder' => 'Choose an account',
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CostCenter::class,
        ]);
    }
}
