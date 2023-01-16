<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;

interface ProductDiscontinuedRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer|null
     */
    public function findProductDiscontinuedByProductId(ProductDiscontinuedTransfer $productDiscontinuedTransfer): ?ProductDiscontinuedTransfer;

    /**
     * @param array<int> $productIds
     *
     * @return bool
     */
    public function areAllConcreteProductsDiscontinued(array $productIds): bool;

    /**
     * @param array<int> $productIds
     *
     * @return bool
     */
    public function isAnyProductConcreteDiscontinued(array $productIds): bool;

    /**
     * @param int $batchSize
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductsToDeactivate(int $batchSize = 1000): ProductDiscontinuedCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
    ): ProductDiscontinuedCollectionTransfer;

    /**
     * @param array<string> $skus
     *
     * @return array<string>
     */
    public function getDiscontinuedProductSkus(array $skus): array;

    /**
     * @return array<int>
     */
    public function findProductAbstractIdsWithDiscontinuedConcrete(): array;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function getProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
    ): ProductDiscontinuedCollectionTransfer;
}
