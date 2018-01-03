<?php
  
  use Atawa\Utilities;

  if(isset($template_vars) && is_array($template_vars)) {
    extract($template_vars);
  }

  $query_params = array();  
  if(isset($search_params['medName']) && $search_params['medName'] !='') {
    $medName = $search_params['medName'];
    $query_params[] = 'medName='.$medName;
  } else {
    $medName = '';
  }
  if(isset($search_params['batchNo']) && $search_params['batchNo'] !='') {
    $batchNo = $search_params['batchNo'];
    $query_params[] = 'batchNo='.$batchNo;
  } else {
    $batchNo = '';
  }
  if(isset($search_params['category']) && $search_params['category'] !='' ) {
    $category = $search_params['category'];
    $query_params[] = 'category='.$category;
  } else {
    $category = '';
  }
  
  if(count($query_params)>0) {
    $query_params = '?'.implode('&', $query_params);
  } else {
    $query_params = '';
  }
?>
<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12"> 

    <!-- Panel starts -->
    <section class="panelBox">
      <div class="panelBody">

        <?php echo Utilities::print_flash_message() ?>

        <!-- Right links starts -->
        <div class="global-links actionButtons clearfix">
          <div class="pull-right text-right">
            <a href="/opbal/add" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> Add New Opening Balance
            </a> 
          </div>
        </div>
        <!-- Right links ends -->        

        <?php if($page_error !== ''): ?>
          <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> <?php echo $page_error ?> 
          </div>
        <?php endif; ?>

		    <div class="panel">
          <div class="panel-body">
			     <div id="filters-form">
      			  <form class="form-validate form-horizontal" method="POST">
        				<div class="form-group">
                  <div class="col-sm-12 col-md-2 col-lg-1">Filter by</div>
        				  <div class="col-sm-12 col-md-2 col-lg-2">
          					<input type="text" placeholder="Item name" name="medName" id="medName" class="form-control" value="<?php echo $medName ?>">
        				  </div>
        				  <div class="col-sm-12 col-md-2 col-lg-2">
          					<input type="text" placeholder="Batch no." name="batchNo" id="batchNo" class="form-control" value="<?php echo $batchNo ?>">
        				  </div>
        				  <div class="col-sm-12 col-md-2 col-lg-2">
          					<div class="select-wrap">
          						<select class="form-control" name="category" id="category">
          						  <?php foreach($categories as $key=>$value): ?>
          							   <option value="<?php echo $key ?>"><?php echo $value ?></option>
          						  <?php endforeach; ?>
          						</select>
          					</div>
        				  </div>
        				  <div class="col-sm-12 col-md-2 col-lg-3">
          					<div class="col-sm-12"> 
          					  <button class="btn btn-success"><i class="fa fa-file-text"></i> Filter</button>
          					  <button type="reset" class="btn btn-warning" onclick="javascript:resetFilter('/opbal/list')"><i class="fa fa-refresh"></i> Reset </button>
          					</div>
        				  </div>
        				</div>
      			  </form>        
			     </div>
          </div>
        </div>
        
        <h2 class="hdg-reports text-center">Inventory Opening Balances</h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover font12">
            <thead>
              <tr>
                <th width="5%" class="text-center valign-middle">Sno.</th>
                <th width="35%" class="text-left valign-middle">Item name</th>
                <th width="5%" class="text-center valign-middle">Units/Pack</th>
                <th width="5%" class="text-center valign-middle">Opening qty.</th>
                <th width="8%" class="text-center valign-middle">Item rate<br />(in Rs.)</th>
                <th width="8%" class="text-center valign-middle">Item value<br />(in Rs.)</th>                
                <th width="5%" class="text-center valign-middle">Tax<br />(in %)</th>
                <th width="5%" class="text-center valign-middle">Batch no.</th>
                <th width="5%" class="text-center valign-middle">Expiry date</th>
                <th width="10%" class="text-center valign-middle">Options</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cntr = $sl_no;
                foreach($openings as $opening_details):
                  $item_name = $opening_details['itemName'];
                  $category_name = $opening_details['categoryName'];
                  $opening_rate = $opening_details['openingRate']; 
                  $opening_qty = $opening_details['openingQty'];
                  $opening_value = $opening_qty*$opening_rate;
                  $expiry_month = $opening_details['expiryDateMonth'];
                  $expiry_year = $opening_details['expiryDateYear']; 
                  $batch_no = $opening_details['batchNo']; 
                  $mrp = $opening_details['mrp'];
                  $upp = $opening_details['unitsPerPack'];
                  $vat_percent = $opening_details['vatPercent'];
                  if($expiry_month<10) {
                    $exp_date = '0'.$expiry_month.'/'.$expiry_year;
                  } else {
                    $exp_date = $expiry_month.'/'.$expiry_year;
                  }
                  $opening_code = $opening_details['openingCode'];
              ?>
                  <tr class="text-uppercase text-right font11">
                    <td class="valign-middle"><?php echo $cntr ?></td>
                    <td class="text-left med-name valign-middle"><?php echo $item_name ?></td>
                    <td class="text-right valign-middle"><?php echo $upp ?></td>
                    <td class="valign-middle"><?php echo $opening_qty ?></td>
                    <td class="valign-middle"><?php echo number_format($opening_rate,2) ?></td>
                    <td class="text-bold valign-middle"><?php echo number_format($opening_value,2) ?></td>
                    <td class="text-right valign-middle"><?php echo number_format($vat_percent,2) ?></td>
                    <td class="text-left valign-middle"><?php echo $batch_no ?></td>
                    <td class="text-right valign-middle"><?php echo $exp_date ?></td>
                    <td>
                      <?php if($opening_code !== ''): ?>
                        <div class="btn-actions-group">
                            <a class="btn btn-primary" href="/opbal/update/<?php echo $opening_code ?>" title="Edit Opening Balance">
                              <i class="fa fa-pencil"></i>
                            </a>
                            <?php /*
                            <a class="btn btn-danger" href="javascrip:void(0)" title="Remove Opening Balance" opcode="<?php echo $opening_code ?>">
                              <i class="fa fa-times"></i>
                            </a>*/ ?>      
                        </div>
                      <?php endif; ?>
                    </td>
                  </tr>
            <?php
              $cntr++;
              endforeach; 
            ?>
            </tbody>
          </table>

          <?php if(count($openings)>0): ?>
            <ul class="pagination">
              <div class="display-count">
                Displaying <?php echo ($sl_no>0?$sl_no:1).' - '.$to_sl_no.' of '.$total_records ?>
              </div>
              <?php
                for($i=$page_links_to_start;$i<=$page_links_to_end;$i++):
                  if((int)$i===(int)$current_page) {
                    $class_name = 'active';
                  } else {
                    $class_name = '';
                  }
              ?>
                <li class="<?php echo $class_name ?>">
                  <a href="/opbal/list/<?php echo $i.$query_params ?>"><?php echo $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          <?php endif; ?>

        </div>
      </div>
    </section>
    <!-- Panel ends --> 
  </div>
</div>
<!-- Basic Forms ends -->