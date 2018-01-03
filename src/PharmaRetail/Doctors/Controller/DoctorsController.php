<?php 

namespace PharmaRetail\Doctors\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;
use Atawa\Flash;

use PharmaRetail\Doctors\Model\Doctor;

class DoctorsController
{
	protected $views_path;

	public function __construct() 
    {
		$this->views_path = __DIR__.'/../Views/';
	}

    public function doctorCreateAction(Request $request)
    {
        $errors = array();
        $page_error = $page_success = $doctor_code = '';
        $update_flag=false;
        $submitted_data = $doctor_details=array();
        
        # initiate doctor model.
        $doctors_api_call = new Doctor;

        if($request->get('doctorCode') && $request->get('doctorCode')!='') {
            $doctor_code = Utilities::clean_string($request->get('doctorCode'));
            $doctor_api_response = $doctors_api_call->get_doctor_details($doctor_code);
            if($doctor_api_response['status']===true) {
                $doctor_details = $doctor_api_response['doctorDetails'];
                $update_flag = true;
            } else {
                $flash = new Flash;
                $flash->set_flash_message("Invalid Doctor Code.",1);
                Utilities::redirect('/doctors/list');
            }
            $page_title = 'Update Doctor Information '.(isset($doctor_details['doctorName'])?' - '.$doctor_details['doctorName']:'');
            $btn_label = 'Update Doctor';
        } else {
            $btn_label = 'Save';
            $page_title = 'Doctors';
        }

        if(count($request->request->all()) > 0) {
            $submitted_data = $request->request->all();
            if(count($doctor_details)>0) {
                $new_doctor = $doctors_api_call->updateDoctor($request->request->all(), $doctor_code);             
            } else {
                $new_doctor = $doctors_api_call->createDoctor($request->request->all());
            }

            $status = $new_doctor['status'];
            if($status === false) {
                if(isset($new_doctor['errors'])) {
                    $errors     =   $new_doctor['errors'];
                } elseif(isset($new_doctor['apierror'])) {
                    $page_error =   $new_doctor['apierror'];
                }
            } elseif($update_flag===false) {
                $page_success   = 'Doctor information added successfully with code ['.$new_doctor['doctorCode'].']';
                $submitted_data = array();
            } else {
                $page_success   = 'Doctor information updated successfully';
            }
        } elseif(count($doctor_details)>0) {
            $submitted_data = $doctor_details;
        }

        // prepare form variables.
        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'status' =>  Constants::$RECORD_STATUS,
            'submitted_data' => $submitted_data,
            'errors' => $errors,
            'btn_label' => $btn_label,
            'doctor_code' => $doctor_code
        );

        // build variables
        $controller_vars = array(
            'page_title' => $page_title,
            'icon_name' => 'fa fa-user-md',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('doctor-create', $template_vars), $controller_vars);
    }

    public function doctorRemoveAction(Request $request)
    {
        $flash = new Flash();

        if($request->get('doctorCode') && $request->get('doctorCode')!='') {
            $doctor_code = Utilities::clean_string($request->get('doctorCode'));
        } else {
            $flash->set_flash_message("Invalid Doctor Code",1);
            Utilities::redirect('/doctors/list');
        }

        # initiate doctor model.
        $doctors_api_call = new Doctor;           
        
        $api_response = $doctors_api_call->get_doctor_details($doctor_code);
        if($api_response['status']===true) {
            $doctor_details = $api_response['doctorDetails'];
        } else {
            $flash->set_flash_message("Invalid Doctor Code",1);
            Utilities::redirect('/doctors/list');               
        }

        $remove_response = $doctors_api_call->removeDoctor($doctor_code);
        if($remove_response['status']===true) {
            $flash_message = "Doctor details of '$doctor_details[doctorName]' removed successfully";
            $flash->set_flash_message($flash_message);            
        } else {
            $flash_message = "Unable to remove Doctor details";
            $flash->set_flash_message($flash_message,1);
        }
        
        Utilities::redirect('/doctors/list');
    }

    public function doctorListAction(Request $request)
    {

        $doctors_list = $doctors= $search_params = array();

        $total_pages = $total_records = $record_count = $page_no = 0 ;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_success = $page_error = '';
        $search_params = array('pagination'=>'yes');

        $doctors_api_call = new Doctor;
        if( $request->get('pageNo') ) {
            $page_no = $request->get('pageNo');
        } else {
            $page_no = 1;
        }

        if( $request->get('perPage') ) {
            $per_page = $request->get('perPage');
        } else {
            $per_page = 100;
        }

        $doctors_list = $doctors_api_call->get_doctors($page_no,$per_page,$search_params);
        $api_status = $doctors_list['status'];

        # check api status
        if($api_status) {

            # check whether we got products or not.
            if(count($doctors_list['doctors']) >0) {
                $slno = Utilities::get_slno_start(count($doctors_list['doctors']),$per_page,$page_no);
                $to_sl_no = $slno+$per_page;
                $slno++;

                if($page_no<=3) {
                    $page_links_to_start = 1;
                    $page_links_to_end = 10;
                } else {
                    $page_links_to_start = $page_no-3;
                    $page_links_to_end = $page_links_to_start+10;            
                }

                if($doctors_list['total_pages']<$page_links_to_end) {
                    $page_links_to_end = $doctors_list['total_pages'];
                }

                if($doctors_list['record_count'] < $per_page) {
                    $to_sl_no = ($slno+$doctors_list['record_count'])-1;
                }

                $doctors = $doctors_list['doctors'];
                $total_pages = $doctors_list['total_pages'];
                $total_records = $doctors_list['total_records'];
                $record_count = $doctors_list['record_count'];
            } else {
                $page_error = $doctors_list['apierror'];
            }

        } else {
            $page_error = $doctors_list['apierror'];
        }               

         // prepare form variables.
        $template_vars = array(
            'page_error' => $page_error,
            'page_success' => $page_success,
            'doctors' => $doctors,
            'total_pages' => $total_pages ,
            'total_records' => $total_records,
            'record_count' =>  $record_count,
            'sl_no' => $slno,
            'to_sl_no' => $to_sl_no,
            'search_params' => $search_params,            
            'page_links_to_start' => $page_links_to_start,
            'page_links_to_end' => $page_links_to_end,
            'current_page' => $page_no,
        );

        // build variables
        $controller_vars = array(
            'page_title' => 'Doctors',
            'icon_name' => 'fa fa-user-md',
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('doctors-list', $template_vars), $controller_vars);       
    }

}