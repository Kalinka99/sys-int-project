<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authorUsername', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Uzupełnij to pole.'
                    ]),
                ]
            ])
            ->add('authorEmail', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Uzupełnij to pole.'
                    ]),
                ]
            ])
            ->add('mainText', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Uzupełnij to pole.'
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
