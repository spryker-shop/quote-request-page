<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestCheckoutWorkflowStepResolverStrategyPlugin extends AbstractPlugin implements CheckoutStepResolverStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if quote request version is editable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->getQuoteRequestClient()->isEditableQuoteRequestVersion($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns checkout steps suitable when buyer is editing shipment data for request for quote.
     *
     * @api
     *
     * @param array<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface&\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface> $steps
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface&\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface>
     */
    public function execute(array $steps, QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createCheckoutStepResolver()
            ->applyQuoteRequestCheckoutWorkflow($steps);
    }
}
