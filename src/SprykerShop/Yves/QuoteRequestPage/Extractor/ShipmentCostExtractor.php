<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Extractor;

class ShipmentCostExtractor implements ShipmentCostExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupTransfers
     *
     * @return int
     */
    public function extractTotalShipmentCosts(array $shipmentGroupTransfers): int
    {
        $totalShippingCosts = 0;

        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $totalShippingCosts += $shipmentGroupTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
        }

        return $totalShippingCosts;
    }
}
