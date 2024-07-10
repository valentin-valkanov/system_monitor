<?php

namespace App\Form;

use App\Entity\PositionState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Validator\Constraints\NotBlank;

class PositionStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('time', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy.MM.dd HH:mm:ss',
            ])
            ->add('symbol', TextType::class)
            ->add('type', TextType::class)
            ->add('volume', NumberType::class)
            ->add('priceLevel', NumberType::class)
            ->add('stopLoss', NumberType::class)
            ->add('commission', NumberType::class)
            ->add('dividend', NumberType::class)
            ->add('swap', NumberType::class)
            ->add('profit', NumberType::class)
            ->add('system', TextType::class)
            ->add('strategy', TextType::class)
            ->add('assetClass', TextType::class)
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
