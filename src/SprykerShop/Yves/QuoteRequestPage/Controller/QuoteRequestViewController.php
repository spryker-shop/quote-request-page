<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestViewController extends QuoteRequestAbstractController
{
    /**
     * @var string
     */
    protected const PARAM_PAGE = 'page';

    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    /**
     * @var int
     */
    protected const DEFAULT_MAX_PER_PAGE = 10;

    /**
     * @var string
     */
    protected const PARAM_QUOTE_REQUEST_VERSION_REFERENCE = 'quote-request-version-reference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $viewData = $this->executeIndexAction($request);

        return $this->view($viewData, [], '@QuoteRequestPage/views/quote-request-view/quote-request-view.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function detailsAction(Request $request, string $quoteRequestReference): View
    {
        $viewData = $this->executeDetailsAction($request, $quoteRequestReference);

        return $this->view($viewData, [], '@QuoteRequestPage/views/quote-request-details/quote-request-details.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setPagination($this->getPaginationTransfer($request));

        $quoteRequestCollectionTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        return [
            'quoteRequests' => $quoteRequestCollectionTransfer->getQuoteRequests(),
            'pagination' => $quoteRequestCollectionTransfer->getPagination(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return array
     */
    protected function executeDetailsAction(Request $request, string $quoteRequestReference): array
    {
        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference, true);
        $quoteRequestClient = $this->getFactory()->getQuoteRequestClient();

        $quoteRequestVersionTransfers = $quoteRequestTransfer->getQuoteRequestVersions()
            ->getArrayCopy();

        if (!$quoteRequestTransfer->getIsLatestVersionVisible()) {
            array_shift($quoteRequestVersionTransfers);
        }

        $version = $this->getQuoteRequestVersion(
            $quoteRequestTransfer,
            $quoteRequestVersionTransfers,
            (string)$request->query->get(static::PARAM_QUOTE_REQUEST_VERSION_REFERENCE) ?: null,
        );

        $shipmentGroupTransfers = $this->getFactory()
            ->createShipmentGrouper()
            ->groupItemsByShippingAddress($version->getQuote());

        $itemExtractor = $this->getFactory()->createItemExtractor();

        return [
            'quoteRequest' => $quoteRequestTransfer,
            'quoteRequestVersionReferences' => $this->getQuoteRequestVersionReferences($quoteRequestVersionTransfers),
            'version' => $version,
            'isQuoteRequestCancelable' => $quoteRequestClient->isQuoteRequestCancelable($quoteRequestTransfer),
            'isQuoteRequestReady' => $quoteRequestClient->isQuoteRequestReady($quoteRequestTransfer),
            'isQuoteRequestEditable' => $quoteRequestClient->isQuoteRequestEditable($quoteRequestTransfer),
            'shipmentGroups' => $shipmentGroupTransfers,
            'itemsWithShipment' => $itemExtractor->extractItemsWithShipment($version->getQuote()),
            'itemsWithoutShipment' => $itemExtractor->extractItemsWithoutShipment($version->getQuote()),
            'shipmentExpenses' => $this->getFactory()->createExpenseExtractor()->extractShipmentExpenses($version->getQuote()),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param array<\Generated\Shared\Transfer\QuoteRequestVersionTransfer> $quoteRequestVersionTransfers
     * @param string|null $versionReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function getQuoteRequestVersion(
        QuoteRequestTransfer $quoteRequestTransfer,
        array $quoteRequestVersionTransfers,
        ?string $versionReference
    ): QuoteRequestVersionTransfer {
        if (!$quoteRequestTransfer->getLatestVisibleVersion()) {
            throw new NotFoundHttpException();
        }

        if (!$versionReference) {
            return $quoteRequestTransfer->getLatestVisibleVersion();
        }

        foreach ($quoteRequestVersionTransfers as $quoteRequestVersionTransfer) {
            if ($quoteRequestVersionTransfer->getVersionReference() === $versionReference) {
                return $quoteRequestVersionTransfer;
            }
        }

        throw new NotFoundHttpException();
    }

    /**
     * @param array<\Generated\Shared\Transfer\QuoteRequestVersionTransfer> $quoteRequestVersionTransfers
     *
     * @return array<string>
     */
    protected function getQuoteRequestVersionReferences(array $quoteRequestVersionTransfers): array
    {
        $versionReferences = [];

        foreach ($quoteRequestVersionTransfers as $quoteRequestVersionTransfer) {
            $versionReferences[] = $quoteRequestVersionTransfer->getVersionReference();
        }

        return $versionReferences;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(Request $request): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($request->query->getInt(static::PARAM_PAGE, static::DEFAULT_PAGE))
            ->setMaxPerPage(static::DEFAULT_MAX_PER_PAGE);
    }
}
