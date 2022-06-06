<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Services\Cart\CartService;
use App\Services\Checkout\Interfaces\CheckoutServiceInterface;
use App\Services\Checkout\Resources\CartItemResource;
use App\Services\Coupons\Resolvers\CouponResolver;

final class CheckoutService implements CheckoutServiceInterface
{
    protected CouponResolver $couponResolver;

    private CartService $cartService;

    public function __construct(CartService $cartService, CouponResolver $couponResolver)
    {
        $this->cartService = $cartService;
        $this->couponResolver = $couponResolver;
    }

    public function getTotal(): string {
        $cartItems = $this->cartService->getItems();
        $total = 0;

        /** @var \App\Services\Checkout\Resources\CartItemResource $cartItem */
        foreach ($cartItems as $cartItem) {
            $cartItem = $this->couponResolver->make($cartItem);

            $total += $cartItem->getTotal();
        }

        return \sprintf('$%s', $total);
    }

    /**
     * @return \App\Services\Checkout\Resources\CartItemResource[]
     */
    public function getItems(): array
    {
        return $this->cartService->getItems();
    }
}