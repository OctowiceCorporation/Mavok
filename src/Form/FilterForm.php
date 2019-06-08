<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET');
        $filter = $options['filter'];
        foreach ($filter as $key => $item) {
            if(!empty($item['is_countable']) && $item['min'] != $item['max']){
                $builder->add($key, HiddenType::class,[
                    'label' => $item['name'].', '.$item['is_countable'],
                    'attr' => [
                        'class' => 'text-slider',
                        'min' => $item['min'],
                        'max' => $item['max'],
                    ]
                    ]);
            }
            else{
                $builder->add($key, ChoiceType::class,[
                    'label' => $item['name'],
                    'choices' => $item['values'],
                    'expanded' => true,
                    'multiple' => true,
                ]);
            }
        }
        $builder->add('save', SubmitType::class, ['label'=>'Отфильтровать'])->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'filter' => null,
        ));
    }

}