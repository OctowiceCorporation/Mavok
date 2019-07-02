<?php


namespace App\Form;


use App\DTO\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddReview extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ваше имя'
            ])
            ->add('review', TextareaType::class, [
                'label' => 'Отзыв',
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('pros', TextareaType::class,[
                'label' => 'Достоинства',
                'required' => false
            ])
            ->add('cons', TextareaType::class,[
                'label' => 'Недостатки',
                'required' => false
            ])
            ->add('save', SubmitType::class, ['label' => 'Оставить отзыв'])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Review::class,
        ));
    }
}