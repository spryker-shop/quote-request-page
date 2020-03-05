<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class CheckoutController extends \SprykerShop\Yves\ShopApplication\Controller\AbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';
    protected const ROUTE_CHECKOUT = 'cart';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_EDIT
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT = 'quote-request/edit';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    public function saveAction()
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if (!$quoteTransfer->getQuoteRequestReference()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS);

            return $this->redirectResponseInternal(static::ROUTE_CHECKOUT);
        }

        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException("Only company users are allowed to access this page");
        }

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteTransfer->getQuoteRequestReference())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage('Quote request not found');

            return $this->redirectResponseInternal(static::ROUTE_CHECKOUT);
        }

        $quoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();
        $quoteRequestTransfer->getLatestVersion()->setQuote($quoteTransfer);

        $quoteRequestResponseTransfer = $this->getFactory()->getQuoteRequestClient()
            ->updateQuoteRequest($quoteRequestTransfer);
        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage('Quote Saved');

            $this->reloadQuoteForCustomer();

            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_EDIT, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestResponseTransfer->getQuoteRequest()->getQuoteRequestReference(),
            ]);
        }

        return $this->redirectResponseInternal(static::ROUTE_CHECKOUT);
    }

    /**
     * @return void
     */
    protected function reloadQuoteForCustomer(): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            return;
        }

        $this->getFactory()
            ->getPersistentCartClient()
            ->reloadQuoteForCustomer($customerTransfer);
    }
}
