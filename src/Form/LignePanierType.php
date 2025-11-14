<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\LignePanier;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LignePanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etudiant', EntityType::class, [
                'class' => Etudiant::class,
                'choice_label' => function (Etudiant $etudiant) {
                    return $etudiant->getPrenom() . ' ' . $etudiant->getNom() . ' (' . $etudiant->getEmail() . ')';
                },
                'label' => 'Étudiant',
                'attr' => ['class' => 'form-control']
            ])
            ->add('plat', EntityType::class, [
                'class' => Plat::class,
                'choice_label' => function (Plat $plat) {
                    return $plat->getNom() . ' - ' . $plat->getPrix() . '€';
                },
                'label' => 'Plat',
                'attr' => ['class' => 'form-control']
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['class' => 'form-control', 'min' => 1]
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LignePanier::class,
        ]);
    }
}
