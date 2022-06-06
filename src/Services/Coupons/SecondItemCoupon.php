<?php

declare(strict_types=1);

namespace App\Services\Coupons;

use App\Services\Checkout\Resources\CartItemResource;
use App\Services\Coupons\Interfaces\CouponInterface;

final class SecondItemCoupon implements CouponInterface
{
    private const SKU = '9781430219484';

    private const VOLUME = 3;

    private const DISCOUNT_PERCENTAGE = '100';

    public function supports(string $sku): bool
    {
        return $sku === self::SKU;
    }

    public function apply(CartItemResource $cartItemResource): CartItemResource
    {
        $quantity = $cartItemResource->getQuantity();

        if ($cartItemResource->getQuantity() >= self::VOLUME) {
            $total = $cartItemResource->getUnitPrice() * $quantity;
            // get discount price based on discount percentage
            $discountedPrice = \bcmul(self::DISCOUNT_PERCENTAGE, $cartItemResource->getUnitPrice(), 2) / 100;
            // deduct discounted price from our cart items total
            $total -= $discountedPrice;

            $cartItemResource->setTotal((float) $total);
            $cartItemResource->setIsDiscounted(true);
        }

        return $cartItemResource;
    }
}