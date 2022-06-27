<?php

namespace App\Infrastructure\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;

class UploadImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFilename', FileType::class, [
                'label'       => 'Image:',
                'constraints' => [
                    new Image(['maxSize' => '2M'])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:'
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags:',
                'attr'  => ['placeholder' => 'Separate tags with commas']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Upload!'
            ]);
    }
}