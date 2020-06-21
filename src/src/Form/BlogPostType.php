<?php

namespace App\Form;

use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('content')
            //->add('file')
            ->add('file_input', FileType::class, [
                'label' => 'File',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2m',
                        //'mimeTypes' => [
                        //    'image/*',
                        //],
                        //'mimeTypesMessage' => 'Please upload a valid image document',
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => BlogCategory::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
