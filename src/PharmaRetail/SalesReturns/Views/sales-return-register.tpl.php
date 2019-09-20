<?php
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }

  $current_date = date("d-m-Y");
  $pagination_url = '/sales-return/list';

  $query_params = [];  
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
  if(is_array($query_params) && count($query_params)>0) {
    $query_params = '?'.implode('&', $query_params);
  }
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12">
    
    <!-- Panel starts -->
    <section class="panelBox">
      <h2 class="hdg-reports text-center">Daywise Sales Return List</h2>
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
			  <form class="form-validate form-horizontal" method="POST">
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
                <th width="5%"  class="text-center">Sl.No.</th>
                <th width="10%" class="text-center">Sale type</th>
                <th width="10%" class="text-center">MRN No.</th>                
                <th width="10%" class="text-center">Return date</th>
                <th width="15%" class="text-center">Bill No. &amp; Date</th>
                <th width="8%" class="text-center">Sale value<br />(in Rs.)</th>
                <th width="8%" class="text-center">Return value<br />(in Rs.)</th>
                <th width="13%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cntr = $sl_no;
                foreach($sales_returns as $sales_return_details):
                  $return_date = date("d-m-Y", strtotime($sales_return_details['returnDate']));
                  $mrn_no = $sales_return_details['mrnNo'];
                  $bill_no = $sales_return_details['billNo'];
                  $invoice_date = date("d-m-Y", strtotime($sales_return_details['invoiceDate']));
                  $sale_value = $sales_return_details['netPay'];
                  $return_value = $sales_return_details['returnAmount'];
                  $return_status = $sales_return_details['returnStatus'];
                  $sales_code = $sales_return_details['invoiceCode'];
                  $sale_type = Utilities::get_sale_type($sales_return_details['saleType']);
                  $return_code = $sales_return_details['returnCode'];
              ?>
                  <tr class="text-uppercase text-right font12">
                    <td class="text-center"><?php echo $cntr ?></td>
                    <td class="text-left"><?php echo $sale_type ?></td>
                    <td class="text-left"><?php echo $mrn_no ?></td>
                    <td class="text-left"><?php echo $return_date ?></td>       
                    <td><?php echo $bill_no.' / '.$invoice_date ?></td>
                    <td class="text-right"><?php echo $sale_value ?></td>
                    <td class="text-right"><?php echo $return_value ?></td>               
                    <td>
                      <div class="btn-actions-group">
                        <!--a class="btn btn-primary" href="#" data-toggle="modal" data-target="#myModal" title="View Supplier"><i class="fa fa-eye"></i></a-->
                        <?php if($return_code !== ''): ?>
                          <a class="btn btn-primary" href="/sales-return/view/<?php echo $sales_code.'/'.$return_code ?>" title="View Sales Return Transaction">
                            <i class="fa fa-eye"></i>
                          </a>
                          <a class="btn btn-primary" href="javascript: printSalesReturnBill('<?php echo $return_code ?>')" title="Print Sales Return Bill">
                            <i class="fa fa-print"></i>
                          </a>
                          <?php /*                         
                          <a class="btn btn-danger delSalesReturn" href="javascrip:void(0)" title="Remove Sales Return Transaction" rid="<?php echo $return_code ?>">
                            <i class="fa fa-times"></i>
                          </a>*/ ?>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
            <?php
              $cntr++;
              endforeach; 
            ?>
            </tbody>
          </table>

          <?php include_once __DIR__."/../../../Layout/helpers/pagination.helper.php" ?>

        </div>
      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>
