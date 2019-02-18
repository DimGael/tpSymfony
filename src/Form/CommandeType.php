<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Dish;
use App\Entity\TableRestaurant;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date de la commande',
                'required' => true,
            ])
            ->add('prixTotal', MoneyType::class, [
                'required' => true
            ])
            ->add('status',ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Prise' => 'prise',
                    'Préparée' => 'preparee',
                    'Servie' => 'servie',
                    'Payée' => 'payee'
                ]
            ])
            ->add('Utilisateur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'required' => true,
            ])
            ->add('TableRestaurant', EntityType::class, [
                'class' => TableRestaurant::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('Dishes', EntityType::class, [
                'class' => Dish::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
