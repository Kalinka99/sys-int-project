<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Uzupełnij to pole.'
                        ]),
                    new Length([
                        'min' => 1,
                        'max' => 255
                    ]),
                ]

            ])
            ->add('mainText', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Uzupełnij to pole.'
                    ]),
                    new Length([
                        'min' => 1,
                    ]),
                ]

            ])
            ->add('categories', ChoiceType::class, [
                'choices'  => $options['categories']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'categories' => []
        ]);
    }
}
