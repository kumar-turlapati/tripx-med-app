<?php

namespace Atawa;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Cookie;
use Atawa\Template;
use Atawa\Utilities;

ini_set('date.timezone', 'Asia/Kolkata');

if(Utilities::is_session_started() === FALSE) session_start();

class Framework {

  protected $matcher;
  protected $resolver;
  protected $template;
  protected $response;

  public function __construct(UrlMatcher $matcher, ControllerResolver $resolver) {
    $this->matcher = $matcher;
    $this->resolver = $resolver;
    $this->template = new Template(__DIR__.'/../Layout/');
    $this->response = new Response;
  }

  public function handle(Request $request) {
    $this->matcher->getContext()->fromRequest($request);
    $path = $request->getPathInfo();

    # validate access token before loading any route except /login
    if($path !== '/login' && $path !== '/forgot-password' && $path !== '/send-otp' && $path !== '/reset-password') {
      Utilities::check_access_token();

      # check ACL.
      $role_id = isset($_SESSION['utype'])&&$_SESSION['utype']>0?$_SESSION['utype']:0;
      Utilities::acls($role_id, $path);          
    }

    try {
      $request->attributes->add($this->matcher->match($path));
      $controller = $this->resolver->getController($request);
      $arguments = $this->resolver->getArguments($request, $controller);
      $controller_response = call_user_func_array($controller, $arguments);
      if(is_array($controller_response) && count($controller_response)>0) {
        $controller_output = $controller_response[0];
        if(is_array($controller_response[1]) && count($controller_response[1]) > 0) {
          $view_vars = $controller_response[1];
        } else {
          $view_vars = array();
        }
      } else {
        $controller_output = $controller_response;
        $view_vars = array();
      }

      if(!isset($view_vars['render'])) {
        $view_vars['render'] = true;
      }

      if($path === '/login') {
        $page_content = $this->template->render_view('login', array('content'=>$controller_output, 'view_vars'=>$view_vars));
      } elseif($view_vars['render']===true) {
        $page_content = $this->template->render_view('layout', array('content'=>$controller_output, 'view_vars'=>$view_vars));
      } else {
        $page_content = $controller_output;
      }
      return new Response($page_content);
    } catch (ResourceNotFoundException $e) {
      // dump($e);
      return new Response('Not Found', 404);
    } catch (\Exception $e) {
      dump($e);
      return new Response('An error occurred', 500);
    }
  }
}
