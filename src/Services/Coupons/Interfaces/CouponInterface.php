<?php

declare(strict_types=1);

namespace App\Services\Coupons\Interfaces;

use App\Services\Checkout\Resources\CartItemResource;

interface CouponInterface
{
    public function supports(string $sku): bool;

    public function apply(CartItemResource $cartItemResource): CartItemResource;
}