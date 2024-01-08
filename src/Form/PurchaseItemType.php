<?php

namespace App\Form;

use App\DTO\PurchaseItemDTO;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class PurchaseItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, [
                'label' => 'Quantidade',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Insira a quantidade'
                    ]),
                    new Positive(null, 'Insira a quantidade')
                ]
            ])
            ->add('product', EntityType::class, [
                'label' => 'Produto',
                'class' => Product::class,
                'choice_label' => function (Product $product): string {
                    return $product;
                }
            ])
            ->add('purchase', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Adicionar',
                'attr' => [
                    'class' => 'btn-secondary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PurchaseItemDTO::class,
        ]);
    }
}
