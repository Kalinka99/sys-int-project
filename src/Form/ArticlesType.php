<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Kalina Muchowicz <https://github.com/Kalinka99>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class ArticlesType
 * @package App\Form
 */
class ArticlesType extends AbstractType
{
    /**
     * Articles form builder.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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

    /**
     * Options resolver for articles form.
     * @param OptionsResolver $resolver
     */
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
