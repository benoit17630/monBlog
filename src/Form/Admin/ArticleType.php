<?php

namespace App\Form\Admin;

use App\Entity\Admin\Article;
use App\Entity\Admin\Category;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    // classe générée avec la commande make:form
    // elle contient le gabarit de formulaire pour une entité donnée
    // la méthode buildForm permet d'ajouter  des champs (reliés aux propriétés)
    // de l'entité Article
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            //je dit que s est un TextType ensuite je change le label
            ->add('name',TextType::class,
                [
                    'label'=>"nom",
                    "attr"=>[
                        "class"=>'test'
                    ]
                ]
            )

            //je cree le choix des category dans mon formulaire grace a EntityType::class
            ->add('category', EntityType::class,
                [
                    //je dit dans quelle entity le choix est
                    "class"=> Category::class,
                    "multiple"=>false,
                    // je choisi la collone qui va etre afficher
                    "choice_label"=>"name",
                    //je renome en fr car mes utilisateur seront francais
                    "label"=> "categories"

                ]
            )
            ->add('content' , TextareaType::class,
                [
                    "label"=> "Contenu"
                ]
            )
            ->add('isPublished', CheckboxType::class,
                [
                    "label"=>"publier ?",
                    "required"=>false
                ]
            )
            ->add('createdAt',DateType::class,
                [
                    'widget'=>'single_text',
                    'label'=> "Date de creation"
                ]
            )

            //pour la propriete image je dit que s est un FileType et que mapped a false sinon
            // j ai une erreur pour l update d un article
            ->add('image', FileType::class,
                [
                    "mapped"=> false, //
                    "required"=>false, // pour pas a avoir a remetre l image a chaque fois
                    "constraints"=>[//je definie les contrainte sur le type de fichier a recuperer
                        new File([
                            'maxSize'=>"5024k",
                            "mimeTypes"=>[
                                "image/jpg",
                                "image/jpeg",
                                "image/gif",
                                "image/png"
                            ]
                        ])

                    ]

                ]
            );
    }

    //cette metode relie le formulaire a l entity
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
