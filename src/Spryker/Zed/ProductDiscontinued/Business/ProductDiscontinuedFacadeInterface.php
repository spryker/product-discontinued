<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Psr\Log\LoggerInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedBusinessFactory getFactory()
 */
interface ProductDiscontinuedFacadeInterface
{
    /**
     * Specification:
     *  - Marks concrete product as discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function markProductAsDiscontinued(
        ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
    ): ProductDiscontinuedResponseTransfer;

    /**
     * Specification:
     *  - Marks concrete product as not discontinued.
     *  - Executes ProductDiscontinuedPreDeleteCheckPluginInterface plugins before save product as not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function unmarkProductAsDiscontinued(
        ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
    ): ProductDiscontinuedResponseTransfer;

    /**
     * Specification:
     *  - Finds product discontinued by concrete product id.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface::getProductDiscontinuedCollection()} instead.
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function findProductDiscontinuedByProductId(int $idProduct): ProductDiscontinuedResponseTransfer;

    /**
     * Specification:
     * - Checks if all given products are discontinued
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return bool
     */
    public function areAllConcreteProductsDiscontinued(array $productIds): bool;

    /**
     * Specification:
     *  - Checks if at least one of given concrete products is discontinued.
     *
     * @api
     *
     * @param array<int> $productConcreteIds
     *
     * @return bool
     */
    public function isAnyProductConcreteDiscontinued(array $productConcreteIds): bool;

    /**
     * Specification:
     *  - Finds product discontinued by filters.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface::getProductDiscontinuedCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
    ): ProductDiscontinuedCollectionTransfer;

    /**
     * Specification:
     * - Fetches a collection of discontinued products from the Persistence.
     * - Uses `ProductDiscontinuedCriteriaTransfer.ProductDiscontinuedConditions.skus` to filter discontinued products by SKUs.
     * - Uses `ProductDiscontinuedCriteriaTransfer.ProductDiscontinuedConditions.productDiscontinuedIds` to filter discontinued products by product discontinued IDs.
     * - Uses `ProductDiscontinuedCriteriaTransfer.ProductDiscontinuedConditions.productIds` to filter discontinued products by product IDs.
     * - Uses `ProductDiscontinuedCriteriaTransfer.pagination.limit` and `ProductDiscontinuedCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     * - Returns `ProductDiscontinuedCollectionTransfer` filled with found discontinued products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function getProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
    ): ProductDiscontinuedCollectionTransfer;

    /**
     * Specification:
     * - Saves product discontinued note.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer
     */
    public function saveDiscontinuedNote(
        ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
    ): ProductDiscontinuedNoteResponseTransfer;

    /**
     * Specification:
     * - Deactivates discontinued products when active until date passed.
     * - Remove discontinued flag for deactivated products.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function deactivateDiscontinuedProducts(?LoggerInterface $logger = null): void;

    /**
     * Specification:
     *  - Checks all items related products from cart change request if they are not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductsIsNotDiscontinued(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     *  - Checks if shopping list item is not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItemProductIsNotDiscontinued(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer;

    /**
     * Specification:
     *  - Checks if wishlist item is not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductIsNotDiscontinued(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer;

    /**
     * Specification:
     *  - Checks if wishlist item is not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function checkUpdateWishlistItemProductIsNotDiscontinued(WishlistItemTransfer $wishlistItemTransfer): WishlistPreUpdateItemCheckResponseTransfer;

    /**
     * Specification:
     *  - Gets list of abstract ids which have related discontinued concrete product.
     *
     * @api
     *
     * @return array<int>
     */
    public function findProductAbstractIdsWithDiscontinuedConcrete(): array;

    /**
     * Specification:
     * - Checks if the are no discontinued products in checkout.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkProductsInCheckoutAreNotDiscontinued(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;
}
