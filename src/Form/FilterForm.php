<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $filter = $options['filter'];
        foreach ($filter as $key =>  $item) {
            if(!empty($item['is_countable'])){
                $builder->add($key, ChoiceType::class,[
                    'label' => $item['name'].' . '.$item['is_countable'],
                    'choices' => $item['values'],
                    'expanded' => true,
                    'multiple' => true,
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