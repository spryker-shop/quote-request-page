<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig getConfig()
 */
class QuoteRequestForm extends AbstractType
{
    public const FIELD_METADATA = 'metadata';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteRequestTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMetadataFields($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addMetadataFields(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->getFactory()->getQuoteRequestFormMetadataFieldExpanderPlugins() as $shoppingItemFormTypeExpanderPlugin) {
            $builder = $shoppingItemFormTypeExpanderPlugin->expand($builder, $options);
        }

        $builder->add(static::FIELD_METADATA, TextType::class, [
            'label' => 'customer.account.shopping_list.overview.name',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
