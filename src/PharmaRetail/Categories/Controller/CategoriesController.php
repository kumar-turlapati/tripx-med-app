<?php 

namespace PharmaRetail\Categories\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Atawa\Constants;
use Atawa\Template;

use PharmaRetail\Categories\Model\Categories;

class CategoriesController
{
	protected $views_path;

	public function __construct() 
    {
		$this->views_path = __DIR__.'/../Views/';
	}

    public function listCategories(Request $request)
    {

        $products_list = $search_params = $categories = array();
        $total_pages = $total_records = $record_count = $page_no = 0 ;
        $slno = $to_sl_no = $page_links_to_start =  $page_links_to_end = 0;
        $page_success = $page_error = '';

        $category_api_call = new Categories;
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

        if(count($request->request->all()) > 0) {
            $search_params = $request->request->all();
        } elseif($request->get('catname')) {
            $search_params['catname'] = $request->get('catname');
        } else {
            $search_params = array();
        }

        $categories_list = $category_api_call->get_categories($page_no, $per_page, $search_params);
        $api_status = $categories_list['status'];

        # check api status
        if($api_status) {
            # check whether we got products or not.
            if(count($categories_list['categories']) >0) {
                $categories = $categories_list['categories'];
            } else {
                $page_error = $categories_list['apierror'];
            }

        } else {
            $page_error = $categories_list['apierror'];
        }

        // build variables
        $controller_vars = array(
            'page_title' => 'Categories',
            'icon_name' => 'fa fa-list',
        );
        $template_vars = array(
            'categories' => $categories,
            'sl_no' => 1,
            'search_params' => $search_params,
            'page_error' => $page_error,
            'page_success' => $page_success,            
        );

        // render template
        $template = new Template($this->views_path);
        return array($template->render_view('categories-list', $template_vars), $controller_vars);        
    }

}