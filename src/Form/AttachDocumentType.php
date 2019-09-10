<?php

namespace App\Form;

use App\Entity\AttachDocument;
use App\Entity\DocumentFile;
use App\Entity\Transit;

use Doctrine\ORM\Mapping\AttributeOverride;
use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Extension_Escaper;
use Twig_TokenParser_Import;
use Twig_TokenParser_Set;

class AttachDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fileupload', FileType::class, array(
                'label' => 'Document',
                'required' => true,
                'data_class' => null,
                'attr' => array(
                    'class' => 'form-input-styled',
                ),
            ))
            ->add('transit', EntityType::class, array(
                'class' => Transit::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('document', EntityType::class, array(
                'class' => DocumentFile::class,
                'placeholder' => 'Choose an option',
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
            'data_class' => AttachDocument::class,
        ]);
    }
}
