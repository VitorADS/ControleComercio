<?php

namespace App\Form;

use App\Entity\PaymentType;
use App\Entity\Purchase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('buyerName', TextType::class, [
                'label' => 'Nome do cliente',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Insira o nome do cliente'
                    ])
                ]
            ])
            // ->add('paymentType', EntityType::class, [
            //     'class' => PaymentType::class,
            //     'label' => 'Meio de pagamento',
            //     'choice_label' => 'id',
            //     'multiple' => true,
            //     'choice_label' => function (PaymentType $paymentType): string {
            //         return $paymentType;
            //     }
            // ])
            ->add('submit', SubmitType::class, [
                'label' => $options['edit'] ? 'Salvar' : 'Criar',
                'attr' => [
                    'class' => 'btn-secondary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
            'edit' => false
        ]);
        $resolver->setAllowedTypes('edit', 'bool');
    }
}
