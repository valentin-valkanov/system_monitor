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
            ->add('time')
            ->add('symbol')
            ->add('type')
            ->add('volume')
            ->add('priceLevel')
            ->add('stopLoss')
            ->add('commission')
            ->add('dividend')
            ->add('swap')
            ->add('profit')
            ->add('system')
            ->add('strategy')
            ->add('assetClass')
            ->add('grade', ChoiceType::class, [
                'choices' => [
                    'None' => PositionState::GRADE_NONE,
                    "A" => PositionState::GRADE_A,
                    'B'=> PositionState::GRADE_B,
                    'C' => PositionState::GRADE_C
                ]
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Opened' => PositionState::STATE_OPENED,
                    'Partially_Closed' => PositionState::STATE_PARTIALLY_CLOSED,
                    'Scale_In' => PositionState::STATE_SCALE_IN,
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
