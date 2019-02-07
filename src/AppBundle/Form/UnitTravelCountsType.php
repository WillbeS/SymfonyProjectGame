<?php

namespace AppBundle\Form;

use AppBundle\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class UnitTravelCountsType extends AbstractType
{
    const MAX_TRAVEL_COUNT = 100000;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $units = $options['units'];

        /** @var Unit $unit */
        foreach ($units as $unit) {
            $builder
                ->add($unit->getId(), IntegerType::class, [
                    'label' => false,
                    'constraints' => [
                        new NotBlank(),
                        new Type('integer'),
                        new Range(['min' => 0, 'max' => $unit->getIddle()]),
                    ],
                    'data' => 0,
                ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'units' => []
        ]);
    }
}
