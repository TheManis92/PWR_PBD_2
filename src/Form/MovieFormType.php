<?php

namespace App\Form;

use App\Document\EmbeddedMovie;
use App\Document\Genre;
use App\Document\Movie;
use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Types\FloatType;
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
        $builder->add('rating', NumberType::class);


        $builder->add('genres', DocumentType::class, array(
            'class' => Genre::class,
            'choice_label' => 'name',
            'choice_value' => 'name',
            'expanded' => false,
            'multiple' => true,
            'mapped' => false
        ));

        $builder->add('plot', TextareaType::class);
        $builder->add('lang');
        $builder->add('director');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EmbeddedMovie::class,
        ));
    }
}