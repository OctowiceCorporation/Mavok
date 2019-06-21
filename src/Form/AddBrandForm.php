<?php


namespace App\Form;


use App\Entity\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddBrandForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Название бренда'
            ])
            ->add('country', TextType::class, [
                'label' => 'Страна производитель',
                'required' => false,
            ])
            ->add('UsdValue', TextType::class,[
                'label' => 'Стоимость доллара, формат: "26.34"',
                'attr' => [
                    'step' => '0.001',
                    'pattern' => '[0-9]+([\.][0-9]+)?',
                    'min' => 0,
                ],
                'required' => false,
            ])
            ->add('EurValue', IntegerType::class,[
                'label' => 'Стоимость евро, формат: "26.34"',
                'attr' => [
                    'step' => '0.001',
                    'pattern' => '[0-9]+([\.][0-9]+)?',
                    'min' => 0,
                ],
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Добавить производителя'])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Brand::class,
        ));
    }
}