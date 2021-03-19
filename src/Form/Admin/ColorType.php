<?php

namespace App\Form\Admin;

use App\Entity\Admin\Color;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>"Nom : ",
                "attr"=>[
                    'class'=>"col-6"
                ]
            ])
            ->add('color', TextType::class,[
                'label'=>"couleur en hexadecimal : ",
                "attr"=>[
                    'class'=>"col-6"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Color::class,
        ]);
    }
}
