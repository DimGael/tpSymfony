<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('calories', ChoiceType::class, [
                'choice_loader' => new CallbackChoiceLoader(function(){
                    $calories = array();

                    for($i = 10; $i<300; $i+=10)
                        $calories[$i] = $i;

                    return $calories;
                })
            ])
            ->add('price', MoneyType::class, [
            ])
            ->add('image', TextType::class, [
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('sticky', CheckboxType::class, [
                'required' => false
            ])

            ->add('Category', EntityType::class, [
                "class" => Category::class,
                "choice_label" => "name",
                'multiple' => false,
                'required' => true
            ])

            ->add('User',  EntityType::class, [
                "class" => User::class,
                "choice_label" => "username",
                'multiple' => false,
                'required' => false
            ])

            ->add('allergens',  EntityType::class, [
                "class" => Allergen::class,
                "choice_label" => "name",
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dish::class,
        ]);
    }
}
