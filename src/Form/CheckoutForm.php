<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя'
            ])
            ->add('surname', TextType::class,[
                'label' => 'Фамилия'
            ])
            ->add('email', EmailType::class,[
                'label' => 'E-Mail'
            ])
            ->add('phone', TextType::class,[
                'label' => 'Телефон'
            ])
            ->add('type', ChoiceType::class,[
                'label' => 'Тип доставки',
                'choices' => [
                    'Самовывоз' => 1,
                    'Новая Почта' => 2,
                    'Другое' => 3
                ]
            ])
            ->add('data', HiddenType::class)
            ->add('comment', TextareaType::class,[
                'label' => 'Комментарий',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }
}