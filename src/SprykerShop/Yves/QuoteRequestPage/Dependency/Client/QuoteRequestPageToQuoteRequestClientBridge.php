<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteRequestPageToQuoteRequestClientBridge implements QuoteRequestPageToQuoteRequestClientInterface
{
    /**
     * @var \Spryker\Client\QuoteRequest\QuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \Spryker\Client\QuoteRequest\QuoteRequestClientInterface $quoteRequestClient
     */
    public function __construct($quoteRequestClient)
    {
        $this->quoteRequestClient = $quoteRequestClient;
    }

    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestClient->createQuoteRequest($quoteRequestTransfer);
    }

    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestClient->updateQuoteRequest($quoteRequestTransfer);
    }

    public function reviseQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestClient->reviseQuoteRequest($quoteRequestFilterTransfer);
    }

    public function cancelQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestClient->cancelQuoteRequest($quoteRequestFilterTransfer);
    }

    public function sendQuoteRequestToUser(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestClient->sendQuoteRequestToUser($quoteRequestFilterTransfer);
    }

    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        return $this->quoteRequestClient->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);
    }

    public function getQuoteRequestVersionCollectionByFilter(
        QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
    ): QuoteRequestVersionCollectionTransfer {
        return $this->quoteRequestClient->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer);
    }

    public function getQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestClient->getQuoteRequest($quoteRequestFilterTransfer);
    }

    public function convertQuoteRequestToLockedQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->quoteRequestClient->convertQuoteRequestToLockedQuote($quoteRequestTransfer);
    }

    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->quoteRequestClient->convertQuoteRequestToQuote($quoteRequestTransfer);
    }

    public function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->quoteRequestClient->isQuoteRequestCancelable($quoteRequestTransfer);
    }

    public function isQuoteRequestEditable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->quoteRequestClient->isQuoteRequestEditable($quoteRequestTransfer);
    }

    public function isQuoteRequestReady(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->quoteRequestClient->isQuoteRequestReady($quoteRequestTransfer);
    }

    public function isEditableQuoteRequestVersion(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteRequestClient->isEditableQuoteRequestVersion($quoteTransfer);
    }

    public function isEditableQuoteShipmentSourcePrice(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteRequestClient->isEditableQuoteShipmentSourcePrice($quoteTransfer);
    }
}
