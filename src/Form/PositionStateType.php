<?php

namespace App\Form;

use App\Entity\PositionState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entryTime')
            ->add('brokerId')
            ->add('symbol')
            ->add('type')
            ->add('volume')
            ->add('entry')
            ->add('stopLoss')
            ->add('exit')
            ->add('commission')
            ->add('dividend')
            ->add('swap')
            ->add('profit')
            ->add('system')
            ->add('strategy')
            ->add('assetClass')
            ->add('grade')
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Opened' => PositionState::STATE_OPENED,
                    'Modified' => PositionState::STATE_MODIFIED,
                    'Closed' => PositionState::STATE_CLOSED,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PositionState::class,
        ]);
    }
}
