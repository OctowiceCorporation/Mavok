<?php


namespace App\Form;


use App\DTO\CommonInfoDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommonInfoForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minimum', IntegerType::class, [
                'label' => 'Минимальная сума заказа'
            ])
            ->add('usd', TextType::class,[
                'label' => 'Общая стоимость доллара, формат: "26.34"',
                'attr' => [
                    'step' => '0.001',
                    'pattern' => '[0-9]+([\.][0-9]+)?',
                    'min' => 0,
                ],
            ])
            ->add('eur', TextType::class,[
                'label' => 'Общая стоимость евро, формат: "26.34"',
                'attr' => [
                    'step' => '0.001',
                    'pattern' => '[0-9]+([\.][0-9]+)?',
                    'min' => 0,
                ],
            ])
            ->add('number', TextType::class,[
                'label' => 'Контактный номер телефона'
            ])
            ->add('address', TextType::class,[
                'label' => 'Адрес самовывоза'
            ])
            ->add('name', TextType::class,[
                'label' => 'Имя фамилия'
            ])
            ->add('about', TextareaType::class,[
                'label' => 'Текст на странице о нас',
                'attr'=>[
                    'class' => 'summernote',
                ]
            ])
            ->add('footerAbout', TextareaType::class,[
                'label' => 'Текст "О нас" в футере',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Соханить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CommonInfoDTO::class,
        ));
    }
}