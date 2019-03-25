<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Form\DataProvider\QuoteRequestFormDataProvider;
use SprykerShop\Yves\QuoteRequestPage\Form\Handler\QuoteRequestHandler;
use SprykerShop\Yves\QuoteRequestPage\Form\Handler\QuoteRequestHandlerInterface;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestEditItemsConfirmForm;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig getConfig()
 */
class QuoteRequestPageFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer|null $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestForm(?QuoteRequestTransfer $quoteRequestTransfer = null): FormInterface
    {
        $quoteRequestFormDataProvider = $this->createQuoteRequestFormDataProvider();

        return $this->getFormFactory()->create(
            QuoteRequestForm::class,
            $quoteRequestFormDataProvider->getData($quoteRequestTransfer)
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Form\DataProvider\QuoteRequestFormDataProvider
     */
    public function createQuoteRequestFormDataProvider(): QuoteRequestFormDataProvider
    {
        return new QuoteRequestFormDataProvider(
            $this->getCompanyUserClient(),
            $this->getCartClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Form\Handler\QuoteRequestHandlerInterface
     */
    public function createQuoteRequestHandler(): QuoteRequestHandlerInterface
    {
        return new QuoteRequestHandler(
            $this->getQuoteRequestClient(),
            $this->getCartClient()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestEditItemsConfirmForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteRequestEditItemsConfirmForm::class,
            $quoteRequestTransfer
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): QuoteRequestPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestPageToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface
     */
    public function getCartClient(): QuoteRequestPageToCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPageExtension\Dependency\Plugin\QuoteRequestFormMetadataFieldPluginInterface[]
     */
    public function getQuoteRequestFormMetadataFieldPlugins(): array
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::PLUGINS_QUOTE_REQUEST_FORM_METADATA_FIELD);
    }
}
