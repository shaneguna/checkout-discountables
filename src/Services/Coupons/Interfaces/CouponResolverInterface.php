<?php

declare(strict_types=1);

namespace App\Services\Coupons\Interfaces;

use App\Services\Cart\Resources\CartItemResource;

interface CouponResolverInterface
{
    public function make(CartItemResource $cartItemResource): CartItemResource;
}