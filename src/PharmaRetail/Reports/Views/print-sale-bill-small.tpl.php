<?php
	use Atawa\Utilities;
	use Atawa\Constants;

  $bill_date      =  date('d-M-Y',strtotime($sale_details['invoiceDate']));
  $bill_time      =  date('h:ia',strtotime($sale_details['createdTime']));
  $bill_no        =  $sale_details['billNo'];
  $sale_type      =  $sale_details['saleType'];
  $pay_method     =  Constants::$PAYMENT_METHODS[$sale_details['paymentMethod']];
  $doctor_name    =  ($sale_details['doctorName']!=''?$sale_details['doctorName']:'-');
  $sale_type_txt  =  Constants::$SALE_TYPES_NUM[(int)$sale_type];
  $terms_text     =  'Note: [1] Please get your medicines checked by Doctor before use. [2] Production of Original bill is mandatory for return of items. [3] Item returns/replacement will not be entertained after 48 hours. [4] Total amount is inclusive of applicable taxes.';
  $bill_amount    =  $sale_details['billAmount'];
  $bill_discount  =  $sale_details['discountAmount'];
  $total_amount   =  $sale_details['totalAmount'];
  $total_amt_r    =  $sale_details['roundOff'];
  $net_pay        =  $sale_details['netPay'];
  $patient_name   =  ($sale_details['patientName']!==null?$sale_details['patientName']:'');
  $patient_age    =  ($sale_details['patientAge']!==null?$sale_details['patientAge']:'');
  $age_category   =  ($sale_details['patientAgeCategory']!==null?$sale_details['patientAgeCategory']:'');
  $gender         =  ($sale_details['patientGender']!==null?$sale_details['patientGender']:'');
  $ipop_ref_no    =  $sale_details['patientRefNumber'];
  $tax_amount     =  $sale_details['taxAmount'];

	$client_details =		Utilities::get_client_details();
	$business_name 	=		$client_details['businessName'];
	$business_add1	=		$client_details['addr1'];
	$business_add2	=		$client_details['addr2'];
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<style>
			@media print {
			  a {
			   	display:none;
			  }
			}		
		</style>
	</head>
	<body>
		<div id="printDiv">
			<div style="font-family: monospace; width:270px;">
	  		<h3 style="margin:0 0 0 0px;text-align:center;"><?php echo $business_name ?></h3>
	  		<h6 style="margin:0 0 0 0px;text-align:center;"><?php echo $business_add1 ?></h6>
	  		<?php if($business_add2 !== ''): ?>
	  			<h6 style="margin:0 0 0 0px; text-align:center;"><?php echo $business_add2 ?></h6>
	  		<?php endif; ?>
	  		<h3 style="margin: 0px 0 0px 0px;text-align:center;border-top: 1px dotted #000;">Bill No : <?php echo $bill_no ?></h3>
	  		<h5 style="text-align:center;margin:0 0 0 0px;">Bill date &amp; time: <?php echo $bill_date.', '.$bill_time ?></h5>
			  <table style="width: 100%;" cellpadding=0 cellspacing=0>
			    <thead>
			      <tr>
			        <th style="border-top:1px dotted #000;font-size:12px;text-align:left;" colspan="4">Item Name</th>
			      </tr>
						<tr>
			        <th style="text-align:left;border-bottom: 1px dotted #000;font-size:12px;">Batch No. , Expiry</th>
			        <th style="text-align:right;border-bottom: 1px dotted #000;font-size:12px;">Qty.</th>
			        <th style="text-align:right;border-bottom: 1px dotted #000;font-size:12px;">Rate</th>
			        <th style="text-align:right;border-bottom: 1px dotted #000;font-size:12px;">Amount</th>
						</tr>			      
			    </thead>
			    <tbody>
				    <?php
				    	$slno=0;
					    foreach($sale_item_details as $item_details) {
					      $slno++;
					      $amount = $item_details['itemQty']*$item_details['itemRate'];
					      $batch_no_a = explode('_',$item_details['batchNo']);
					      if(is_array($batch_no_a) && count($batch_no_a)>1) {
					        $batch_no = $batch_no_a[1];
					      } else {
					        $batch_no = $item_details['batchNo'];
					      }
					  ?>
				      <tr>
				        <td colspan="4"><?php echo $item_details['itemName'] ?></td>
				      </tr>
				      <tr style="font-weight:bold;">
				        <td style="text-align:left;font-size:10px;padding-left:15px;"><?php echo trim($batch_no).', '.$item_details['expDate'] ?></td>
				        <td style="text-align:right;font-size:10px;"><?php echo number_format($item_details['itemQty'],2) ?></td>
				        <td style="text-align:right;font-size:10px;"><?php echo number_format($item_details['itemRate'],2) ?></td>
				        <td style="text-align:right;font-size:10px;"><?php echo number_format($amount,2) ?></td>
				      </tr>
				    <?php } ?>
			    </tbody>
			    <tr>
			    	<td colspan="3" style="text-align:right;border-top:1px dotted #000;">Bill total:</td>
			    	<td style="text-align:right;border-top:1px dotted #000;">
			    		<?php echo $bill_amount ?>
			    	</td>
			    </tr>
			    <?php if($bill_discount>0): ?>
				    <tr>
				    	<td colspan="3" style="text-align:right;">(-)Discount:</td>
				    	<td style="text-align:right;">
				    		<?php echo $bill_discount ?>
				    	</td>
				    </tr>
				    <tr>
				    	<td colspan="3" style="text-align:right;">Grand total:</td>
				    	<td style="text-align:right;">
				    		<?php echo $total_amount ?>
				    	</td>
				    </tr>
			  	<?php endif; ?>
				    <tr>
				    	<td colspan="3" style="text-align:right;">Roud off:</td>
				    	<td style="text-align:right;">
				    		<?php echo $total_amt_r ?>
				    	</td>
				    </tr>
				    <tr>
				    	<td colspan="3" style="text-align:right;border-top:1px dotted #000;border-bottom:1px dotted #000;">Net pay</td>
				    	<td style="text-align:right;border-bottom:1px dotted #000;border-top:1px dotted #000;">
				    		<?php echo $net_pay ?>
				    	</td>
				    </tr>				    			  	
			  </table>
	  	<div>
	  </div>
	  <h6 style="margin:0;text-align:center;padding:0">powered by AtawaCloud (visit www.atawa.net)</h6>
	  <br />
	  <a href="javascript: window.print();window.close();">Print</a>
	  <a href="javascript: window.close();" style="padding-left:150px;">(x) Close</a>	  
	</body>
</html>

<?php exit; ?>