<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', null, ['label' => 'Nom de la chambre :'])
            ->add('description', null, ['label' => 'Description :'])
            ->add('capacity', null, ['label' => 'Capacité :'])
            ->add('superficy', null, ['label' => 'Superficie :'])
            ->add('price', null, ['label' => 'Prix (/nuit):'])
            ->add('address', null, ['label' => 'Adresse :'])
            ->add('regions', null, ['label' => 'Région :'])
            ->add('imageName', TextType::class,  ['disabled' => true, 'label' => "Nom de l'image :"])
            ->add('imageFile', VichImageType::class, ['required' => false, 'label' => 'Image à mettre en ligne :'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
