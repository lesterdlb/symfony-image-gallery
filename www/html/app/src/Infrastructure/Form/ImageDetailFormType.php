<?php

declare(strict_types=1);

namespace App\Infrastructure\Form;

use App\Application\Image\Query\GetImageByIdResponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageDetailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Description:'
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags:',
                'attr'  => ['placeholder' => 'Separate tags with commas']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Update!'
            ]);

        $builder->get('tags')
                ->addModelTransformer(
                    new CallbackTransformer(
                        fn($tagsAsArray) => implode(',', $tagsAsArray),
                        fn($tagsAsString) => explode(',', $tagsAsString)
                    )
                );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GetImageByIdResponse::class,
        ]);
    }
}
