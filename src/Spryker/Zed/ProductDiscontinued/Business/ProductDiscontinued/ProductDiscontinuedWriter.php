<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface;
use Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig;

class ProductDiscontinuedWriter implements ProductDiscontinuedWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface
     */
    protected $productDiscontinuedEntityManager;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig
     */
    protected $productDiscontinuedConfig;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutorInterface
     */
    protected $productDiscontinuedPluginExecutor;

    /**
     * @var array<\Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\ProductDiscontinuedPreDeleteCheckPluginInterface>
     */
    protected $productDiscontinuedPreDeleteCheckPlugins;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface $productDiscontinuedEntityManager
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     * @param \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutorInterface $productDiscontinuedPluginExecutor
     * @param \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig $productDiscontinuedConfig
     * @param array<\Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\ProductDiscontinuedPreDeleteCheckPluginInterface> $productDiscontinuedPreDeleteCheckPlugins
     */
    public function __construct(
        ProductDiscontinuedEntityManagerInterface $productDiscontinuedEntityManager,
        ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository,
        ProductDiscontinuedPluginExecutorInterface $productDiscontinuedPluginExecutor,
        ProductDiscontinuedConfig $productDiscontinuedConfig,
        array $productDiscontinuedPreDeleteCheckPlugins
    ) {
        $this->productDiscontinuedEntityManager = $productDiscontinuedEntityManager;
        $this->productDiscontinuedConfig = $productDiscontinuedConfig;
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
        $this->productDiscontinuedPluginExecutor = $productDiscontinuedPluginExecutor;
        $this->productDiscontinuedPreDeleteCheckPlugins = $productDiscontinuedPreDeleteCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function create(ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer): ProductDiscontinuedResponseTransfer
    {
        $productDiscontinuedTransfer = (new ProductDiscontinuedTransfer())
            ->setFkProduct($productDiscontinueRequestTransfer->getIdProduct());
        if ($this->productDiscontinuedRepository->findProductDiscontinuedByProductId($productDiscontinuedTransfer)) {
            return (new ProductDiscontinuedResponseTransfer())->setIsSuccessful(false);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($productDiscontinueRequestTransfer) {
            return $this->executeCreateTransaction($productDiscontinueRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function delete(ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer): ProductDiscontinuedResponseTransfer
    {
        $productDiscontinuedTransfer = (new ProductDiscontinuedTransfer())
            ->setFkProduct($productDiscontinueRequestTransfer->getIdProduct());
        $productDiscontinuedTransfer = $this->productDiscontinuedRepository->findProductDiscontinuedByProductId($productDiscontinuedTransfer);
        if (!$productDiscontinuedTransfer) {
            return (new ProductDiscontinuedResponseTransfer())->setIsSuccessful(false);
        }

        $productDiscontinuedResponseTransfer = $this->executeProductDiscontinuedPreDeleteCheckPlugins($productDiscontinuedTransfer);

        if (!$productDiscontinuedResponseTransfer->getIsSuccessful()) {
            return $productDiscontinuedResponseTransfer;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($productDiscontinuedTransfer) {
            return $this->executeDeleteTransaction($productDiscontinuedTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    protected function executeCreateTransaction(
        ProductDiscontinueRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer {
        $productDiscontinuedTransfer = (new ProductDiscontinuedTransfer())
            ->setFkProduct($productDiscontinuedRequestTransfer->getIdProduct())
            ->setActiveUntil($this->getActiveUntilDate());

        $productDiscontinuedTransfer = $this->productDiscontinuedEntityManager
            ->saveProductDiscontinued($productDiscontinuedTransfer);
        $this->productDiscontinuedPluginExecutor->executePostProductDiscontinuePlugins($productDiscontinuedTransfer);

        return (new ProductDiscontinuedResponseTransfer())
            ->setProductDiscontinued($productDiscontinuedTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    protected function executeDeleteTransaction(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer
    ): ProductDiscontinuedResponseTransfer {
        $this->productDiscontinuedEntityManager->deleteProductDiscontinuedNotes($productDiscontinuedTransfer);
        $this->productDiscontinuedEntityManager->deleteProductDiscontinued($productDiscontinuedTransfer);

        $productDiscontinuedCollectionTransfer = new ProductDiscontinuedCollectionTransfer();
        $productDiscontinuedCollectionTransfer->addDiscontinuedProduct($productDiscontinuedTransfer);

        $this->productDiscontinuedPluginExecutor->executeBulkPostDeleteProductDiscontinuedPlugins($productDiscontinuedCollectionTransfer);

        return (new ProductDiscontinuedResponseTransfer())->setIsSuccessful(true);
    }

    /**
     * @return string
     */
    protected function getActiveUntilDate(): string
    {
        return date(
            'Y-m-d',
            (int)strtotime(sprintf('+%s Days', $this->productDiscontinuedConfig->getDaysAmountBeforeProductDeactivate())),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    protected function executeProductDiscontinuedPreDeleteCheckPlugins(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer
    ): ProductDiscontinuedResponseTransfer {
        foreach ($this->productDiscontinuedPreDeleteCheckPlugins as $productDiscontinuedPreDeleteCheckPlugin) {
            $productDiscontinuedResponseTransfer = $productDiscontinuedPreDeleteCheckPlugin->execute($productDiscontinuedTransfer);

            if (!$productDiscontinuedResponseTransfer->getIsSuccessful()) {
                return $productDiscontinuedResponseTransfer;
            }
        }

        return (new ProductDiscontinuedResponseTransfer())->setIsSuccessful(true);
    }
}
