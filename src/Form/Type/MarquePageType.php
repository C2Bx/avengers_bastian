<?php

namespace App\Form\Type;

use App\Entity\MotsCles;
use App\Entity\MarquePage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MarquePageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'URL',
            ])
            ->add('commentaire', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Commentaire',
            ])
            ->add('date_creation', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de Création',
            ])
            ->add('motsCles', EntityType::class, [
                'class' => MotsCles::class,
                'choice_label' => 'motCle',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Mots-clés',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MarquePage::class,
        ]);
    }
}
