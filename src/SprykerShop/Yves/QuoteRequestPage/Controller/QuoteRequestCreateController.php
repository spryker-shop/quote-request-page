<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestCreateController extends QuoteRequestAbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CREATED = 'quote_request_page.quote_request.created';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@QuoteRequestPage/views/quote-request-create/quote-request-create.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeCreateAction(Request $request)
    {
        $quoteRequestForm = $this->getFactory()
            ->getQuoteRequestForm()
            ->handleRequest($request);

        if ($quoteRequestForm->isSubmitted() && $quoteRequestForm->isValid()) {
            return $this->processQuoteRequestForm($quoteRequestForm);
        }

        return $this->getViewParameters($quoteRequestForm);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quoteRequestForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function processQuoteRequestForm(FormInterface $quoteRequestForm)
    {
        $quoteRequestResponseTransfer = $this->getFactory()
            ->createQuoteRequestHandler()
            ->createQuoteRequest($quoteRequestForm->getData());

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CREATED);

            return $this->redirectResponseInternal(QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_DETAILS, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestResponseTransfer->getQuoteRequest()->getQuoteRequestReference(),
            ]);
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        return $this->getViewParameters($quoteRequestForm);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $quoteRequestForm
     *
     * @return array
     */
    protected function getViewParameters(FormInterface $quoteRequestForm): array
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer */
        $quoteRequestTransfer = $quoteRequestForm->getData();

        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();
        $shipmentGroupTransfers = $this->getFactory()
            ->createShipmentGrouper()
            ->groupItemsByShippingAddress($quoteTransfer);

        $itemExtractor = $this->getFactory()->createItemExtractor();

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
            'shipmentGroups' => $shipmentGroupTransfers,
            'itemsWithShipment' => $itemExtractor->extractItemsWithShipment($quoteTransfer),
            'itemsWithoutShipment' => $itemExtractor->extractItemsWithoutShipment($quoteTransfer),
            'shipmentExpenses' => $this->getFactory()->createExpenseExtractor()->extractShipmentExpenses($quoteTransfer),
        ];
    }
}
