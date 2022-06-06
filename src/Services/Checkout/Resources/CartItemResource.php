<?php

declare(strict_types=1);

namespace App\Services\Checkout\Resources;

final class CartItemResource
{
    private bool $applyCoupon = false;

    private bool $isDiscounted = false;

    private string $unitPrice = '0';

    private string $subtotal;

    private string $sku;

    private float $total;

    private int $quantity = 0;

    public function getApplyCoupon(): bool
    {
        return $this->applyCoupon;
    }

    public function getIsDiscounted(): bool
    {
        return $this->isDiscounted;
    }

    public function getUnitPrice(): string
    {
        return $this->unitPrice;
    }

    public function getSubtotal(): string
    {
        return $this->subtotal;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getTotal(): float
    {
        return $this->total ?? (float) $this->getQuantity() * $this->getUnitPrice();
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setApplyCoupon(bool $applyCoupon): self
    {
        $this->applyCoupon = $applyCoupon;

        return $this;
    }

    public function setIsDiscounted(bool $isDiscounted): self
    {
        $this->isDiscounted = $isDiscounted;

        return $this;
    }

    public function setUnitPrice(string $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function setSubtotal(string $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}