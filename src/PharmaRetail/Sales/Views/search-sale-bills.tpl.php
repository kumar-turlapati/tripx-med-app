<?php
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }
  
  $query_params = '';  
  if(isset($search_params['searchBy']) && $search_params['searchBy'] !='') {
    $searchBy = $search_params['searchBy'];
  } else {
    $searchBy = '';
  }

  if(isset($search_params['searchValue']) && $search_params['searchValue'] !='') {
    $searchValue = $search_params['searchValue'];
  } else {
    $searchValue = '';
  }  
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12">
    
    <!-- Panel starts -->
    <section class="panel">
      <div class="panel-body">

        <?php echo Utilities::print_flash_message() ?>

        <?php if($page_error !== ''): ?>
          <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> <?php echo $page_error ?> 
          </div>
        <?php endif; ?>
          <div id="filters-form">
            <!-- Form starts -->
            <form class="form-validate form-horizontal" method="POST">
              <div class="form-group">
                <div class="col-sm-12 col-md-2 col-lg-2">
                  <label class="control-label">Search by</label>
                  <div class="select-wrap m-bot15">
                    <select class="form-control" name="searchBy" id="searchBy">
                      <?php 
                        foreach($search_by_a as $key=>$value): 
                          if($key===$searchBy) {
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
                <div class="col-sm-12 col-md-2 col-lg-2">
                  <label class="control-label">Search value</label>
                  <input type="text" name="searchValue" id="searchValue" class="form-control" value="<?php echo $searchValue ?>">
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label">&nbsp;</label>
                    <button class="btn btn-success"><i class="fa fa-file-text"></i> Search</button>
                    <button type="reset" class="btn btn-warning" onclick="javascript:resetFilter('/sales/search-bills')"><i class="fa fa-refresh"></i> Reset </button>
                </div>
              </div>
            </form>        
            <!-- Form ends -->
          </div>
        
        <?php if(count($bills)>0) { ?>

          <h2 class="hdg-reports text-center">Bills</h2>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th width="5%"  class="text-center">Sl.No.</th>
                  <th width="10%" class="text-center">Sale Type</th>
                  <th width="15%" class="text-center">Patient Name</th>                
                  <!--th width="10%" class="text-center">Doctor Name</th-->
                  <th width="15%" class="text-center">Bill No.&Date</th>
                  <th width="8%" class="text-center">Bill Amount<br />(in Rs.)</th>
                  <th width="8%" class="text-center">Discount<br />(in Rs.)</th>
                  <th width="5%" class="text-center">Round off<br />(in Rs.)</th>
                  <th width="8%" class="text-center">Net Pay<br />(in Rs.)</th>
                  <th width="18%" class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $cntr = 1;
                  $tot_bill_amount=$tot_disc_amount=$tot_round_off=$tot_net_pay=0;
                  foreach($bills as $sales_details):
                    $sales_code = $sales_details['invoiceCode'];
                    $sale_type = Utilities::get_sale_type($sales_details['saleType']);
                    $invoice_date = date("d-M-Y", strtotime($sales_details['invoiceDate']));
                    if($sales_details['patientName'] !== null) {
                      $patient_name = $sales_details['patientName'];
                    } elseif($sales_details['customerName'] !== null) {
                      $patient_name = $sales_details['customerName'];
                    } elseif($sales_details['generalPName'] !== null) {
                      $patient_name = $sales_details['generalPName'];
                    } else {
                      $patient_name = '';
                    }
                    $tot_bill_amount += $sales_details['billAmount'];
                    $tot_disc_amount += $sales_details['discountAmount'];
                    $tot_round_off += $sales_details['roundOff'];
                    $tot_net_pay += $sales_details['netPay']
                ?>
                    <tr class="text-uppercase text-right font12">
                      <td><?php echo $cntr ?></td>
                      <td class="text-left med-name"><?php echo $sale_type ?></td>
                      <td class="text-left med-name"><?php echo $patient_name ?></td>
                      <!--td class="text-left med-name"><?php echo $sales_details['doctorName'] ?></td-->
                      <td><?php echo $sales_details['billNo'].' / '.$invoice_date ?></td>
                      <td class="text-right"><?php echo $sales_details['billAmount'] ?></td>
                      <td class="text-right"><?php echo $sales_details['discountAmount'] ?></td>
                      <td class="text-right"><?php echo $sales_details['roundOff'] ?></td>
                      <td class="text-right"><?php echo $sales_details['netPay'] ?></td>                
                      <td>
                        <div class="btn-actions-group">
                          <?php if($sales_code !== ''): ?>
                            <a class="btn btn-primary" href="/sales/update/<?php echo $sales_code ?>" title="Edit Sales Transaction">
                              <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-primary" href="javascript: printSalesBill(<?php echo $sales_details['billNo'] ?>)" title="Print Sales Bill">
                              <i class="fa fa-print"></i>
                            </a>                          
                            <a class="btn btn-primary" href="/sales/view/<?php echo $sales_code ?>" title="View Sales Transaction">
                              <i class="fa fa-eye"></i>
                            </a>                          
                            <a class="btn btn-primary" href="/sales-return/entry/<?php echo $sales_code ?>" title="Sales Return">
                              <i class="fa fa-undo"></i>
                            </a>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
              <?php
                $cntr++;
                endforeach; 
              ?>
              <tr class="text-uppercase">
                <td colspan="4" align="right">TOTALS</td>
                <td class="text-bold text-right"><?php echo number_format($tot_bill_amount,2) ?></td>
                <td class="text-bold text-right"><?php echo number_format($tot_disc_amount,2) ?></td>
                <td class="text-bold text-right"><?php echo number_format($tot_round_off,2) ?></td>
                <td class="text-bold text-right"><?php echo number_format($tot_net_pay,2) ?></td>
                <td>&nbsp;</td>              
              </tr>
              </tbody>
            </table>

          </div>
        
        <?php } ?>

      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>