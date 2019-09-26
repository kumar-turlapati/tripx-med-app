<?php
  use Atawa\Utilities;

  $query_params = [];  
  if(isset($search_params['suppName']) && $search_params['suppName'] !='') {
    $suppName = $search_params['suppName'];
    $query_params[] = 'suppName='.$suppName;
  } else {
    $suppName = '';
  }
  if(isset($search_params['category']) && $search_params['category'] !='' ) {
    $category = $search_params['category'];
    $query_params[] = 'category='.$category;
  } else {
    $category = '';
  }
  if(is_array($query_params) && count($query_params)>0) {
    $query_params = '?'.implode('&', $query_params);
  } else {
    $query_params = '';
  }
  $page_url = '/suppliers/list';
?>

<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12">
    
    <!-- Panel starts -->
    <section class="panelBox">
      <h2 class="hdg-reports text-center">List of all Suppliers</h2>
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
            <!-- <a href="/suppliers/list" class="btn btn-default">
              <i class="fa fa-book"></i> Suppliers List
            </a> -->
            <a href="/suppliers/create" class="btn btn-default">
              <i class="fa fa-file-text-o"></i> New Supplier 
            </a> 
          </div>
        </div>
        <!-- Right links ends --> 

		<div class="filters-block">
			<div id="filters-form">
			  <!-- Form starts -->
			  <form class="form-validate form-horizontal" method="POST" action="/suppliers/list">
				<div class="form-group">
          <div class="col-sm-12 col-md-2 col-lg-2"><b>Filter by</b></div>
				  <div class="col-sm-12 col-md-2 col-lg-2">
					<input placeholder="Supplier Name" type="text" name="suppName" id="suppName" class="form-control" value="<?php echo $suppName ?>">
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
                <th width="5%" class="text-center">Sno.</th>
                <th width="25%" class="text-center">Supplier Name</th>
                <th width="10%" class="text-center">DL No.</th>
                <th width="10%" class="text-center">Tax Regn.No.</span></th>
                <th width="15%" class="text-center">Contact Person</th>
                <th width="15%" class="text-center">Phones</th>
                <th width="15%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $cntr = $sl_no;
                foreach($suppliers as $supplier_details):
                  $supplier_code = $supplier_details['supplierCode'];
              ?>
                  <tr class="text-uppercase text-right font12" style="vertical-align:middle;">
                    <td class="text-center"><?php echo $cntr ?></td>
                    <td class="text-left med-name"><?php echo $supplier_details['supplierName'] ?></td>
                    <td class="text-left"><?php echo $supplier_details['dlNo'] ?></td>
                    <td class="text-bold text-left"><?php echo $supplier_details['tinNo'] ?></td>
                    <td class="text-left"><?php echo $supplier_details['contactPersonName'] ?></td>
                    <td class="text-left"><?php echo $supplier_details['mobileNo'] ?></td>
                    <td>
                      <div class="btn-actions-group">
                        <?php if($supplier_code !== ''): ?>
                          <a class="btn btn-primary" href="/fin/supplier-ledger?suppCode=<?php echo $supplier_code ?>" title="View Supplier Ledger">
                            <i class="fa fa-eye"></i>
                          </a>
                          <a class="btn btn-primary" href="/suppliers/update/<?php echo $supplier_code ?>" title="Edit Supplier">
                            <i class="fa fa-pencil"></i>
                          </a>                          
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
                <a href="/suppliers/list/<?php echo $i.$query_params ?>"><?php echo $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>

        </div>
      </div>
    </section>
    <!-- Panel ends -->
  </div>
</div>

<!-- Modal HTML -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>