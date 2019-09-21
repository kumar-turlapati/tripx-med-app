$(window).load(function() {
  // Animate loader off screen
  $(".se-pre-con").fadeOut("slow");;
});

function initializeJS() {

	// Datepicker
	jQuery('.date').datepicker();
	
	// Timepicker
	jQuery('#timepicker1').timepicker();

    //tool tips
    jQuery('.tooltips').tooltip();

    //popovers
    jQuery('.popovers').popover();

    //custom scrollbar
        //for html
    jQuery("html").niceScroll({styler:"fb",cursorcolor:"#007AFF", cursorwidth: '6', cursorborderradius: '10px', background: '#F7F7F7', cursorborder: '', zindex: '1000'});
        //for sidebar
    jQuery("#sidebar").niceScroll({styler:"fb",cursorcolor:"#007AFF", cursorwidth: '3', cursorborderradius: '10px', background: '#F7F7F7', cursorborder: ''});
        // for scroll panel
    jQuery(".scroll-panel").niceScroll({styler:"fb",cursorcolor:"#007AFF", cursorwidth: '3', cursorborderradius: '10px', background: '#F7F7F7', cursorborder: ''});
    
    //sidebar dropdown menu
    jQuery('#sidebar .sub-menu > a').click(function () {
        var last = jQuery('.sub-menu.open', jQuery('#sidebar'));        
        jQuery(this).find('.menu-arrow').removeClass('arrow_carrot-right');
        jQuery('.sub', last).slideUp(200);
        var sub = jQuery(this).next();
        if (sub.is(":visible")) {
            jQuery(this).find('.menu-arrow').addClass('arrow_carrot-right');            
            sub.slideUp(200);
        } else {
            jQuery(this).find('.menu-arrow').addClass('arrow_carrot-down');            
            sub.slideDown(200);
        }
        var o = (jQuery(this).offset());
        diff = 200 - o.top;
        if(diff>0)
            jQuery("#sidebar").scrollTo("-="+Math.abs(diff),500);
        else
            jQuery("#sidebar").scrollTo("+="+Math.abs(diff),500);
    });

    // sidebar menu toggle
    jQuery(function() {
        function responsiveView() {
            var wSize = jQuery(window).width();
            if (wSize <= 768) {
                jQuery('#container').addClass('sidebar-close');
                jQuery('#sidebar > ul').hide();
            }

            if (wSize > 768) {
                jQuery('#container').removeClass('sidebar-close');
                jQuery('#sidebar > ul').show();
            }
        }
        jQuery(window).on('load', responsiveView);
        jQuery(window).on('resize', responsiveView);
    });

    jQuery('.toggle-nav').click(function () {
        if (jQuery('#sidebar > ul').is(":visible") === true) {
            jQuery('#main-content').css({
                'margin-left': '0px'
            });
            jQuery('#sidebar').css({
                'margin-left': '-180px'
            });
            jQuery('#sidebar > ul').hide();
            jQuery("#container").addClass("sidebar-closed");
        } else {
            jQuery('#main-content').css({
              'margin-left': '180px'
            });
            jQuery('#sidebar > ul').show();
            jQuery('#sidebar').css({
                'margin-left': '0'
            });
            jQuery("#container").removeClass("sidebar-closed");
        }
    });

    /************************************************************************/
    /* Business logic functions starts from here. don't alter below code.
    /************************************************************************/

    // Prevent Enter key while submitting form.

    var saleBatchNos=[];
    var bnoFirstOption = jQuery("<option></option>").attr("value","xx99!!").text("Sel");

    $('form').attr('autocomplete', 'off');

    /*
    if($('#taxes_taxPercent').length>0) {
      $('#taxes_taxPercent').inputmask('99.99');
    }*/

    if(jQuery('#itemnames').length>0) {    
      $('#itemnames').Tabledit({
        url: '/async/add-thr-qty',
        editButton: false,
        deleteButton: false,
        hideIdentifier: false,
        columns: {
          identifier: [1,'mCode'],
          editable: [[3,'thQty']]
        },
        onAjax: function(action, serialize) {
          // var data = serialize.split('&')[1];
          // console.log(data);
          // console.log('onAjax(action, serialize)');
          // console.log(action);
          // console.log(serialize);
        }
      });
    }

    $('.noEnterKey').on('keypress keydown keyup', function (e) {
       if (e.keyCode == 13) {
         e.preventDefault();
       }
    });

    if($('#delSaleBill').length>0) {
      $('#delSaleBill').inputmask('99999999');      
    }

    jQuery('.delSupplier').on("click", function(e){
        var supplierId = jQuery(this).attr('sid');
        bootbox.confirm("Are you sure. You want to remove this Supplier?", function(result) {
            if(result===true) {
                window.location.href="/suppliers/remove/"+supplierId
            } else {
                return;               
            }
        });
    });

    jQuery('.delDoctor').on("click", function(e){
        var doctorId = jQuery(this).attr('sid');
        bootbox.confirm("Are you sure. You want to remove Doctor from app?", function(result) {
            if(result===true) {
                window.location.href="/doctors/remove/"+doctorId
            } else {
                return;               
            }
        });
    });    

    jQuery('.delSales').on("click", function(e){
        var salesId = jQuery(this).attr('sid');
        bootbox.confirm("Are you sure. You want to remove this Sales transaction?", function(result) {
            if(result===true) {
                window.location.href="/sales/remove/"+salesId
            } else {
                return;
            }
        });
    });

    jQuery('.delSalesReturn').on("click", function(e){
        var salesReturnId = jQuery(this).attr('rid');
        bootbox.confirm("Are you sure. You want to remove this Sales Return transaction?", function(result) {
            if(result===true) {
                window.location.href="/sales-return/remove/"+salesReturnId
            } else {
                return;
            }
        });
    });    

    if(jQuery('.inameAc').length>0) {
      $('.inameAc').autocomplete("/async/itemsAc", {
        width: 300,
        cacheLength:0,
        selectFirst:false,
        minChars:2,
        'max': 0,
      });
    }

    if(jQuery('.inameLc').length>0) {
      $('.inameLc').autocomplete("/async/itemsAc", {
        width: 300,
        cacheLength:0,
        selectFirst:false,
        minChars:2,
        'max': 0,
      });
    }

    jQuery('.puRcvdQty').on("blur",function(e){
      var rcvdQty = parseFloat($(this).val());
      var idArray = $(this).attr('id').split('_');

      var freeId = '#puFreeQty_'+idArray[1];
      var freeQty = parseFloat($(freeId).val());
      if(isNaN(freeQty)) {
        freeQty = 0;
      }
      if(isNaN(rcvdQty)) {
        rcvdQty = 0;
      }
      
      $('#puBillQty_'+idArray[1]).text((rcvdQty-freeQty).toFixed(2));
      updatePurchaseitemAmount(idArray[1]);
      updatePurchaseItemTotal();
    });

    jQuery('#paymentMode').on("change", function(e){
        var paymentMode = $(this).val();
        if(paymentMode==='b' || paymentMode==='p') {
            $('#refInfo').show();
        } else if(paymentMode==='c') {
            $('#refInfo').hide();
        }
    });

    jQuery('.puFreeQty').on("blur",function(e){
      var freeQty = parseFloat($(this).val());
      var idArray = $(this).attr('id').split('_');
      var rcvdId = '#puRcvdQty_'+idArray[1];
      var rcvdQty = parseFloat($(rcvdId).val());
      if(isNaN(freeQty)) {
        freeQty = 0;
      }
      if(isNaN(rcvdQty)) {
        rcvdQty = 0;
      }

      if(freeQty>rcvdQty) {
        alert('Free Qty. must be less than or equal to Received Qty.');
        $('#puFreeQty_'+idArray[1]).val(0);
        $('#puBillQty_'+idArray[1]).val(rcvdQty)
      } else {
        $('#puBillQty_'+idArray[1]).val((rcvdQty-freeQty));
        updatePurchaseitemAmount(idArray[1]);
      }
    });

    jQuery('.puItemRate').on("blur",function(){
        var taxAmount = 0;
        var idArray = $(this).attr('id').split('_');
        var rowId = idArray[1];
        updatePurItemTaxRates(rowId);        
    });

    $('.puTaxIncludes').on("change", function(){
      var idArray = $(this).attr('id').split('_');
      rowId = idArray[1];
      $('#puItemRate_'+rowId).val('');
      alert('You need to re-enter Item rate again for this item to get new calculations effected !');
      $('#puItemRate_'+rowId).focus();

      updatePurItemTaxRates(rowId);        
    });    

    jQuery('.puItemTax').on("change",function(){
      var idArray = $(this).attr('id').split('_');
      rowId = idArray[1];

      var taxPercent = parseFloat($(this).val());
      var itemRate = parseFloat($('#puItemRate_'+rowId).val());
      if(taxPercent>=0) {
        taxAmount = parseFloat( (itemRate*taxPercent)/100).toFixed(2);
        if(isNaN(taxAmount)) {
          taxAmount = 0.00;
        }
        $('#puItemTaxRate_'+rowId).text(taxAmount)
        updatePurchaseitemAmount(rowId);
      }
    });

    function updatePurItemTaxRates(rowId) {
      var taxIorE = parseInt($('#puTaxIncludes_'+rowId).val());
      var taxPercent = parseFloat($('#puItemTax_'+rowId).val());
      var itemRate = parseFloat($('#puItemRate_'+rowId).val());
      var finalItemRate = calcTaxRate = 0;

      if(parseInt(taxIorE) === 0) {
        taxAmount = parseFloat( (itemRate*taxPercent)/100);
        finalItemRate = itemRate;
      } else if(parseInt(taxIorE) === 1) {
        calcTaxRate = (taxPercent/100)+1;
        grossValue = Math.round((itemRate/calcTaxRate) * 100)/100;
        taxAmount = Math.round( (itemRate-grossValue) * 100)/100;
        finalItemRate = grossValue;
      }

      if(isNaN(finalItemRate)) {
        finalItemRate = 0;
      }
      if(isNaN(taxAmount)) {
        taxAmount = 0;
      }      

      $('#puItemRate_'+rowId).val(finalItemRate);
      $('#puItemTaxRate_'+rowId).text(taxAmount);

      updatePurchaseitemAmount(rowId);
    }

    jQuery('.inameAc').on("blur", function(e){
        var itemName = jQuery(this).val();
        var itemIndex = jQuery(this).attr('index');
        var batchNoRef = $('#batchno_'+itemIndex);
        var avaBatches = $(batchNoRef).children('option').length;
        if(itemName !== '' && parseInt(avaBatches) === 1) {
           var data = {itemname:itemName};
           jQuery.ajax("/async/getBatchNos", {
              data: data,
              method:"POST",
              success: function(batchNos) {
                var objLength = Object.keys(batchNos).length;
                var bnoElement = jQuery('#batchno_'+itemIndex);
                var uppElement = jQuery('#upp_'+itemIndex);
                if(objLength>0) {
                    var upp = batchNos.unitsPerPack;
                    jQuery(uppElement).text(upp);
                    jQuery(bnoElement).empty();
                    jQuery(bnoElement).append(bnoFirstOption);
                    jQuery.each(batchNos.batch_nos, function (index, bnoDetails) {
                        saleBatchNos[index] = bnoDetails;
                        jQuery(bnoElement).append(jQuery("<option></option>")
                        .attr("value",index)
                        .text(index));                   
                    });
                } else {
                    jQuery(uppElement).text('');
                }
              },
              error: function(e) {
                alert('An error occurred while fetching Batch Nos.');
              }
           });          
        }
    });

    jQuery('.landingCost').on("blur", function(e){
        var itemName = jQuery(this).val();
        var itemIndex = jQuery(this).attr('index');
        var batchNoRef = $('#batchno_'+itemIndex);
        var avaBatches = $(batchNoRef).children('option').length;
        if(itemName !== '' && parseInt(avaBatches) === 1) {
           var data = {itemname:itemName};
           jQuery.ajax("/async/getBatchNosWithLc", {
              data: data,
              method:"POST",
              success: function(batchNos) {
                // console.log(batchNos);
                var objLength = Object.keys(batchNos).length;
                var bnoElement = jQuery('#batchno_'+itemIndex);
                var uppElement = jQuery('#upp_'+itemIndex);
                if(objLength>0) {
                    var upp = batchNos.unitsPerPack;
                    jQuery(uppElement).text(upp);
                    jQuery(bnoElement).empty();
                    jQuery(bnoElement).append(bnoFirstOption);
                    jQuery.each(batchNos.batch_nos, function (index, bnoDetails) {
                        saleBatchNos[index] = bnoDetails;
                        jQuery(bnoElement).append(jQuery("<option></option>")
                        .attr("value",index)
                        .text(index));                   
                    });
                } else {
                    jQuery(uppElement).text('');
                }
              },
              error: function(e) {
                alert('An error occurred while fetching Batch Nos.');
              }
           });          
        }
    });

    jQuery('.batchNo').on("change", function(e){
        var batchNo = jQuery(this).val();
        var elemId = jQuery(this).attr('index');
        var qtyAvailable = itemRate = expDate = '';
        if(batchNo !== 'xx99!!') {
            if(Object.keys(saleBatchNos[batchNo]).length>0) {
                qtyAvailable = saleBatchNos[batchNo].availableQty;
                itemRate = saleBatchNos[batchNo].itemRate;
                expDate = saleBatchNos[batchNo].expDate;
                itemQty = jQuery('#qty_'+elemId+' option:selected').val();
                if(parseInt(itemQty)>parseInt(qtyAvailable)) {
                    alert('Sold Qty. should be less than or equal to Available Qty.');
                    return false;
                } else if(parseFloat(itemQty)>0 && parseFloat(itemRate)>0) {
                    updateItemTotals(elemId,itemQty,itemRate);  
                }
            } else {
                qtyAvailable = '**invalid**';
                itemRate = '**invalid**';
            }
        } else {
            $('option', this).not(':eq(0)').remove();
            jQuery('#iname_'+elemId).val('');
            jQuery('#qty_'+elemId).val(0);
            jQuery('#upp_'+elemId).text('');
            jQuery('#itemtotal_'+elemId).text('');            
        }

        jQuery('#qtyava_'+elemId).text(qtyAvailable);
        jQuery('#mrp_'+elemId).text(itemRate);
        jQuery('#expdate_'+elemId).text(expDate);
    });

    jQuery('.itemQty').on("change", function(e){
        var elemId = jQuery(this).attr('index');
        var itemQty = parseInt(jQuery(this).val());
        var itemRate = parseFloat(jQuery('#mrp_'+elemId).text());
        var batchNo = $('#batchno_'+elemId).val();
        var qtyAvailable = saleBatchNos[batchNo].availableQty;
        if(parseInt(itemQty)>parseInt(qtyAvailable)) {
            $(this).val(0);
            alert('Sold Qty. should be less than or equal to Available Qty.');
            return false;
        } else if(parseFloat(itemQty)>0 && parseFloat(itemRate)>0) {
            updateItemTotals(elemId,itemQty,itemRate);  
        }            
    });

    jQuery('input[name=discount]').on("change", function(e){
      updateSaleBillTotals();
    });    

    jQuery('#poNoGrn').on("blur", function(){
        var poNo = $(this).val();
        if(poNo !== '') {
           jQuery.ajax("/async/poDetails?poNo="+poNo, {
              method:"GET",
              success: function(apiResponse) {
                apiResponse = JSON.parse(apiResponse);
                var apiStatus = apiResponse.status;
                if(apiStatus=='success') {
                    var tranDetails = apiResponse.response.purchaseDetails;
                    var purItems = apiResponse.response.purchaseDetails.itemDetails;
                    var billAmount = tranDetails.billAmount;
                    var roundOff = tranDetails.roundOff;
                    var netPay = tranDetails.netPay;
                    var adjAmount = tranDetails.adjAmount;
                    $('#supplierID').val(tranDetails.supplierCode);
                    $('#paymentMethod').val(tranDetails.paymentMethod);
                    $('#creditDays').val(tranDetails.creditDays);
                    $('#grandTotal').text(billAmount);
                    $('#roundOff').text(roundOff);
                    $('#netPay').text(netPay);
                    $('#adjAmount').text(adjAmount);
                    $.each(purItems,function(key,purItemDetails){
                        var totalQty = parseInt(purItemDetails.itemQty);
                        var freeQty = parseInt(purItemDetails.freeQty);
                        var billedQty = totalQty-freeQty;
                        var itemCode = purItemDetails.itemCode;
                        var itemAmount = parseFloat(billedQty)*parseFloat(purItemDetails.itemRate);
                        var taxAmount = parseFloat(itemAmount)*parseFloat(purItemDetails.vatPercent)/100;
                        itemAmount += taxAmount;

                        $('#grnItemName_'+key).text(purItemDetails.itemName);
                        $('#grnRcvdQty_'+key).html('<b>'+totalQty+'</b> ('+freeQty+')');
                        $('#grnBatchNo_'+key).text(purItemDetails.batchNo);
                        $('#grnExpDate_'+key).text(purItemDetails.expdateMonth+'/'+purItemDetails.expdateYear);
                        $('#grnMrp_'+key).text(purItemDetails.mrp);
                        $('#grnItemRate_'+key).text(purItemDetails.itemRate);
                        $('#grnTaxRate_'+key).text(purItemDetails.vatPercent);
                        $('#grnItemAmount_'+key).text(itemAmount.toFixed(2));
                        $('#grnAccQty_'+key).val(totalQty);
                        $('#poCode').val(tranDetails.purchaseCode);

                        $('<input type="hidden">').attr({
                            name: 'grnItems[]',
                            value: itemCode
                        }).appendTo('#grnForm');
                    });
                } else {
                    for(key=0;key<25;key++) {
                        $('#grnItemName_'+key).text('');
                        $('#grnRcvdQty_'+key).text('');
                        $('#grnBatchNo_'+key).text('');
                        $('#grnExpDate_'+key).text('');
                        $('#grnMrp_'+key).text('');
                        $('#grnItemRate_'+key).text('');
                        $('#grnTaxRate_'+key).text('');
                    }
                    alert('Invalid PO NO.');
                }
              },
              error: function(e) {
                alert('Invalid PO No.');
              }
           });
        }
    });

    function updateItemTotals(index, qty, rate) {
      var netPay = iTotal = billAmount = totalAmount = roundOff = 0;
      var elemId = jQuery('#itemtotal_'+index);
      var itemTotal = (parseFloat(qty)*parseFloat(rate)).toFixed(2);
      var discount = 0;

      jQuery(elemId).text(itemTotal);
      jQuery('.itemTotal').each(function(i, obj) {
          iTotal = jQuery(this).text();
          if(parseFloat(iTotal)>0) {
              billAmount  += parseFloat(iTotal);
          }
      });

      totalAmount = billAmount-discount;
      roundOff = parseFloat(Math.round(totalAmount)-totalAmount);
      netPay = parseFloat(totalAmount+roundOff);

      billAmount = billAmount.toFixed(2);
      totalAmount = totalAmount.toFixed(2);
      roundOff = roundOff.toFixed(2);
      netPay = netPay.toFixed(2);

      jQuery('.billAmount').text(billAmount);
      jQuery('.totalAmount').text(totalAmount);
      jQuery('.roundOff').text(roundOff); 
      jQuery('.netPay').text(netPay);

      updateSaleBillTotals();
    }

    function updateSaleBillTotals() {
      var netPay = iTotal = billAmount = totalAmount = roundOff = 0;
      var discountPercent = $('input[name=discount]:checked').val();
      if(discountPercent) {
        jQuery('.itemTotal').each(function(i, obj) {
          iTotal = jQuery(this).text();
          if(parseFloat(iTotal)>0) {
              billAmount  += parseFloat(iTotal);
          }
        });

        var discount = ((parseFloat(billAmount)*parseFloat(discountPercent))/100).toFixed(2);

        totalAmount = billAmount-discount;
        roundOff = parseFloat(Math.round(totalAmount)-totalAmount);
        netPay = parseFloat(totalAmount+roundOff);

        billAmount = billAmount.toFixed(2);
        totalAmount = totalAmount.toFixed(2);
        roundOff = roundOff.toFixed(2);
        netPay = netPay.toFixed(2);

        jQuery('.billAmount').text(billAmount);
        jQuery('.totalAmount').text(totalAmount);
        jQuery('#discount').text(discount);
        jQuery('.roundOff').text(roundOff);
        jQuery('.netPay').text(netPay);      
      }
    }

    function updatePurchaseitemAmount(rowId) {
      var itemRate = parseFloat($('#puItemRate_'+rowId).val());
      var taxRate = parseFloat($('#puItemTaxRate_'+rowId).text());
      var billedQty = parseFloat($('#puBillQty_'+rowId).text());
      var itemAmount = 0;
      if(itemRate>0 && billedQty>0) {
        itemAmount = parseFloat( (itemRate+taxRate)*billedQty );
        $('#puItemAmount_'+rowId).text(itemAmount.toFixed(2));
        updatePurchaseItemTotal();
       } else {
        $('#puItemAmount_'+rowId).text('');
       }
    }

    function updatePurchaseItemTotal() {
      var billAmount = roundOff = netPay = totalAmount = 0;
      var adjAmount = parseFloat($('#adjAmount').val());

      if(isNaN(adjAmount)) {
        adjAmount = 0;
      }

      jQuery('.puItemAmount').each(function(i, obj) {
        iTotal = jQuery(this).text();
        if(parseFloat(iTotal)>0) {
          billAmount  += parseFloat(iTotal);
        }
      });
      if(billAmount>0) {
        totalAmount = parseFloat(billAmount-adjAmount);
        roundOff = parseFloat(Math.round(totalAmount)-totalAmount);
        netPay = parseFloat(totalAmount+roundOff);
      } else {
        billAmount = roundOff = netPay = '';
      }

      $('.grandTotal').text(billAmount.toFixed(2));
      $('.roundOff').text(roundOff.toFixed(2));
      $('.netPay').text(netPay.toFixed(2));
    }

    jQuery('#adjAmount').on("blur",function(){
      updatePurchaseItemTotal();
    });

    $('#qTaxPercent').on("change",function(){
      if( $(this).val() !== '' ) {
        var qTaxPercent = $(this).val();
      } else {
        var qTaxPercent = 0;
      }
      $('.puItemTax').each(function(i, obj) {
        $('#puItemTax_'+i).val(qTaxPercent);
        updatePurchaseitemAmount(i);
      });
    });

    $('#qRateIncludesTax').on("change",function(){
      if( $(this).val() !== '' ) {
        var qRateIncludesTax = $(this).val();
      } else {
        var qRateIncludesTax = 0;
      }
      $('.puTaxIncludes').each(function(i, obj) {
        $(this).val(qRateIncludesTax);
      });      
    });    

    $('.puExpDate').inputmask('99/99');

    if( $('#gstNo').length>0 ) {
      $('#gstNo').inputmask('99**********9Z9');
    }

    $('#reportsFilter').on("click",function(e){
        e.preventDefault();
        $('#reportsForm').submit();
    });

    $('#reportsReset').on("click",function(e){
        e.preventDefault();
        var redirectUrl = $('#reportHook').val();
        window.location.href='/report-options'+redirectUrl;
    });    

    $('.returnQty').on("change", function(e){
        var returnItemId = $(this).attr('id').split('_')[1];
        var returnRate = parseFloat($('#returnRate_'+returnItemId).text());
        var returnQty = parseFloat($(this).val());
        var returnValue = parseFloat(returnRate*returnQty);
        $('#returnValue_'+returnItemId).text(returnValue.toFixed(2));
        updateSalesReturnValue();
    });

    $('#registrationNo').on("blur", function(e){
        var refNo = $(this).val();
        if(refNo !== '') {
           jQuery.ajax("/async/getPatientDetails?refNo="+refNo, {
              method:"GET",
              success: function(patientDetails) {
                var objLength = Object.keys(patientDetails).length;
                if(objLength>0) {
                    $('#name').val(patientDetails.patientName);
                    $('#age').val(patientDetails.age);
                    $('#ageCategory').val(patientDetails.ageCategory);
                    $('#gender').val(patientDetails.gender);
                    $('#mobileNo').val(patientDetails.mobileNo); 
                    $('#saleType').val(getPatientTypes(patientDetails.regType));                             
                } else {
                    // $('#name').val('');
                    // $('#age').val(0);
                    // $('#ageCategory').val('years');
                    // $('#gender').val('m');
                    // $('#mobileNo').val('');
                    // $('#saleType').val('GEN');                             
                }
              },
              error: function(e) {
                alert('An error occurred while fetching Patient Details');
              }
           });          
        }
    });

    $('#mobileNo').on("blur", function(e){
        var refNo = $(this).val();
        if(refNo !== '') {
           jQuery.ajax("/async/getPatientDetails?refNo="+refNo+'&by=mobile', {
              method:"GET",
              success: function(patientDetails) {
                var objLength = Object.keys(patientDetails).length;
                if(objLength>0) {
                    $('#name').val(patientDetails.patientName);
                    $('#age').val(patientDetails.age);
                    $('#ageCategory').val(patientDetails.ageCategory);
                    $('#gender').val(patientDetails.gender);
                    $('#mobileNo').val(patientDetails.mobileNo);
                    $('#saleType').val(getPatientTypes(patientDetails.regType));
                    $('#registrationNo').val(patientDetails.refNumber);
                } else {
                    $('#name').val('');
                    $('#age').val(0);
                    $('#ageCategory').val('years');
                    $('#gender').val('m');
                    $('#saleType').val('GEN');
                    $('#registrationNo').val('');                            
                }
              },
              error: function(e) {
                alert('An error occurred while fetching Patient Details');
              }
           });
        }
    });

    $('#expiryForm #month').on("change",function(e){
        e.preventDefault();
        var expiryMonth = $(this).val();
        var expiryYear = $('#expiryForm #year').val();
        var redirectUrl = '/inventory/trash-expired-items?month='+expiryMonth+'&year='+expiryYear;
        window.location.href = redirectUrl;
    });

    $('.mmovement').on("change",function(e){
        if($(this).val()==='slow') {
            $('#count').val(1);
            $('#count').attr('disabled',true);
        } else if($(this).val()==='fast') {
            $('#count').val(0);
            $('#count').attr('disabled',false);            
        }
    });

    $('.puExpDate').inputmask('99/99');

    function updateSalesReturnValue() {
        var totalAmount = roundOff = netPay = 0;
        jQuery('.itemReturnValue').each(function(i, obj) {
            iTotal      =   jQuery(this).text();
            if(parseFloat(iTotal)>0) {
                totalAmount  += parseFloat(iTotal);
            }
        });

        roundOff = parseFloat(Math.round(totalAmount)-totalAmount);
        netPay = parseFloat(totalAmount+roundOff);

        jQuery('.totalAmount').text(totalAmount.toFixed(2));
        jQuery('.roundOff').text(roundOff.toFixed(2));
        jQuery('.netPay').text(netPay.toFixed(2));
    }

    function getPatientTypes(patientType) {
        if(parseInt(patientType)===1) {
            return 'GEN';
        } else if(parseInt(patientType)===2) {
            return 'IPS';
        } else if(parseInt(patientType)===3) {
            return 'OPS';
        }
    }

    /*chart functionality */

    if( $('#dbContainer').length>0 ) {
      var saleDates = [];
      var saleAmounts = [];
      // load daysales initially
      jQuery.ajax("/async/day-sales",{
          method:"GET",
          success: function(apiResponse) {
            if(apiResponse.status==='success') {
              var daySales = apiResponse.response.daySales[0];
              var cashSales = parseInt(daySales.cashSales);
              var creditSales = parseInt(daySales.creditSales);
              var cardSales = parseInt(daySales.cardSales);
              var salesReturns = parseInt(daySales.returnamount);
              var salesReturnsCredit = parseInt(daySales.creditReturnAmount);
              var salesReturnsCashCard = parseInt(daySales.cashCardReturnAmount);
              var totalSales = parseInt(cashSales+creditSales+cardSales);
              var netSales = parseInt(totalSales-salesReturns);
              var cashInHand = cashSales-salesReturnsCashCard;
              $('#ds-cashsale').text(cashSales.toFixed(2));
              $('#ds-cardsale').text(cardSales.toFixed(2));
              $('#ds-creditsale').text(creditSales.toFixed(2));
              $('#ds-totals').text(totalSales.toFixed(2));
              // $('#ds-returns').text(salesReturns.toFixed(2));
              $('#ds-cash-returns').text(salesReturnsCashCard.toFixed(2));
              $('#ds-credit-returns').text(salesReturnsCredit.toFixed(2));
              $('#ds-netsale').text(netSales.toFixed(2));
              $('#ds-cashinhand').text(cashInHand.toFixed(2));                                          
            }
          },
          error: function(e) {
            alert('An error occurred while fetching Day Sales');
          }
      });
    }

    $('#sfGraphReload').on("click", function(e){
      var curMonth = $('#sgf-month').val();
      var curYear =  $('#sgf-year').val();
      $('#saleMonth').val(curMonth);
      $('#saleYear').val(curYear);
      monthWiseSales();
    });

/*************************************** Inward Material Entry JS ************************************************/
    if( $('#inwardEntryForm').length>0 ) {

      jQuery('.inwRcvdQty, .inwFreeQty, .inwItemRate, .inwItemDiscount').on("blur",function(e){
        var idArray = $(this).attr('id').split('_');
        var rowId = idArray[1];
        updateInwardItemRow(rowId);
      });

      jQuery('.inwItemTax').on("change", function(){
        var idArray = $(this).attr('id').split('_');
        var rowId = idArray[1];
        $('#inwItemTaxAmt_'+rowId).attr('data-rate', parseFloat($(this).val()).toFixed(2) );

        updateInwardItemRow(rowId);
      });

      jQuery('#supplierID').on('change', function(e){
        var supplierCode = $(this).val();
        jQuery.ajax("/async/get-supplier-details?c="+supplierCode, {
          method:"GET",
          success: function(apiResponse) {
            if(apiResponse['status'] === 'success') {
              var supplierDetails = apiResponse.response.supplierDetails;
              var companyState = $('#cs').val();
              $('#supplierState').val(supplierDetails.stateCode);
              $('#supplierGSTNo').val(supplierDetails.tinNo);
              if(companyState == supplierDetails.stateCode) {
                $('#supplyType').val('intra');
              } else {
                $('#supplyType').val('inter');
              }
            }
          },
          error: function(e) {
            alert('An error occurred while fetching Supplier Information.');
          }
        });  
      });

      /*********************** functions for inward entry **********************/
      function updateInwardItemRow(rowId) {
        
        var totTaxableAmount = totalTaxAmount = finalAmount = 0;
        var netPay = roundedNetPay = 0;
        
        var rcvdQty = parseFloat( returnNumber($('#inwRcvdQty_'+rowId).val()) );
        var freeQty = parseFloat( returnNumber($('#inwFreeQty_'+rowId).val()) );
        var itemRate = parseFloat( returnNumber($('#inwItemRate_'+rowId).val()) );
        var inwItemDiscount = parseFloat( returnNumber($('#inwItemDiscount_'+rowId).val()) );
        var inwItemTax = parseFloat( returnNumber($('#inwItemTax_'+rowId).val()) );

        var billedQty = rcvdQty - freeQty;
        var inwItemGrossAmount = parseFloat( returnNumber(billedQty*itemRate) );
        var inwItemAmount = parseFloat( returnNumber(inwItemGrossAmount-inwItemDiscount) );
        var inwItemTaxAmount = parseFloat((inwItemAmount * inwItemTax) / 100).toFixed(2);

        $('#inwBillQty_'+rowId).val(billedQty);
        $('#inwItemGrossAmount_'+rowId).val(inwItemGrossAmount);
        $('#inwItemAmount_'+rowId).val(inwItemAmount);
        $('#inwItemTaxAmt_'+rowId).val(inwItemTaxAmount);        

        jQuery('.inwItemAmount').each(function(i, obj) {
          if(jQuery(this).val().length === 0) {
            iTotal = 0;
          } else {
            iTotal = parseFloat(returnNumber(jQuery(this).val()));
          }
          if( iTotal > 0 ) {
            totTaxableAmount  += iTotal;
          }
        });

        jQuery('.inwItemTaxAmount').each(function(i, obj) {
          if(jQuery(this).val().length === 0) {
            iTotal = 0;
          } else {
            iTotal = parseFloat(returnNumber(jQuery(this).val()));
          }
          if( iTotal > 0 ) {
            totalTaxAmount  += iTotal;
          }
        });        

        netPay = parseFloat(totTaxableAmount + totalTaxAmount);
        roundedNetPay = Math.round(netPay);
        finalAmount = netPay-parseFloat(roundedNetPay.toFixed(2));

        $('#inwItemsTotal').text(totTaxableAmount.toFixed(2));
        $('#inwItemTaxAmount').text(totalTaxAmount.toFixed(2));
        $('#roundOff').text(finalAmount.toFixed(2));
        $('#inwNetPay').text(roundedNetPay);

        updateGSTSummary();

        // console.log(rcvdQty, freeQty, billedQty, itemRate, inwItemGrossAmount, inwItemDiscount);
      }

      function updateGSTSummary() {
        var taxValues = [];
        jQuery('.inwTaxPercents').each(function(i, obj) {
          var taxRate = $(this).val();
          var taxCode = $(this).attr('id');
          var totalTax = totalTaxable = 0;
          $("input[data-rate='"+taxRate+"']").each(function(i, obj){
            if(parseFloat( returnNumber($(this).val()) ) > 0 ) {
              var idArray = $(this).attr('id').split('_');
              var rowId = idArray[1];
              var thisGrossAmount = $('#inwItemAmount_'+rowId).val();

              totalTaxable = parseFloat(totalTaxable) + parseFloat(thisGrossAmount); 
              totalTax = parseFloat(totalTax) + parseFloat($(this).val());
            }
          });

          $("#taxAmount_"+taxCode).val(totalTax.toFixed(2));

          var splitTax = parseFloat(totalTax/2).toFixed(2);
          $('#taxable_'+taxCode+'_cgst_value').text(splitTax);
          $('#taxable_'+taxCode+'_sgst_value').text(splitTax);
          $('#taxable_'+taxCode+'_amount').text(totalTaxable);

          // var array = [];
          // $('.inwItemTax option[value="'+taxRate+'"]').each(function() {
          //     console.log($(this).val(), $(this).text());
          //     array[ $(this).val()] = $(this).text();
          // });
        });
      }

      jQuery('#inwCancel').on('click', function(e){
        if(confirm("Are you sure. You want to close this page?") == true) {
          window.location.href = '/purchase/list';
        } else {
          return false;
        }
        e.preventDefault();
      });
    }
  /*************************************** End of Inward Material Entry JS ************************************************/

  if( $('#sendOtpBtn').length>0 ) {
    $('#sendOtpBtn').on('click', function(){
      sendOTP();
    });
    $('#submit-fp').on('click', function(e){
      var userId = $('#emailID').val();
      var otp = $('#pass-fp').val();
      var newPassword = $('#newpass-fp').val();
      if(userId === '' || otp === '' || newPassword === '') {
        alert('Userid, OTP and New password fields are mandatory to Reset your password.');
        $('#emailID').focus();
        return false;
      }
      /* hit server to reset the password */
      jQuery.ajax("/reset-password", {
        method:"POST",
        data: $('#forgotPassword').serialize(),
        success: function(response) {
          if(response.status===false) {
            alert(response.error);
            window.location.href = '/forgot-password';
          } else {
            alert('Password has been changed successfully.');
            window.location.href = '/login';
          }
        },
        error: function(e) {
          alert('Unable to reset password. Please try again.');
          window.location.href = '/forgot-password';
        }
      });

      e.preventDefault();
    });
  }

  if( $('#uploadInventory').length>0 ) {
    $('#uploadInventory').on('click', function(e){
      if($('#fileName').val().length) {
        $(this).attr('disabled', true);
        $('#invRefresh').attr('disabled', true);
        $('#reloadInfo').show();
        $('#frmInventoryUpload').submit();
      } else {
        alert('Please choose a file to upload.');
        return false;
      }
      e.preventDefault();
    });
  }
}

function sendOTP(fpType) {
  var userId = $('#emailID').val();
  var emailFilter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
  if(!emailFilter.test(userId)) {
    $('#emailID').focus();
    alert('Please enter a valid username.');
    return false;
  }

  /* hit server to get the OTP */
  jQuery.ajax("/send-otp", {
    method:"POST",
    data: $('#forgotPassword').serialize(),
    success: function(response) {
      if(response.status===false) {
        alert(response.message);
        return false;
      }
      if(response.status === true) {
        $('#success-msg-fp').show();
        $('#success-msg-fp').html(response.message);
        $('#pass-fp').attr('disabled', false);
        $('#submit-fp').attr('disabled', false);
        $('#newpass-fp').attr('disabled', false);
        $('#sendOtpBtn').attr('disabled', true);
        if(fpType==='resend') {
          alert('OTP has been resent successfully. Please use latest code to reset your password.');
        }
      } else {
        $('#error-msg-fp').show();
        $('#error-msg-fp').html(response.message);
        if(fpType==='resend') {
          alert('Unable to resend OTP.');
        }        
      }
    },
    error: function(e) {
      $('#emailID').focus();
      alert('An error occurred while processing your request.');
      return false;
    }
  });
}

/*function updateInwardItemAmount(rowId) {
  var itemRate = parseFloat($('#inwItemRate_'+rowId).val());
  var billedQty = parseFloat($('#inwBillQty_'+rowId).val());
  var itemAmount = 0;

  if(itemRate>0 && billedQty>0) {
    itemAmount = parseFloat(itemRate*billedQty);
    $('#inwItemAmount_'+rowId).val(itemAmount.toFixed(2));
    $('#inwItemGrossAmount_'+rowId).val(itemAmount.toFixed(2));    
  } else {
    $('#inwItemAmount_'+rowId).val('');
    $('#inwItemGrossAmount_'+rowId).val('');    
  }
}

function updateInwardItemTaxAmount(rowId, taxPercent) {
  var inwDiscountAmount = parseFloat($('#inwItemDiscount_'+rowId).val());
  if( isNaN(inwDiscountAmount) ) {
    inwDiscountAmount = 0;
  }

  var taxPercent = $('#inwItemTax_'+rowId).val();
  var itemValue = parseFloat($('#inwItemRate_'+rowId).val())*parseFloat($('#inwBillQty_'+rowId).val());
  var itemValueAfterDiscount = parseFloat(itemValue - inwDiscountAmount);
  var taxAmount = (itemValueAfterDiscount*taxPercent)/100;

  $('#inwItemTaxAmt_'+rowId).val(taxAmount);
  $('#inwItemTaxAmt_'+rowId).attr('data-rate', taxPercent);
}

function updateInwardItemsTotal(rowId) {
  var itemsTotal = billAmount = 0;
  var grandTotal = 0;
  var adjustments = 0;
  var netPay = 0;
  var roundedNetPay = 0;

  var adjustments = parseFloat($('#inwAdjustment').val());
  var shippingCharges = parseFloat($('#inwShippingCharges').val());

  jQuery('.inwItemAmount').each(function(i, obj) {
    iTotal = jQuery(this).val();
    if(parseFloat(iTotal)>0) {
      billAmount  += parseFloat(iTotal);
    }
  });

  grandTotal = billAmount + shippingCharges;

  // var inwItemDiscountVal = parseFloat($('#inwDiscountValue').text());
  // if(isNaN(inwItemDiscountVal)) {
  //   var finalAmount = billAmount;
  // } else {
  //   var finalAmount = billAmount-inwItemDiscountVal;
  // }

  if( parseFloat($('#inwAddlTaxes').val())>0 ) {
    var inwAddlTaxes = parseFloat($('#inwAddlTaxes').val());
  } else {
    var inwAddlTaxes = 0;
  }
  grandTotal += inwAddlTaxes;

  jQuery('.taxAmounts').each(function(i, obj) {
    iTotal = parseFloat(jQuery(this).text());
    if( iTotal > 0 ) {
      grandTotal  += parseFloat(iTotal);
    }
  });

  $('#inwItemsTotal').text(billAmount.toFixed(2));
  $('#inwTotalAmount').text(grandTotal.toFixed(2));

  if(isNaN(adjustments)) {
    adjustments = 0;
  }

  netPay = grandTotal + parseFloat(adjustments);
  roundedNetPay = Math.round(netPay);
  var finalAmount = netPay-parseFloat(roundedNetPay.toFixed(2));

  $('#roundOff').text(finalAmount.toFixed(2));
  $('#inwNetPay').text(roundedNetPay);
}

function updateInwardTaxAmounts() {
  var taxValues = [];
  jQuery('.taxPercents').each(function(i, obj) {
    var taxRate = $(this).val();
    var taxCode = $(this).attr('id');
    var totalTax = 0;
    $("input[data-rate='"+taxRate+"']").each(function(i, obj){
      if(parseFloat($(this).val())>0 ) {
        totalTax = parseFloat(totalTax) + parseFloat($(this).val());
      }
    });

    $("#taxAmount_"+taxCode).text(totalTax.toFixed(2));
  });
}*/

function fetchInwardItemHistory(rowId) {
  var itemName = $('#itemName_'+rowId).val();
  var batchNo = $('#batchNo_'+rowId).val();
  if(itemName.length>0 && parseInt(batchNo.length)===0) {
    jQuery.ajax("/async/check-inward-item", {
      method:"POST",
      data: 'iN='+itemName,
      success: function(response) {
        if(response.status === 'success') {
          var historyDetails = response.response.history[0];
          $('#batchNo_'+rowId).val(historyDetails.batchNo.split('_')[1]);
          $('#expDate_'+rowId).val(historyDetails.expdateMonth + '/' + historyDetails.expdateYear);
          $('#mrp_'+rowId).val(historyDetails.mrp);
          $('#inwItemRate_'+rowId).val(historyDetails.itemRate);
          $('#inwItemTax_'+rowId).val(parseInt(historyDetails.taxPercent));
        }
      },
      error: function(e) {
        alert('Unable to fetch updated information. Please try again.');
      }
    });
  }
}

function printSalesBill(bill_no) {
  var printUrl = '/print-sales-bill?billNo='+bill_no;
  window.open(printUrl, "_blank", "scrollbars=yes,titlebar=yes,resizable=yes,width=400,height=400");
}

function printSalesBillSmall(bill_no) {
  var printUrl = '/print-sales-bill-small?billNo='+bill_no;
  window.open(printUrl, "_blank", "scrollbars=yes,titlebar=yes,resizable=yes,width=400,height=400");
}

function printSalesBillGST(bill_no) {
  var printUrl = '/print-sales-bill-gst?billNo='+bill_no;
  window.open(printUrl, "_blank", "scrollbars=yes,titlebar=yes,resizable=yes,width=400,height=400");
}

function printGrn(grnCode) {
  var printUrl = '/print-grn/'+grnCode;
  window.open(printUrl,"_blank","scrollbars=yes,titlebar=yes,resizable=yes,width=400,height=400");
}

function printSalesReturnBill(returnCode) {
  var printUrl = '/print-sales-return-bill?returnCode='+returnCode;
  window.open(printUrl, "_blank", "scrollbars=yes, titlebar=yes, resizable=yes, width=400, height=400");
}

function resetFilter(url) {
  if(url !== '') {
    window.location.href=url;
  }
}

function monthWiseSales() {
  var sgfMonth = $('#saleMonth').val();
  var sgfYear = $('#saleYear').val();
  var saleDate = []
  var saleAmounts = [];
  var totCashSales = totCreditSales = totCardSales = totSales = totSalesReturns = totNetSales = 0;
  var totCardReturns = totCashCardReturns = 0; 
  jQuery.ajax("/async/monthly-sales?saleMonth="+sgfMonth+'&saleYear='+sgfYear, {
    method:"GET",
    success: function(apiResponse) {
      if(apiResponse.status==='success') {
        jQuery.each(apiResponse.response.daywiseSales, function (index, saleDetails) {
          var dateFormat = new Date(saleDetails.tranDate+'T12:00:30z');
          var amount = (
                          parseInt(saleDetails.cardSales)+
                          parseInt(saleDetails.cashSales)+
                          parseInt(saleDetails.creditSales)
                        );
          saleDate.push(dateFormat.getDate());
          saleAmounts.push(amount);

          totCashSales += parseFloat(saleDetails.cashSales);
          totCardSales += parseFloat(saleDetails.cardSales);
          totCreditSales += parseFloat(saleDetails.creditSales);
          totSales += ( parseFloat(saleDetails.cashSales) + parseFloat(saleDetails.cardSales) + parseFloat(saleDetails.creditSales) );
          totSalesReturns += parseFloat(saleDetails.returnamount);
          totCardReturns += parseFloat(saleDetails.creditReturnAmount);
          totCashCardReturns += parseFloat(saleDetails.cashCardReturnAmount);
        });

        totNetSales = parseFloat(totSales) - parseFloat(totSalesReturns);

        $('#cs-cashsale').text(totCashSales.toFixed(2));
        $('#cs-cardsale').text(totCardSales.toFixed(2));
        $('#cs-creditsale').text(totCreditSales.toFixed(2));
        $('#cs-totals').text(totSales.toFixed(2));
        $('#cs-netsale').text(totNetSales.toFixed(2));
        $('#cs-returns').text(totSalesReturns.toFixed(2));
        $('#cs-returns-credit').text(totCardReturns.toFixed(2));
        $('#cs-returns-cash').text(totCashCardReturns.toFixed(2));

        // $('#ds-cashinhand').text(cashInHand.toFixed(2));
      }

      $('#salesGraph').empty();
      $('#salesGraph').jqplot([saleAmounts,saleDate], {
        title:'',
        seriesDefaults:{
          showMarker: true,
          renderer:$.jqplot.BarRenderer,
          pointLabels:{
           show:true
          },
          rendererOptions: {
            varyBarColor: true
          },          
          showLine: true
        },
        axes:{
          xaxis:{
            renderer: $.jqplot.CategoryAxisRenderer,
            ticks: []
          },
          yaxis: {
            showTicks: true,
          }
        },
        grid: {
          drawBorder: false,
          shadow: false
        },
        /*
        legend: {
          show: true,
          location: 'n', 
          placement: 'outside',          
        }*/
      });  
    },
    error: function(e) {
      alert('An error occurred while loading Monthwise Sales');
    }
  });
}

function returnNumber(val) {
  if(isNaN(val) || val.length === 0) {
    return 0;
  }
  return val;
}

jQuery(document).ready(function(){
  initializeJS();
  if( $('#dbContainer').length>0 && ($('#monthwiseSales').length>0 || $('#salesDayGraph').length>0)) {
    monthWiseSales();
  }
});