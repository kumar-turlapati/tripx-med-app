<?php 

namespace Misc\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Atawa\Utilities;
use Predis;

class MiscController
{
  public function indexAction(Request $request) {

    $redis_client = new Predis\Client(array(
      'scheme' => 'tcp',
      'host'   => 'localhost',
      'port'   => 6379,
    ));

    $autocomplete = new VictorSigma\RedCard\RedisAutocomplete($client);




    exit;
  }
}