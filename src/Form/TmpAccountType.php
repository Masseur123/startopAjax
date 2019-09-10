<?php

namespace App\Form;

use App\Entity\TmpAccount;


use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Parser;

class TmpAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number',TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'number',
                ),
                'trim' => true,
                'required' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TmpAccount::class,
        ]);
    }
}
