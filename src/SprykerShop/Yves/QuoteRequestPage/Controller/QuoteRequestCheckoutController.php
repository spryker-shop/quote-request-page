<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestCheckoutController extends QuoteRequestAbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART_SUCCESS = 'quote_request.validation.converted_to_cart.success';

    public function convertToCartAction(string $quoteRequestReference): RedirectResponse
    {
        $response = $this->executeConvertToCartActionAction($quoteRequestReference);

        return $response;
    }

    protected function executeConvertToCartActionAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);

        $quoteResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        $this->handleQuoteResponseErrors($quoteResponseTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->redirectResponseInternal(QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_DETAILS, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
            ]);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART_SUCCESS);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    protected function handleQuoteResponseErrors(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        foreach ($quoteResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessage());
        }
    }
}
