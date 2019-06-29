<?php


namespace App\Form;


use App\DTO\BlogFormDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPostForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Заголовок'
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Текст',
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Фото'
            ])
            ->add('is_visible', CheckboxType::class,[
                'label' => 'Отображать',
                'required' => false
            ])
            ->add('save', SubmitType::class, ['label' => 'Сохранить изменения'])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BlogFormDTO::class
        ));
    }
}