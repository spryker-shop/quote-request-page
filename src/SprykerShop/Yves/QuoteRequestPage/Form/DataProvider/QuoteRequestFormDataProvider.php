<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form\DataProvider;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuoteRequestFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface $companyUserClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient
     * @param \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig $config
     */
    public function __construct(
        QuoteRequestPageToCompanyUserClientInterface $companyUserClient,
        QuoteRequestPageToCartClientInterface $cartClient,
        QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestPageConfig $config
    )
    {
        $this->companyUserClient = $companyUserClient;
        $this->cartClient = $cartClient;
        $this->quoteRequestClient = $quoteRequestClient;
        $this->config = $config;
    }

    /**
     * @param string|null $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function getData(?string $quoteRequestReference): QuoteRequestTransfer
    {
        if (!$quoteRequestReference) {
            return $this->createQuoteRequestTransfer();
        }

        return $this->quoteRequestClient->findQuoteRequest(
            $quoteRequestReference,
            $this->companyUserClient->findCompanyUser()->getIdCompanyUser()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequestTransfer(): QuoteRequestTransfer
    {
        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote($this->cartClient->getQuote());

        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserClient->findCompanyUser())
            ->setCreatedAt((new DateTime())->format('Y-m-d H:i:s'))
            ->setStatus($this->config->getInitialStatus())
            ->setLatestVersion($quoteRequestVersionTransfer);

        return $quoteRequestTransfer;
    }
}
