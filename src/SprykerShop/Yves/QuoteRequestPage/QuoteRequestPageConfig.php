<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class QuoteRequestPageConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_WAITING
     */
    public const STATUS_WAITING = 'Waiting';

    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::DEFAULT_VERSION
     */
    public const DEFAULT_VERSION = 1;

    /**
     * @return string
     */
    public function getInitialStatus(): string
    {
        return static::STATUS_WAITING;
    }

    /**
     * @return int
     */
    public function getInitialVersion(): int
    {
        return static::DEFAULT_VERSION;
    }
}