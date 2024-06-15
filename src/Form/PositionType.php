<?php

// src/Form/PositionType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Position;

class PositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entryTime', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy.MM.dd HH:mm:ss',
                'html5' => false,
            ])
            ->add('brokerId')
            ->add('symbol')
            ->add('type')
            ->add('volume')
            ->add('entry')
            ->add('stopLoss')
            ->add('takeProfit')
            ->add('exitTime', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy.MM.dd HH:mm:ss',
                'html5' => false,
                'required' => false
            ])
            ->add('exit')
            ->add('commission')
            ->add('swap')
            ->add('profit')
            ->add('system')
            ->add('strategy')
            ->add('assetClass')
            ->add('grade')
            ->add('dividend');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Position::class,
        ]);
    }
}
