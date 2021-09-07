<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinued\Communication\ProductDiscontinuedCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface getRepository()
 */
class DeactivateDiscontinuedProductsConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'deactivate-discontinued-products';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Deactivates discontinued products when active until date passed.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->deactivateDiscontinuedProducts($this->getMessenger());

        return static::CODE_SUCCESS;
    }
}
