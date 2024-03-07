<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Preview Invoice</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="<?php echo base_url() ?>skin/hrpremium_assets/theme_assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Include roboto.css to use the Roboto web font, material.css to include the theme and ripples.css to style the ripple effect -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>skin/hrpremium_assets/theme_assets/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>skin/hrpremium_assets/css/hrpremium/animate.css">
<link href='https://fonts.googleapis.com/css?family=Raleway:400,200,700' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<!--Invoice Information-->
<?php $system_settings = system_settings_info(1);?>
<?php $company_info = xin_company_info(1);?>
<?php $invoice_total = $grand_total * 100;?>
<div class="container">
  <div class="row">
    <div class="col-md-4 col-md-offset-4" style="margin-top:100px;">
      <div class="panel panel-default">
        <div class="panel-body">
          <h4 class="confirm-gateway">Payment Method: <?php echo ucwords($gateway); ?></h4>
          <h4 class="confirm-gateway">Invoice Number: <?php echo $invoice_number; ?></h4>
          <h4 class="confirm-gateway">Amount: <?php echo system_currency_sign($grand_total);?></h4>
          <?php $attributes = array('name' => 'add_item', 'id' => 'xin-form', 'autocomplete' => 'off');?>
          <?php $hidden = array('invoice_id' => $invoice_id, 'token' => $invoice_id);?>
          <?php echo form_open('client/gateway/stripe_charge/'.$invoice_id, $attributes, $hidden);?> 
          <script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="<?php echo $system_settings->stripe_publishable_key; ?>"
				data-amount="<?php echo $invoice_total;?>"
				data-currency="usd"
				data-name="<?php echo $company_info->company_name; ?>"
				data-description="Invoice payment"
				data-image="<?php echo base_url()."uploads/logo/".$company_info->logo; ?>"
				data-locale="auto">
			  </script>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<html>
<script>

</script>
