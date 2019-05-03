<?php
if (isset($_POST['order'])) {
  $order =  $_POST['order'];
  $discount =  isset($_POST['discount']) ? $_POST['discount'] : 0;
  $shipping =  isset($_POST['shipping']) ? $_POST['shipping'] : 0;
  // $output = shell_exec("php grab_calculator.php -o 100+20,70+15,40,50+5,60,80,60,60,60 -d 125 -s 10 --json");
  $output = shell_exec("php grab_calculator.php -o $order -d 125 -s $shipping --json");
  $output = json_decode($output, true);
} else {
  $order = '';
  $discount = '';
  $shipping = '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style>
    .wrapper {
      margin:0 auto;
      width: 600px;
    }
    .table-calculator {
      margin:0 auto;
      width: 80%;
    }
    .table-calculator input {
      width: 100%;
    }
    hr {
      margin: 1.5rem 0;
    }
  </style>
  <script>
    function handleCalculator() {
      var order = document.getElementById('order').value;
      var discount = document.getElementById('discount').value;
      var shipping = document.getElementById('shipping').value;
    }
  </script>
</head>
<body>
  <div class="wrapper">
    <h1 style="text-align: center;">Grab Calculator</h1>
    <form action="" method="post">
      <table class="table-calculator">
        <tr>
          <td style="text-align: right;"><label for="order">Order : </label></td>
          <td><input type="text" id="order" name="order" value="<?php echo $order; ?>"></td>
        </tr>
        <tr>
          <td style="text-align: right;"><label for="discount">Discount : </label></td>
          <td><input type="number" id="discount" name="discount" value="<?php echo $discount; ?>"></td>
        </tr>
        <tr>
          <td style="text-align: right;"><label for="shipping">Shipping : </label></td>
          <td><input type="number" id="shipping" name="shipping" value="<?php echo $shipping; ?>"></td>
        </tr>
        <tr>
          <td></td>
          <td><button type="submit">Calculator</button></td>
        </tr>
      </table>
    </form>
    <hr>
    <?php if (isset($output) && is_array($output)) : ?>
      <table width="100%" border="1" style="margin-bottom: 15px;">
        <thead>
          <tbody>
            <tr>
              <th width="15%">Order</th>
              <th width="20%">Total price</th>
              <th width="20%">Discount</th>
              <th width="20%">Shipping</th>
              <th width="25%">Shipping / order</th>
            </tr>
          </tbody>
        </thead>
        <tbody>
          <tr>
            <td style="text-align: center;"><?php echo $output['header']['order'] ?></td>
            <td style="text-align: center;"><?php echo $output['header']['total_price'] ?></td>
            <td style="text-align: center;"><?php echo $output['header']['discount'] ?></td>
            <td style="text-align: center;"><?php echo $output['header']['shipping'] ?></td>
            <td style="text-align: center;"><?php echo $output['header']['shipping_per_order'] ?></td>
          </tr>
        </tbody>
      </table>
      <table width="100%" border="1">
        <thead>
          <tr>
            <th width="40%"># Name</th>
            <th width="20%"># Price</th>
            <th width="20%"># Discount</th>
            <th width="20%"># Balance</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($output['body'] as $item) : ?>
            <tr>
              <td><?php echo $item['name'] ?></td>
              <td style="text-align: right;"><?php echo $item['price'] ?></td>
              <td style="text-align: right;"><?php echo $item['discount'] ?></td>
              <td style="text-align: right;"><?php echo $item['balance'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>