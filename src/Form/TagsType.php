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

use App\Entity\Tags;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TagsType
 * @package App\Form
 */
class TagsType extends AbstractType
{
    /**
     * Tags form builder.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('articles');
    }

    /**
     * Options resolver for tags form.
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tags::class,
        ]);
    }
}
