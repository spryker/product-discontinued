<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeAdapter;

/**
 * @method \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig getConfig()
 */
class ProductDiscontinuedDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const PLUGINS_POST_PRODUCT_DISCONTINUE = 'PLUGINS_POST_PRODUCT_DISCONTINUE';

    /**
     * @var string
     */
    public const PLUGINS_POST_DELETE_PRODUCT_DISCONTINUED = 'PLUGINS_POST_DELETE_PRODUCT_DISCONTINUED';

    /**
     * @var string
     */
    public const PLUGINS_POST_DELETE_BULK_PRODUCT_DISCONTINUED = 'PLUGINS_POST_DELETE_BULK_PRODUCT_DISCONTINUED';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_DISCONTINUED_PRE_DELETE_CHECK = 'PLUGINS_PRODUCT_DISCONTINUED_PRE_DELETE_CHECK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addPostProductDiscontinuePlugins($container);
        $container = $this->addPostDeleteProductDiscontinuedPlugins($container);
        $container = $this->addPostDeleteBulkProductDiscontinuedPlugins($container);
        $container = $this->addProductDiscontinuedPreDeleteCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductDiscontinuedToProductFacadeAdapter($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostProductDiscontinuePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_PRODUCT_DISCONTINUE, function () {
            return $this->getPostProductDiscontinuePlugins();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedDependencyProvider::addPostDeleteBulkProductDiscontinuedPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostDeleteProductDiscontinuedPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_DELETE_PRODUCT_DISCONTINUED, function () {
            return $this->getPostDeleteProductDiscontinuedPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostDeleteBulkProductDiscontinuedPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_DELETE_BULK_PRODUCT_DISCONTINUED, function () {
            return $this->getPostDeleteBulkProductDiscontinuedPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductDiscontinuedPreDeleteCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_DISCONTINUED_PRE_DELETE_CHECK, function () {
            return $this->getProductDiscontinuedPreDeleteCheckPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface>
     */
    protected function getPostProductDiscontinuePlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedDependencyProvider::getPostDeleteBulkProductDiscontinuedPlugins()} instead.
     *
     * @return array<\Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteProductDiscontinuedPluginInterface>
     */
    protected function getPostDeleteProductDiscontinuedPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteBulkProductDiscontinuedPluginInterface>
     */
    protected function getPostDeleteBulkProductDiscontinuedPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\ProductDiscontinuedPreDeleteCheckPluginInterface>
     */
    protected function getProductDiscontinuedPreDeleteCheckPlugins(): array
    {
        return [];
    }
}
