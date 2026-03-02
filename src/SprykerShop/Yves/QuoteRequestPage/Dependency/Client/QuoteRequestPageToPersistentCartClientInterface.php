<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface QuoteRequestPageToPersistentCartClientInterface
{
    public function reloadQuoteForCustomer(CustomerTransfer $customerTransfer): void;
}
