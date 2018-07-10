<?php 

namespace PharmaRetail\Discount\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Atawa\Utilities;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Discount\Model\Discount;

class DiscountController
{
	protected $views_path,$flash,$disc_model;

	public function __construct() {
		$this->views_path = __DIR__.'/../Views/';
    $this->flash = new Flash();
    $this->disc_model = new Discount();
	}

  public function addDiscount(Request $request) {

    $submitted_data = $form_errors = array();
    $yes_no_options = array(''=>'Select','0'=>'No','1'=>'Yes');

    if( count($request->request->all())>0 ) {
      $submitted_data = $request->request->all();
      $form_validation = $this->_validate_form_data($submitted_data);
      if($form_validation['status']===true) {
        $cleaned_params = $form_validation['cleaned_params'];
        $result = $this->disc_model->add_discount_percent($cleaned_params);
        if($result['status']) {
          $discount_code = $result['discountCode'];
          $this->flash->set_flash_message('Discount percentage added successfully with code `'.$discount_code.'`');
        } else {
          $page_error = $result['apierror'];
          $this->flash->set_flash_message($page_error, 1);
        }
        Utilities::redirect('/discount-percent/add');
      } else {
        $form_errors = $form_validation['errors'];
      }
    }

    // prepare form variables.
    $template_vars = array(
      'yes_no_options' => $yes_no_options,
      'submitted_data' => $submitted_data,
      'form_errors' => $form_errors,
    );

    // build variables
    $controller_vars = array(
      'page_title' => 'Discount Percentages',
      'icon_name' => 'fa fa-percent',
    );

    // render template
    $template = new Template($this->views_path);
    return array($template->render_view('add-discount',$template_vars),$controller_vars);
  }

  public function updateDiscount(Request $request) {

    $submitted_data = $errors = [];
    $discount_code = $request->get('discountCode');
    $discount_percent_details = $this->disc_model->get_discount_percent_details($discount_code);
    if($tax_details['status']===false) {
      $this->flash->set_flash_message('Invalid discount code', 1);
      Utilities::redirect('/discount-percent/list');
    }

    $submitted_data = $discount_percent_details['discount_percent_details'];
    if( count($request->request->all())>0 ) {
      $submitted_data = $request->request->all();
      $form_validation = $this->_validate_form_data($submitted_data);
      if($form_validation['status']===true) {
        $cleaned_params = $form_validation['cleaned_params'];
        $result = $this->tax_model->update_tax($cleaned_params,$discount_code);
        if($result['status']===true) {
          $this->flash->set_flash_message('Tax rate updated successfully');
        } else {
          $page_error = $result['apierror'];
          $this->flash->set_flash_message($page_error, 1);
        }
        Utilities::redirect('/taxes/list');
      } else {
        $form_errors = $form_validation['errors'];
      }
    }

    // prepare form variables.
    $template_vars = array(
      'yes_no_options' => array(''=>'Select','0'=>'No','1'=>'Yes'),
      'submitted_data' => $submitted_data,
      'discountCode' => $discount_code,
    );

    // build variables
    $controller_vars = array(
      'page_title' => 'Discount Percentages',
      'icon_name' => 'fa fa-percent',
    );

    // render template
    $template = new Template($this->views_path);
    return array($template->render_view('update-discount',$template_vars),$controller_vars);
  }

  public function listDiscounts(Request $request) {

    $percents = $this->disc_model->list_discount_percents();

    // prepare form variables.
    $template_vars = array(
      'percents' => $percents['percents'],
    );

    // build variables
    $controller_vars = array(
      'page_title' => 'Discount Percentages',
      'icon_name' => 'fa fa-percent',
    );

    // render template
    $template = new Template($this->views_path);
    return array($template->render_view('discounts-list',$template_vars),$controller_vars);
  }

  /***************************** Private functions should go here ***************************/
  private function _validate_form_data($form_data=array()) {
    $cleaned_params = $errors = [];

    $tv_from = isset($form_data['tvFrom']) ? Utilities::clean_string($form_data['tvFrom']) : 0;
    $tv_to = isset($form_data['tvTo']) ? Utilities::clean_string($form_data['tvTo']) : 0;
    $discount_percent = isset($form_data['discountPercent']) ? Utilities::clean_string($form_data['discountPercent']) : 0;
    $coupon_code = isset($form_data['couponCode']) ? Utilities::clean_string($form_data['couponCode']) : '';
    $is_allowed_for_olo = isset($form_data['isAllowedForOlo']) ? Utilities::clean_string($form_data['isAllowedForOlo']) : 0;
    $is_allowed_for_mao = isset($form_data['isAllowedForMao']) ? Utilities::clean_string($form_data['isAllowedForMao']) : 0;

    if(!is_numeric($tv_from) || $tv_from <= 0) {
      $errors['tvFrom'] = 'Minimum transaction value should be greater than zero.';
    } else {
      $cleaned_params['tvFrom'] = $tv_from;
    }
    if(!is_numeric($tv_to) || $tv_to <= 0) {
      $errors['tvTo'] = 'Maximum transaction value should be greater than zero.';
    } else {
      $cleaned_params['tvTo'] = $tv_to;
    }
    if(!is_numeric($discount_percent) || $discount_percent <=0 || $discount_percent > 100 ) {
      $errors['discountPercent'] = 'Invalid discount percentage.';
    } else {
      $cleaned_params['discountPercent'] = Utilities::clean_string($form_data['discountPercent']);
    }
    if($coupon_code !== '' && !ctype_alnum($coupon_code)) {
      $errors['couponCode'] = 'Invalid coupon code.';
    } else {
      $cleaned_params['couponCode'] = $coupon_code;
    }

    if(count($errors)>0) {
      return array(
        'status' => false,
        'errors' => $errors,
      );
    } else {
      $cleaned_params['isAllowedForOlo'] = $is_allowed_for_olo;
      $cleaned_params['isAllowerForMao'] = $is_allowed_for_mao;
      return array(
        'status' => true,
        'cleaned_params' => $cleaned_params,
      );
    }
  }
}