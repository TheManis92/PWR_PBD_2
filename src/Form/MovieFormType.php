<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Genre;
use App\Entity\Lang;
use App\Entity\Movie;
use App\Entity\MovieSubmission;
use App\Entity\Person;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('year');
        $builder->add('genres', EntityType::class, array(
            'class' => Genre::class,
            'choice_label' => 'name',
            'choice_value' => 'id',
            'expanded' => false,
            'multiple' => true,
            'mapped' => false
        ));
        $builder->add('plot', TextareaType::class);
        $builder->add('lang', EntityType::class, array(
            'class' => Lang::class,
            'choice_label' => 'name',
            'choice_value' => 'id',
            'expanded' => false,
            'multiple' => true,
            'mapped' => false
        ));
        $builder->add('countries', EntityType::class, array(
            'class' => Country::class,
            'choice_label' => 'name',
            'choice_value' => 'id',
            'expanded' => false,
            'multiple' => true,
            'mapped' => false
        ));
        $builder->add('cast', EntityType::class, array(
            'class' => Person::class,
            'choice_label' => 'surname',
            'choice_value' => 'id',
            'expanded' => false,
            'multiple' => true,
            'mapped' => false
        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MovieSubmission::class,
        ));
    }
}