<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Mapping\ClassMetadata;


class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('mainText', TextareaType::class)
            ->add('categories', ChoiceType::class, [
                'choices'  => $options['categories'],
                'required' => true,
            ])
            ->add('tags', ChoiceType::class, [
                'choices' => $options['tags'],
                'required' => false,
            ]);
        if($options['removeTags']){
            $builder
                ->add('tags', ChoiceType::class);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'categories' => [],
            'tags' => [],
            'tagsToRemove' => [],
            'removeTags' => false
        ]);
    }
}
