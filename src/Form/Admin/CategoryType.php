<?php

namespace App\Form\Admin;

use App\Entity\Admin\Category;
use App\Entity\Admin\Color;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>"Nom : ",

            ])
            ->add('color',EntityType::class,[
                "class"=>Color::class,
                "choice_label"=>"name",
                "multiple"=>false,
                "required"=>true,
                "label"=> "couleur : "
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
