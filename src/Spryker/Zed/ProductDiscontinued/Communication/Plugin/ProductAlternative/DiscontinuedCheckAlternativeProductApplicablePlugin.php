<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Communication\Plugin\ProductAlternative;

use Generated\Shared\Transfer\ProductDiscontinuedConditionsTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\AlternativeProductApplicablePluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Communication\ProductDiscontinuedCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig getConfig()
 */
class DiscontinuedCheckAlternativeProductApplicablePlugin extends AbstractPlugin implements AlternativeProductApplicablePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return bool
     */
    public function check(int $idProduct): bool
    {
        $productDiscontinuedCriteriaTransfer = (new ProductDiscontinuedCriteriaTransfer())
            ->setProductDiscontinuedConditions(
                (new ProductDiscontinuedConditionsTransfer())->addIdProduct($idProduct),
            );

        $productDiscontinuedCollectionTransfer = $this->getFacade()
            ->getProductDiscontinuedCollection($productDiscontinuedCriteriaTransfer);

        return $productDiscontinuedCollectionTransfer->getDiscontinuedProducts()->count() === 1;
    }
}
