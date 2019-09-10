<?php

namespace App\Form;

use App\Entity\RoleLine;
use App\Entity\Role;
use App\Repository\RoleRepository;

use Facebook\WebDriver\Exception\NullPointerException;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\Binary\RangeBinary;

class RoleLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$value = $options['menuId'];
		
        $builder
			->add('role',EntityType::class, array(
                'class' => Role::class,
                'query_builder' => function (RoleRepository $m) use ($value) {
                    return $m->createQueryBuilder('r')
                        ->andWhere('r.is_enabled = :isenable')
						->andWhere('r.menu = :value')
                        ->setParameter('isenable', true)
						->setParameter('value', $value)
                        ->orderBy('r.position', 'ASC');
                },
                'placeholder' => 'assign_roles',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
				'translation_domain' => 'admin',
                'attr' => array(
                    'class' => 'form-control multiselect',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoleLine::class,
        ]);
		$resolver->setRequired(['menuId']);
    }
}
