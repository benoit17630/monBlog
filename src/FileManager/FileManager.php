<?php


namespace App\FileManager;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileManager
{
    private $slugger;
    private $parameterBag;
    public function __construct(SluggerInterface $slugger, ParameterBagInterface $parameterBag)
    {

        $this->slugger= $slugger;
        $this->parameterBag =$parameterBag;
    }
    public function saveFile($article, $form)
    {
        //  je récupère recupere mon image
        $file = $form->get('image')->getData();

        //si $imagefile
        if ($file){

            //j indique le path ou l image devra etre stocker pour cela je doit faire une modification dans config service.yaml
            // et metre dedans en fesant attention a l indentation
            //  parameters:
            //    images_directory: '%kernel.project_dir%/public/uploads/articles'
            $originalFilename = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

            //je slugify le nom de l image
            $safeFilename= $this->slugger->slug($originalFilename);

            //je recupere le nom slugify puis lui donne un unigid pour etre sur que l image est un nom unique
            $newFilename = $safeFilename.'_'.uniqid().'.'.$file->guessextension();

            //ici j enregistre l image dans le projet
            $file->move(

                $this->parameterBag->get('images_directory'),

                $newFilename

            );

            //ici je set l image avec le nouveau nom pour la BDD
            $article->setImage($newFilename);
        }
    }
}