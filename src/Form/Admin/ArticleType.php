<?php

namespace App\Form\Admin;

use App\Entity\Admin\Article;
use App\Entity\Admin\Category;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>"nom",


            ])
            ->add('category', EntityType::class,[
                "class"=> Category::class,
                "multiple"=>false,
                "choice_label"=>"name",

            ])
            ->add('content')
            ->add('isPublished')
            ->add('createdAt',DateType::class,[
                'widget'=>'single_text',
                'label'=> "Date de creation"]
            )
            ->add('image')

            ->add('category', EntityType::class,[
                "class"=> Category::class,
                "multiple"=>false,
                "choice_label"=>"name"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
