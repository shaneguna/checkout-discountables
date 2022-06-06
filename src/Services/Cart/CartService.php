<?php

declare(strict_types=1);

namespace App\Services\Cart;

use App\Services\Checkout\Resources\CartItemResource;
use Brick\Math\Internal\Calculator\BcMathCalculator;

final class CartService
{
    protected array $cart = [];

    public function addToCart(CartItemResource $cartItemResource): self
    {
        $existingItem = $this->resolveExistingItem($cartItemResource->getSku());

        if ($existingItem !== null) {
            $updatedQuantity = (int) \bcadd(
                (string) $existingItem->getQuantity(),
                (string) $cartItemResource->getQuantity()
            );

            $existingItem->setQuantity($updatedQuantity);

            $cartItemResource = $existingItem;
        }

        $this->cart[] = $cartItemResource;

        return $this;
    }

    public function getItems(): array
    {
        return $this->cart;
    }

    private function resolveExistingItem(string $sku): ?CartItemResource
    {
        foreach ($this->getItems() as $item) {
            if ($item->getSku() === $sku) {
                return $item;
            }
        }

        return null;
    }
}