<?php
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars); 
  }

  $current_date = date("d-m-Y");
  
  $query_params = '';  
  if(isset($search_params['adjDate']) && $search_params['adjDate'] !='') {
    $adjDate = $search_params['adjDate'];
    $query_params[] = 'adjDate='.$adjDate;
  } else {
    $adjDate = '';
  }

  if(is_array($query_params) && count($query_params)>0) {
    $query_params = '?'.implode('&', $query_params);
  }

  $pagination_url = '/inventory/stock-adjustments-list';
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12">
    
    <!-- Panel starts -->
    <section class="panelBox">
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
            <a href="/inventory/stock-adjustment" class="btn btn-default"><i class="fa fa-book"></i> New Stock Adjustment</a>
            <!-- <a href="/inventory/stock-adjustments-list" class="btn btn-default"><i class="fa fa-file-text-o"></i> Stock Adjustment List</a> -->
          </div>
        </div>
        <!-- Right links ends -->         

        <div class="panel">
          <div class="panel-body">
          <div id="filters-form">
            <form class="form-validate form-horizontal" method="POST" id="adjForm">
              <div class="form-group">
                <div class="col-sm-12 col-md-2 col-lg-1">Filter By</div>
                <div class="col-sm-12 col-md-2 col-lg-2">
                  <div class="form-group">
                    <div class="col-lg-12">
                      <div class="input-append date" data-date="<?php echo $current_date ?>" data-date-format="dd-mm-yyyy">
                        <input placeholder="Adjustment date" class="span2" size="16" type="text" readonly name="adjDate" id="adjDate" value="<?php echo $adjDate ?>" />
                        <span class="add-on"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
                <?php include_once __DIR__."/../../../Layout/helpers/filter-buttons.helper.php" ?>
              </div>
            </form>        
          </div>
        </div>
        </div>
        
        <h2 class="hdg-reports text-center">List of Adjusted Item Qtys</h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover font12">
            <thead>
              <tr>
                <th width="5%" class="text-center">Sl.No.</th>
                <th width="35%" class="text-left">Item Name</th>
                <th width="8%" class="text-center">Batch No.</th>
                <th width="8%" class="text-center">Adjusted<br />Qty.</th>
                <th width="24%" class="text-center">Reason</th>
                <th width="10%" class="text-center">Adj. Date</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cntr = $sl_no;
                foreach($items as $item_details):
                  $item_name = $item_details['itemName'];
                  $item_code = $item_details['itemCode'];
                  $batch_no = $item_details['batchNo'];
                  $adj_qty = $item_details['adjQty'];
                  $reason_code = $item_details['reasonCode'];
                  $reason_a = explode('_',$adj_reasons[$reason_code]);
                  $adj_date = date("d-M-Y",strtotime($item_details['adjDate']));
              ?>
                  <tr class="text-right">
                    <td><?php echo $cntr ?></td>
                    <td class="text-left"><?php echo $item_name ?></td>
                    <td class="text-bold"><?php echo $batch_no ?></td>
                    <td class="text-right"><?php echo number_format($adj_qty,2) ?></td>
                    <td class="text-right font11">
                      <i><?php echo $reason_a[0] ?></i>
                    </td>
                    <td class="text-right"><?php echo $adj_date ?></td>
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