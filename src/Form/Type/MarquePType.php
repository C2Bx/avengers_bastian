<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\MotCles;
use App\Entity\MarquePage;


class MarquePType extends AbstractType{
    
    public function buildForm (FormBuilderInterface $builder, array $options){
        $builder
            ->add('Url', TextType::class)
            ->add('DateCreation', DateType::class)
            ->add('Commentaire', TextType::class)
            ->add('motCles', EntityType::class, [
                'class' => MotCles::class,
                'multiple'=>true,
                'expanded' => true,
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions (OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class' => MarquePage::class,
        ]);
    }
}