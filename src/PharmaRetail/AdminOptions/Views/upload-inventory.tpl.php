<?php
  use Atawa\Utilities; 
?>
<!-- Basic form starts -->
<div class="row">
  <div class="col-lg-12">
    <!-- Panel starts -->
    <section class="panelBox">
      <div class="panelBody">
        <?php echo Utilities::print_flash_message() ?>
        <div id="filters-form">
          <form class="form-validate form-horizontal" method="POST" enctype="multipart/form-data" id="frmInventoryUpload">
            <div class="panel">
              <div class="panel-body">
                <div class="form-group">
                  <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                    <label class="control-label">Select a file to upload inventory</label>
                    <input type="file" class="form-control" name="fileName" id="fileName" />
                  </div>
                  <div class="col-sm-12 col-md-4 col-lg-4 m-bot15">
                    <label class="control-label">Upload type</label>
                    <div class="select-wrap">
                      <select class="form-control" id="uploadType" name="uploadType">
                        <?php 
                          foreach($upload_options as $key=>$value): 
                        ?>
                          <option value="<?php echo $key ?>"><?php echo $value ?></option>
                        <?php endforeach; ?>
                      </select>
                      <?php if(isset($errors['uploadType'])): ?>
                        <span class="error"><?php echo $errors['uploadType'] ?></span>
                      <?php endif; ?>                
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-4 col-lg-4 m-bot15" id="downloadButton">
                    <label class="control-label">
                      <a href="/downloads/Atawa_InventoryUpload_Format_V.1.0.xls" target="_blank">
                        <i class="fa fa-download"></i> Click here to download Inventory Upload format.
                      </a>
                    </label>
                    <p class="red" align="center">Note: For uploading only Microsoft Office Excel 2003, Excel 2002, Excel 2000, and Excel 97 file formats are allowed.</p>
                    <p class="blue" align="center">Only 300 rows are allowed per excel sheet including header row.</p>
                  </div>                  
                </div>
                <div class="text-center">
                  <button class="btn btn-success" id="uploadInventory" name="uploadInventory"><i class="fa fa-upload"></i> Upload</button>
                  <button type="reset" class="btn btn-warning" onclick="javascript:resetFilter('/admin-options/upload-inventory')" name="invRefresh" id="invRefresh"><i class="fa fa-refresh"></i> Reset </button>
                  <p class="red" id="reloadInfo" style="display:none;font-size:14px;">Please wait while we upload your data. This may take several minutes...<br />Please don't close this page or press "Back" or "Refresh" buttons</p>
                </div>
              </div>
            </div>
          </form>        
        </div>
      </div>
    </section>
  </div>
</div>