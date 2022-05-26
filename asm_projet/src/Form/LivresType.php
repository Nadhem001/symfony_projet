<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use app\Entity\Livres;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Auteurs;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LivresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date_sortie',DateType::class, [
                'label' => 'Date de sortie',
                'attr' => ['class' => 'input-group date' ],
                'widget' => 'single_text',
            ])
            ->add('date_ajout',DateType::class, [
                'label' => "Date d'ajout",
                'attr' => ['class' => 'input-group date' ],
                'widget' => 'single_text',
            ])
            ->add('path_cover', FileType::class, [
                'label' => 'Cover de livre',
                'attr' => ['class' => 'form-control form-control-lg input_file'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ])
                ],
            ])

            ->add('path_livre', FileType::class, [
                'label' => 'livre',
                'attr' => ['class' => 'form-control form-control-lg input_file'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document PDF valide',
                    ])
                ],
            ])
            ->add('disp',null,[
                'label' => 'Disponible:'
            ])
            ->add('auteurs', EntityType::class, [
                'class' => Auteurs::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Choisissez ...'
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livres::class,
        ]);
    }
}
