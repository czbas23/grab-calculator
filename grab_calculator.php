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
  print "Exemple: php grab_calculator.php -o 100+20,70+15,40,50+5,60,80,60,60,60 -d 125 -s 10\n";
  print "         php grab_calculator.php -o Bob/100+10,Judy/70,\"John Doe\"/40,50,60+20,80,60,60,60 -d 125 -s 10\n";
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

$pad = 25;
$hr = "\n" . str_repeat('-', 90) . "\n";
print $hr;
print str_pad("# Order", $pad, " ") . "$count\n";
print str_pad("# Total price", $pad, " ") . "$total_price\n";
print str_pad("# Discount", $pad, " ") . "$discount\n";
print str_pad("# Shipping", $pad, " ") . "$shipping\n";
print str_pad("# Shipping per order", $pad, " ") . "$divide_shipping";
print $hr;
print str_pad("# NAME", $pad, " ");
print str_pad("# Price", $pad, " ");
print str_pad("# Discount", $pad, " ");
print str_pad("# Balance", $pad, " ");
print $hr;
foreach ($order as $item) {
  list($name, $price, $sum_price) = getPrice($item);
  $cal_discount = round(($sum_price / $total_price * $discount), 2);
  $balance = number_format($sum_price - $cal_discount + $divide_shipping, 2);
  print str_pad("$name", $pad, " ");
  print str_pad("$sum_price", $pad, " ");
  print str_pad(number_format($cal_discount, 2), $pad, " ");
  print "$balance";
  print $hr;
}
?>
