<?php

declare(strict_types=1);

namespace App\Services\Coupons;

use App\Services\Cart\Resources\CartItemResource;
use App\Services\Coupons\Interfaces\CouponInterface;

final class FirstItemCoupon implements CouponInterface
{
    private const SKU = '9780201835953';

    private const VOLUME = 10;

    private const DISCOUNT_PRICE = '21.99';

    public function supports(string $sku): bool
    {
        return $sku === self::SKU;
    }

    public function apply(CartItemResource $cartItemResource): CartItemResource
    {
        $quantity = $cartItemResource->getQuantity();

        if ($cartItemResource->getQuantity() >= self::VOLUME) {
            $discountedPrice = \bcmul(self::DISCOUNT_PRICE, (string) $quantity, 2);

            $cartItemResource->setTotal((float) $discountedPrice);
            $cartItemResource->setIsDiscounted(true);
        }

        return $cartItemResource;
    }
}