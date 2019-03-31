<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\QuoteRequestPage;

use DateTime;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteRequestPageExtension\Dependency\Plugin\QuoteRequestFormMetadataFieldPluginInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class DeliveryDateMetadataFieldPlugin extends AbstractPlugin implements QuoteRequestFormMetadataFieldPluginInterface
{
    protected const FIELD_METADATA_DELIVERY_DATE = 'delivery_date';
    protected const LABEL_METADATA_DELIVERY_DATE = 'quote_request_page.quote_request.metadata.label.delivery_date';
    protected const GLOSSARY_KEY_DATE_VIOLATION = 'quote_request_page.quote_request.violations.invalid_date';

    /**
     * {@inheritdoc}
     * - Adds delivery date to metadata for QuoteRequest form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(static::FIELD_METADATA_DELIVERY_DATE, DateType::class, [
            'label' => static::LABEL_METADATA_DELIVERY_DATE,
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
            'constraints' => [
                new Callback([
                    'callback' => function ($date, ExecutionContextInterface $context) {
                        if (!$date) {
                            return;
                        }

                        if (new DateTime($date) < new DateTime()) {
                            $context->addViolation(static::GLOSSARY_KEY_DATE_VIOLATION);
                        }
                    },
                ]),
            ],
        ]);

        $builder->get(static::FIELD_METADATA_DELIVERY_DATE)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $builder;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    $value = new DateTime($value);
                }
                return $value;
            },
            function ($value) {
                if ($value instanceof DateTime) {
                    $value = $value->format('Y-m-d');
                }
                return $value;
            }
        );
    }
}
