<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\CartChangePreCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface;

class CartChangePreCheck implements CartChangePreCheckInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CART_PRE_CHECK_PRODUCT_DISCONTINUED = 'cart.pre.check.product_discontinued';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_NAME = '%name%';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     */
    public function __construct(ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository)
    {
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartItems(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $cartPreCheckResponseTransfer = $this->addDiscontinuedErrorMessagesToCartPreCheckResponseTransfer(
            $cartPreCheckResponseTransfer,
            $cartChangeTransfer,
        );

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function addDiscontinuedErrorMessagesToCartPreCheckResponseTransfer(
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer,
        CartChangeTransfer $cartChangeTransfer
    ): CartPreCheckResponseTransfer {
        $skus = $this->getSkusFromCartChangeTransfer($cartChangeTransfer);
        $productDiscontinuedCollectionTransfer = $this
            ->productDiscontinuedRepository
            ->findProductDiscontinuedCollection($this->createProductDiscontinuedCriteriaFilterTransfer($skus));
        $indexedProductDiscontinuedTransfers = $this->indexProductDiscontinuedTransfersBySku($productDiscontinuedCollectionTransfer);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($this->isProductDiscontinued($itemTransfer, $indexedProductDiscontinuedTransfers)) {
                $cartPreCheckResponseTransfer->addMessage(
                    $this->createItemIsDiscontinuedMessageTransfer($itemTransfer),
                );
            }
        }

        $cartPreCheckResponseTransfer->setIsSuccess(
            !$cartPreCheckResponseTransfer->getMessages()->count(),
        );

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<\Generated\Shared\Transfer\ProductDiscontinuedTransfer> $indexedProductDiscontinuedTransfers
     *
     * @return bool
     */
    protected function isProductDiscontinued(ItemTransfer $itemTransfer, array $indexedProductDiscontinuedTransfers): bool
    {
        return isset($indexedProductDiscontinuedTransfers[$itemTransfer->getSku()]);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsDiscontinuedMessageTransfer(ItemTransfer $itemTransfer): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::GLOSSARY_KEY_CART_PRE_CHECK_PRODUCT_DISCONTINUED);
        $messageTransfer->setParameters([
            static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku(),
            static::GLOSSARY_PARAM_NAME => $itemTransfer->getName(),
        ]);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string>
     */
    protected function getSkusFromCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        return $skus;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductDiscontinuedTransfer>
     */
    protected function indexProductDiscontinuedTransfersBySku(ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer): array
    {
        $indexedProductDiscontinuedTransfers = [];

        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $indexedProductDiscontinuedTransfers[$productDiscontinuedTransfer->getSku()] = $productDiscontinuedTransfer;
        }

        return $indexedProductDiscontinuedTransfers;
    }

    /**
     * @param array<string> $skus
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer
     */
    protected function createProductDiscontinuedCriteriaFilterTransfer(array $skus): ProductDiscontinuedCriteriaFilterTransfer
    {
        $productDiscontinuedCriteriaFilterTransfer = new ProductDiscontinuedCriteriaFilterTransfer();
        $productDiscontinuedCriteriaFilterTransfer->setSkus($skus);

        return $productDiscontinuedCriteriaFilterTransfer;
    }
}
