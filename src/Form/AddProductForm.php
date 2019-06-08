<?php


namespace App\Form;


use App\DTO\CategoryForm;
use App\DTO\ProductFormDTO;
use App\Entity\Brand;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProductForm extends AbstractType
{
    private $rep;

    /**
     * AddProductForm constructor.
     * @param $rep
     */
    public function __construct(CategoryRepository $rep)
    {
        $this->rep = $rep;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $this->rep->findAll();
        foreach ($categories as $key => $category) {
            if(!$category->getChildren()->isEmpty())
                unset($categories[$key]);
        }


        $builder
            ->add('name', TextType::class, [
                'label' => 'Название'
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Описание',
                'required' => false,
                'attr' => [
                    'class' => 'summernote',
                ]
            ])
            ->add('category', ChoiceType::class,[
                'choices' => $categories,
                'choice_label' => 'name',
            ])
            ->add('images', FileType::class,[
                'label' => 'Изображение',
                'required' => false,
                'multiple' => true,
            ])
            ->add('is_visible', ChoiceType::class,[
                'label' => 'Отображение',
                'choices'=>[
                    'Да' => true,
                    'Нет' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('is_available', ChoiceType::class,[
                'label' => 'Наличие',
                'choices'=>[
                    'Да' => true,
                    'Нет' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('special_offer', ChoiceType::class,[
                'label' => 'Хит продаж',
                'choices'=>[
                    'Да' => true,
                    'Нет' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('currency_name', ChoiceType::class,[
                'label' => 'Валюта',
                'choices'=>[
                    'UAH' => 'UAH',
                    'EUR' => 'EUR',
                    'USD' => 'USD',
                ],
            ])
            ->add('retail_price', TextType::class,[
                'label' => 'Розничная цена',
                'attr' => [
                    'step' => '0.001',
                    'pattern' => '[0-9]+([\.][0-9]+)?',
                    'min' => 0,
                ],
                'required' => true,
            ])
            ->add('wholesale_price', TextType::class,[
                'label' => 'Оптовая цена',
                'attr' => [
                    'step' => '0.001',
                    'pattern' => '[0-9]+([\.][0-9]+)?',
                    'min' => 0,
                ],
                'required' => false,
            ])
            ->add('product_unit', TextType::class, [
                'label' => 'Единица измерения',
                'required' => false,
            ])
            ->add('specification', HiddenType::class)
            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Добавить товар'])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductFormDTO::class,
        ));
    }
}