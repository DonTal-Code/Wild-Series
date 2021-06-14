<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Doctrine\ORM\Cache\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['required' => false])
            ->add('number', null, ['required' => false])
            ->add('synopsis', null, ['required' => false])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'Selectionnez la Categorie',
                'mapped' => false,
                'required' => false

            ]);
        $builder->get('category')->addEventListener(FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addProgramField($form->getParent(), $form->getData());
            }
        );
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event)
            {
                $data = $event->getData();
                /* @var $season season*/
                $season = $data->getSeason();
                $form = $event->getForm();
                if ($season) {
                    $program = $season->getProgram();
                    $category = $program->getCategory();
                    $this->addProgramField($form, $category);
                    $this->addSeasonField($form, $program);
                 $form->get('category')->setData($category);
                    $form->get('program')->setData($program);
                } else {
                    $this->addProgramField($form, null);
                    $this->addSeasonField($form, null);
                }
            }
        );
    }

    private function addProgramField(FormInterface $form, ?Category $category)
    {

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'program',
            EntityType::class,
            null,
            [
                'class' => Program::class,
                'placeholder' => $category ? 'Selectionnez votre Program' : 'Selectionnez la Category',
                'mapped' => false,
                'required' => false,
                'auto_initialize' => false,
                'choices' => $category ? $category->getPrograms() : []

            ]

        );
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event){
               $form =$event->getForm();
               $this->addSeasonField($form->getParent(), $form->getData());
            }
        );
        $form->add($builder->getForm());
    }

private function addSeasonField(FormInterface $form, ?Program $program){
//pas de formbuilder puisque c'est le dernier niveau de notre form; si on veut rajouté encore un filtre, ou fait un form builder comme le addProgramField
        $form->add('season', EntityType::class, [
           'class'=> Season::class,
            'placeholder'=> $program ? 'Selectionnez votre saison ' : 'Selectionnez le program',
            'required' => false,
            'choices' => $program ? $program->getSeasons() : []
        ]);
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
