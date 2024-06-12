<?php

namespace App\Form;

use App\Entity\PositionState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entryTime', null, [
                'widget' => 'single_text',
            ])
            ->add('brokerId')
            ->add('symbol')
            ->add('type')
            ->add('volume')
            ->add('entry')
            ->add('stopLoss')
            ->add('takeProfit')
            ->add('exitTime', null, [
                'widget' => 'single_text',
            ])
            ->add('exit')
            ->add('commission')
            ->add('swap')
            ->add('profit')
            ->add('system')
            ->add('strategy')
            ->add('assetClass')
            ->add('grade')
            ->add('dividend')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PositionState::class,
        ]);
    }
}
