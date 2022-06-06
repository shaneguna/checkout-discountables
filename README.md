# The Nile Group Coding Challenge
The Nile wants to offer a wide variety of discounts to our customers.

Your task is to develop a system to allow for discounts to be applied
to a customers cart. The system should be as flexible as possible, allowing
for the creation of new discount types easily.

Given these products:

SKU           | Name                         | Price
--------------|------------------------------|----------
9325336130810 | Game of Thrones: Season 1    | $39.49
9325336028278 | The Fresh Prince of Bel-Air  | $19.99
9780201835953 | The Mythical Man-Month       | $31.87
9781430219484 | Coders at Work               | $28.72
9780132071482 | Artificial Intelligence      | $119.92
--------------|------------------------------|----------

Initially we would like to offer our customers these discounts:

* Buy 10 or more copies of The Mythical Man-Month, and receive them at the discounted price of $21.99
* We would like to offer a 3 for the price of 2 deal on Coders at Work. (Buy 3 get 1 free);


Examples:

Products in cart: 9780201835953 x 10, 9325336028278
Expected total: $239.89

Products in cart: 9781430219484 x 3, 9780132071482
Expected total: $177.36

Example interface:

`$checkout = new Checkout($pricingRules);`

`$checkout->addToCart("9780201835953");`

`$checkout->addToCart("9781430219484");`

`$checkout->total();`

## To Setup & Run Locally

1. Clone this repo locally, `cd` into directory
2. Run `composer install`
3. Run `composer test`

Composer test is setup with `--testdox` option to give more readable test output.
```
> phpunit tests --testdox

Checkout Service (Tests\CheckoutService)
 ✔ Success AddSomething with data set "Satisfied Coupon Should Apply on Discounted Products - Case 1"
 ✔ Success AddSomething with data set "Applied Coupons Should Apply Discounted Price - Case 2"
 ✔ Success AddSomething with data set "No Discount Coupons Should Apply Regular Price"
 ✔ Success AddSomething with data set "Attempt Apply Discount on Unsatisfied Coupon Terms Should Fail"
 ✔ Success AddSomething with data set "Satisfied Coupon Terms Should Apply Only Once"


Time: 00:00.015, Memory: 6.00 MB

OK (5 tests, 5 assertions)
```