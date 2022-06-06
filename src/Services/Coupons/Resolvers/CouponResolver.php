<?php

declare(strict_types=1);

namespace App\Services\Coupons\Resolvers;

use App\Services\Cart\Resources\CartItemResource;
use App\Services\Coupons\FirstItemCoupon;
use App\Services\Coupons\Interfaces\CouponResolverInterface;
use App\Services\Coupons\SecondItemCoupon;

final class CouponResolver implements CouponResolverInterface
{
    /**
     * @var mixed[]
     */
    private array $coupons;

    public function __construct()
    {
        // for demo purposes, assign concrete classes directly
        $this->coupons = [
            new FirstItemCoupon(),
            new SecondItemCoupon(),
        ];
    }

    public function make(CartItemResource $cartItemResource): CartItemResource
    {
        // let's check whether coupon has been applied on checkout and apply coupons only once for each checkout
        if ($cartItemResource->getApplyCoupon() === false || $cartItemResource->getIsDiscounted() === true) {
            return $cartItemResource;
        }

        // go through the coupons to apply whenever product is supported
        /** @var \App\Services\Coupons\Interfaces\CouponInterface $coupon */
        foreach ($this->coupons as $coupon) {
            if ($coupon->supports($cartItemResource->getSku()) === false) {
                continue;
            }

            // let's apply the coupon if passes our pricing rules predetermined in each coupon class
            $coupon->apply($cartItemResource);
        }

        return $cartItemResource;
    }
}