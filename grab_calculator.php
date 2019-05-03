<?php

$options = getopt("o:d:s:h", ['help']);

if (isset($options['h']) || isset($options['help'])) {
  print "Usage: php grab_calculator.php [options] [-o] <string> [-d] <number> [-s] <int>\n";
  print "";
  print "    -o \t Order. Use , for splite order.\n";
  print "       \t Use / for separate between name and price or add price only.\n";
  print "       \t Use + for separate price.\n";
  print "    -d \t Discount.\n";
  print "    -s \t Shipping.\n\n";
  print "Exemple: php grab_calculator.php -o 100,70,40,50,60,80,60,60,60 -d 125 -s 10\n";
  print "         php grab_calculator.php -o Bas/100+20,70+10,40,50,60,80,60,60,60 -d 125 -s 10\n";
  exit;
}

if (!isset($options['o'])) {
  print "Please enter option -o";
  exit;
}

function getPrice($item) {
  $explode_price = explode('/', $item);
  if (count($explode_price) == 1) {
    $name = 'Null';
    $price = $explode_price[0];
  } else {
    $name = $explode_price[0];
    $price = $explode_price[1];
  }
  $sum_price = array_sum(explode('+', $price));
  return [$name, $price, $sum_price];
}

$order = explode(',', $options['o']);
$count = count($order);
$total_price = array_reduce($order, function($carry, $item) {
  list($name, $price, $sum_price) = getPrice($item);
  $carry += $sum_price;
  return $carry;
});
$discount = isset($options['d']) ? (float) $options['d'] : 0;
$shipping = isset($options['s']) ? (float) $options['s'] : 0;

$divide_shipping = round($shipping / $count, 2);

$hr = str_repeat('-', 85) . "\n";
print $hr;
print "# Order \t\t\t $count\n";
print "# Total price \t\t\t $total_price\n";
print "# Discount \t\t\t $discount\n";
print "# Shipping \t\t\t $shipping\n";
print "# Shipping per order \t\t $divide_shipping\n";
print $hr;
foreach ($order as $item) {
  list($name, $price, $sum_price) = getPrice($item);
  $cal_discount = round(($sum_price / $total_price * $discount), 2);
  $balance = number_format($sum_price - $cal_discount + $divide_shipping, 2);
  print "# $name\t\tPrice $sum_price\t\tDiscount " . number_format($cal_discount, 2) . "\t\tBalance $balance\n";
  print $hr;
}
?>
