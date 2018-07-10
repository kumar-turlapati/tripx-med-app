<?php

  use Atawa\Utilities;
  use Atawa\Constants;

  $current_date = date("d-m-Y");
  $pagination_url = '/sales/list-by-patient';

  $query_params = '';
  if(isset($search_params['fromDate']) && $search_params['fromDate'] !='') {
    $fromDate = $search_params['fromDate'];
    $query_params[] = 'fromDate='.$fromDate;
  } else {
    $fromDate = $current_date;
  }
  if(isset($search_params['toDate']) && $search_params['toDate'] !='' ) {
    $toDate = $search_params['toDate'];
    $query_params[] = 'toDate='.$toDate;
  } else {
    $toDate = $current_date;
  }
  if(isset($search_params['saleType']) && $search_params['saleType'] !='' ) {
    $saleType = $search_params['saleType'];
    $query_params[] = 'saleMode='.$search_params['saleType'];
  } else {
    $saleType = '';
  }

  // echo '<pre>';
  // var_dump($payment_methods, $paymentMethod);
  // echo '</pre>';
  // dump($query_totals);
  // dump($sale_modes);

  if(is_array($query_params) && count($query_params)>0) {
    $query_params = '?'.implode('&', $query_params);
  }
  $page_url = '/sales/list-by-patient';
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12">
    
    <!-- Panel starts -->
    <section class="panelBox">
      <h2 class="hdg-reports text-center">Patientwise Sales Register &nbsp;-&nbsp;<?php echo ($saleType!=='' ? Constants::$SALE_TYPES[$saleType] : 'All Sale Types') ?></h2>
      <div class="panelBody">
        <?php echo Utilities::print_flash_message() ?>

        <?php if($page_error !== ''): ?>
          <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> <?php echo $page_error ?> 
          </div>
        <?php endif; ?>

        <!-- Right links starts -->
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/sales/entry" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> New Sale 
            </a> 
          </div>
        </div>
        <!-- Right links ends --> 
		
		<div class="filters-block">
			<div id="filters-form">
			  <!-- Form starts -->
			  <form class="form-validate form-horizontal" method="GET" action="<?php echo $page_url ?>">
  				<div class="form-group">
            <div class="col-sm-12 col-md-1 col-lg-1">Filter by</div>
  				  <div class="col-sm-12 col-md-2 col-lg-2">
  						<div class="input-append date" data-date="<?php echo $current_date ?>" data-date-format="dd-mm-yyyy">
  						  <input class="span2" size="16" type="text" readonly name="fromDate" id="fromDate" value="<?php echo $fromDate ?>" />
  						  <span class="add-on"><i class="fa fa-calendar"></i></span>
  					  </div>
  				  </div>
  				  <div class="col-sm-12 col-md-2 col-lg-2">
  						<div class="input-append date" data-date="<?php echo $current_date ?>" data-date-format="dd-mm-yyyy">
  						  <input class="span2" size="16" type="text" readonly name="toDate" id="toDate" value="<?php echo $toDate ?>" />
  						  <span class="add-on"><i class="fa fa-calendar"></i></span>
  						</div>
  				  </div>
            <?php /*
  				  <div class="col-sm-12 col-md-2 col-lg-2">
    					<div class="select-wrap">
    						<select class="form-control" name="paymentMethod" id="paymentMethod">
    						  <?php 
                    foreach($payment_methods as $key=>$value):
                      if((int)$paymentMethod === (int)$key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }  
                  ?>
    							 <option value="<?php echo $key ?>" <?php echo $selected ?>>
                      <?php echo $value ?>
                    </option>
    						  <?php endforeach; ?>
    						</select>
    					 </div>
  				  </div>*/ ?>
            <div class="col-sm-12 col-md-2 col-lg-2">
              <div class="select-wrap">
                <select class="form-control" name="saleType" id="saleType">
                  <?php 
                    foreach($sale_modes as $key=>$value):
                      if($saleType === $key) {
                        $selected = 'selected="selected"';
                      } else {
                        $selected = '';
                      }
                  ?>
                  <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
                  <?php endforeach; ?>
                </select>
               </div>
            </div>           
            <?php include_once __DIR__."/../../../Layout/helpers/filter-buttons.helper.php" ?>
  				</div>
			  </form>        
			  <!-- Form ends -->
			</div>
        </div>
         <div class="table-responsive">
          <table class="table table-striped table-hover font12">
            <thead>
              <tr>
                <th width="5%"  class="text-center">Sno.</th>
                <th width="25%" class="text-center">Patient name</th>
                <th width="10%" class="text-center">Age &amp; Gender</th>                
                <th width="12%" class="text-center">Mobile no.</th>
                <th width="8%" class="text-center">Reference no.</th>
                <th width="10%" class="text-center">Transaction value<br />(in Rs.)</th>
                <th width="17%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cntr = $sl_no;
                $tot_bill_amount = 0;
                foreach($sales as $sales_details):
                  if($sales_details['customerName'] === '') {
                    $patient_name = 'Unknown';
                  } else {
                    $patient_name = $sales_details['customerName'];                    
                  }
                  if($sales_details['age'] > 0) {
                    $patient_age_gender = $sales_details['age'].' '.strtolower($sales_details['ageCategory']).' / '.strtoupper($sales_details['gender']);
                  } else {
                    $patient_age_gender = '';
                  }
                  if($sales_details['mobileNo'] !== '') {
                    $mobile_no = $sales_details['mobileNo'];
                  } else {
                    $mobile_no = '';
                  }
                  if($sales_details['refNo'] !== '') {
                    $ref_no = $sales_details['refNo'];
                  } else {
                    $ref_no = '';
                  }
                  if($sales_details['tranValue'] !== '') {
                    $tran_value = $sales_details['tranValue'];
                  } else {
                    $tran_value = 0;
                  }
                  $customer_code = $sales_details['customerCode'];
                  $tot_bill_amount += $tran_value;   
              ?>
                  <tr class="text-uppercase text-right font12">
                    <td class="valign-middle"><?php echo $cntr ?></td>
                    <td class="text-left  valign-middle"><?php echo substr($patient_name, 0, 25) ?></td>
                    <td class="text-left  valign-middle" style="text-transform: none;"><?php echo $patient_age_gender ?></td>
                    <td class="text-right valign-middle"><?php echo $mobile_no ?></td>
                    <td class="text-right valign-middle"><?php echo $ref_no ?></td>
                    <td class="text-right valign-middle font14"><b><?php echo $tran_value ?></b></td>
                    <td>
                      <?php if($customer_code !== ''): ?>
                        <div class="btn-actions-group">
                          <a class="btn btn-primary" href="/patients/update/<?php echo $customer_code ?>" title="Edit Customer Information">
                            <i class="fa fa-pencil"></i>
                          </a>
                        </div>
                      <?php endif; ?>
                    </td>
                  </tr>
            <?php
              $cntr++;
              endforeach; 
            ?>
              <tr class="text-uppercase">
                <td colspan="5" align="right"><b>PAGE TOTALS</b></td>
                <td class="text-bold text-right font13"><?php echo number_format($tot_bill_amount, 2) ?></td>
                <td>&nbsp;</td>
              </tr>
              <tr class="text-uppercase font14">
                <td colspan="5" align="right"><b>GRAND TOTALS<b></td>
                <td class="text-bold text-right font13"><?php echo number_format($query_totals, 2) ?></td>
                <td>&nbsp;</td>                
              </tr>
            </tbody>
          </table>
          <?php include_once __DIR__."/../../../Layout/helpers/pagination.helper.php" ?>
        </div>
      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>