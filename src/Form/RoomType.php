<?php

namespace App\Form;

use App\Entity\Room;
use App\enum\Difficulty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('difficulty', EnumType::class, [
                'class' => Difficulty::class,
                'choice_label' => fn (Difficulty $choice) => ucfirst($choice->value),
            ])
            ->add('number', TextType::class)
            ->add('component', FileType::class, [
                'label' => 'Challenge Component (HTML file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                    maxSize: '2M',
                    mimeTypes: ['text/html', 'text/plain'],
                    mimeTypesMessage: 'Please upload a valid HTML file',
                    )
                ]
            ])
            ;     
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}