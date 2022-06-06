<?php

declare(strict_types=1);

namespace Tests;

use App\InMemoryProducts;
use App\Services\Cart\CartService;
use App\Services\Cart\Resources\CartItemResource;
use App\Services\Checkout\CheckoutService;
use App\Services\Coupons\Resolvers\CouponResolver;
use App\Utilities\ArrayHelper;
use PHPUnit\Framework\TestCase;

/**
 *  For demo, only applied a high level test though ideally we want to test each service.
 *
 * @covers \App\Services\Checkout\CheckoutService
 */
class CheckoutServiceTest extends TestCase
{
    private ?CartService $cartService;

    private ?CheckoutService $checkoutService;

    protected function setUp(): void
    {
        $this->cartService = new CartService();
        $this->checkoutService = new CheckoutService($this->cartService, new CouponResolver());
    }

    protected function tearDown(): void
    {
        $this->cartService = null;
        $this->checkoutService = null;
    }

    public function getRequestValidationFailsTestDataProvider(): iterable
    {
        $product1 = InMemoryProducts::findOneBySku('9780201835953');
        $product2 = InMemoryProducts::findOneBySku('9781430219484');
        $nonDiscountableProduct1 = InMemoryProducts::findOneBySku('9325336028278');
        $nonDiscountableProduct2 = InMemoryProducts::findOneBySku('9780132071482');

        yield 'Satisfied Coupon Should Apply on Discounted Products - Case 1' => [
            'products' => [
                [
                    'details' => $product1,
                    'quantity' => 10,
                    'attemptApplyDiscount' => true,
                ],
                [
                    'details' => $nonDiscountableProduct1,
                    'quantity' => 1,
                    'attemptApplyDiscount' => false,
                ],
            ],
            'expected' => '$239.89',
        ];

        yield 'Applied Coupons Should Apply Discounted Price - Case 2' => [
            'products' => [
                [
                    'details' => $product2,
                    'quantity' => 3,
                    'attemptApplyDiscount' => true,
                ],
                [
                    'details' => $nonDiscountableProduct2,
                    'quantity' => 1,
                    'attemptApplyDiscount' => false,
                ],
            ],
            'expected' => '$177.36',
        ];

        yield 'No Discount Coupons Should Apply Regular Price' => [
            'products' => [
                [
                    'details' => $product1,
                    'quantity' => 10,
                    'attemptApplyDiscount' => false,
                ],
                [
                    'details' => $product2,
                    'quantity' => 5,
                    'attemptApplyDiscount' => false,
                ],
            ],
            'expected' => '$462.3',
        ];

        yield 'Attempt Apply Discount on Unsatisfied Coupon Terms Should Fail' => [
            'products' => [
                [
                    'details' => $product1,
                    'quantity' => 9,
                    'attemptApplyDiscount' => true,
                ],
                [
                    'details' => $product2,
                    'quantity' => 2,
                    'attemptApplyDiscount' => true,
                ],
            ],
            'expected' => '$344.27',
        ];

        yield 'Satisfied Coupon Terms Should Apply Only Once' => [
            'products' => [
                [
                    'details' => $product1,
                    'quantity' => 20,
                    'attemptApplyDiscount' => true,
                ],
                [
                    'details' => $product2,
                    'quantity' => 6,
                    'attemptApplyDiscount' => true,
                ],
            ],
            'expected' => '$583.4',
        ];
    }

    /**
     * @dataProvider getRequestValidationFailsTestDataProvider
     */
    public function testSuccess_AddSomething(array $products, string $expectedTotal): void
    {
        foreach ($products as $product) {
            $productDetails = ArrayHelper::get($product, 'details');
            $price = ArrayHelper::get($productDetails, 'price');
            $sku = ArrayHelper::get($productDetails, 'sku');
            $quantity = ArrayHelper::get($product, 'quantity');
            $discountAttempt = ArrayHelper::get($product, 'attemptApplyDiscount');

            // Test items acting as order item resources supplied via an api
            $cartItem = (new CartItemResource())
                ->setApplyCoupon($discountAttempt)
                ->setSubtotal(\bcmul($price, (string) $quantity))
                ->setSku($sku)
                ->setQuantity($quantity)
                ->setUnitPrice($price);

            $this->cartService->addToCart($cartItem);
        }

        $actualTotal = $this->checkoutService->getTotal();

        self::assertSame($expectedTotal, $actualTotal);
    }
}