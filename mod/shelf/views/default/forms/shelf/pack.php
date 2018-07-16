<?php

use hypeJunction\Cart\Cart;
use hypeJunction\Payments\ChargeInterface;
use hypeJunction\Payments\MerchantInterface;
use hypeJunction\Payments\OrderItemInterface;
use hypeJunction\Payments\Price;
use hypeJunction\Payments\ProductInterface;
use SebastianBergmann\Money\Currency;

$cart = elgg_extract('cart', $vars);
if (!$cart instanceof Cart) {
	return;
}

$shop = $cart->getMerchant();
$currency = $cart->getCurrency();
$items = $cart->all();

if (!$shop instanceof MerchantInterface || !$currency instanceof Currency || empty($items)) {
	echo elgg_format_element('p', ['class' => 'elgg-no-results'], elgg_echo('cart:is_empty'));
	return;
}

$charges = [];
?>

<div class="cart-checkout">
	<div class="cart-checkout-shop">
		<?php
		$icon = elgg_view_entity_icon($shop, 'small');
		$title = elgg_view('output/url', [
			'href' => $shop->getURL(),
			'text' => $shop->getDisplayName(),
			'is_trusted' => true,
		]);
		echo elgg_view_image_block($icon, elgg_format_element('h3', [], $title), ['class' => 'cart-image-block']);
		?>
	</div>
	<table class="elgg-table-alt cart-table">
		<thead>
			<tr>
				<th class="cart-checkout-item-description"><?= elgg_echo('cart:checkout:product') ?></th>
				<th><?= elgg_echo('cart:checkout:price') ?></th>
				<th><?= elgg_echo('cart:checkout:charges') ?></th>
				<th><?= elgg_echo('cart:checkout:quantity') ?></th>
				<th><?= elgg_echo('cart:checkout:total') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($items as $item) {
				if (!$item instanceof OrderItemInterface) {
					continue;
				}
				$product = $item->getProduct();
				if (!$product instanceof ProductInterface) {
					continue;
				}
				$id = $product->getId();
				$tr_attrs = elgg_format_attributes([
					'class' => 'cart-checkout-item',
					'data-guid' => $id,
				]);
				?>
				<tr <?= $tr_attrs ?>>
					<td class="cart-checkout-item-description">
						<?php
						$icon = elgg_view_entity_icon($product, ['size' => 'small']);
						$title = elgg_view('output/url', [
							'text' => $product->getDisplayName(),
							'href' => $product->getURL(),
							'class' => 'elgg-lightbox',
						]);
						$subtitle = '';
						if ($product->briefdescription) {
							$subtitle = elgg_format_element('div', ['class' => 'elgg-text-help'], $product->briefdescription);
						}
						echo elgg_view_image_block($icon, $title . $subtitle, ['class' => 'cart-image-block']);
						?>
					</td>
					<td class="cart-checkout-item-price">
						<?php
						$amount = $product->price;
						echo Price::format($amount, $currency);
						?>
					</td>
					<td class="cart-checkout-item-charges">
						<?php
						$product_charges = $product->getCharges();
						$product_charges_amount = 0;
						foreach ($product_charges as $product_charge) {
							if (!$product_charge instanceof ChargeInterface) {
								continue;
							}
							$charge_id = $product_charge->getId();
							$charges[$charge_id] += $product_charge->calculate($amount);
							$product_charges_amount += $product_charge->calculate($amount);
						}
						echo Price::format($product_charges_amount, $currency);
						?>
					</td>
					<td class="cart-checkout-item-quantity">
						<?php
						$quantity = $item->getQuantity();
						echo elgg_view('input/text', [
							'name' => "items[{$id}]",
							'value' => $quantity,
						]);
						?>
					</td>
					<td class="cart-checkout-item-total">
						<?php
						echo Price::format(($amount + $product_charges_amount) * $quantity, $currency);
						?>
					</td>
				</tr>
				<?php
			}
			?>
			<tr class="cart-checkout-subtotal">
				<td colspan="4"><?= elgg_echo('cart:subtotal') ?></td>
				<td>
					<?php
					$subtotal = $cart->subtotal();
					echo Price::format($subtotal, $currency);
					?>
				</td>
			</tr>
			<?php
			foreach ($charges as $id => $charge_amount) {
				?>
				<tr class="cart-checkout-tax">
					<td colspan="4"><?= elgg_echo("cart:charges:$id") ?></td>
					<td>
						<?php
						echo Price::format($charge_amount, $currency);
						?>
					</td>
				</tr>
				<?php
			}

			$cart_charges = (array) $shop->getCharges($cart);
			foreach ($cart_charges as $charge) {
				$id = $charge->getId();
				$charge_amount = $charge->calculate($cart->subtotal());
				$subtotal += $charge_amount;
				?>
				<tr class="cart-checkout-subtotal">
					<td colspan="4"><?= elgg_echo("cart:charges:$id") ?></td>
					<td>
						<?php
						echo Price::format($charge_amount, $currency);
						?>
					</td>
				</tr>
				<?php
			}
			?>
			<tr class="cart-checkout-total">
				<td colspan="4">
					<?php
					echo elgg_echo('cart:total');
					?>
				</td>
				<td>
					<?php
					echo Price::format($subtotal, $currency);
					?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php
echo elgg_view('forms/cart/checkout/extend', $vars);
?>
<div class="cart-row cart-payment-method-picker">
	<label class="required"><?php echo elgg_echo('cart:checkout:payment_method') ?></label>
	<?php
	echo elgg_view('input/cart/payments/payment_method', [
		'name' => 'payment_method',
		'cart' => $cart,
	]);
	?>
</div>
<div class="elgg-foot text-right">
	<?php
	echo elgg_view('input/hidden', [
		'name' => 'hash',
		'value' => $cart->get('hash'),
	]);
	echo elgg_view('input/hidden', [
		'name' => 'merchant',
		'value' => $shop->guid,
	]);
	echo elgg_view('input/hidden', [
		'name' => 'currency',
		'value' => $currency,
	]);
	echo elgg_view('input/submit', [
		'value' => elgg_echo('cart:checkout:update'),
	]);
	echo elgg_view('input/cart/payments/payment', [
		'cart' => $cart,
	]);
	?>
</div>
