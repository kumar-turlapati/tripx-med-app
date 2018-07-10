<?php

namespace Atawa;

use Atawa\Utilities;
use Atawa\Config\Config;

include_once __DIR__.'../../../libraries/fpdf181/fpdf.php';

class PDF extends FPDF {

  private static $pdf = null;
  private static $client_details = null;

  public static function getInstance() {
    if(self::$pdf == null) {
      self::$pdf = new PDF;
    }
    return self::$pdf;
  }

  public function get_client_details() {
    return Utilities::get_client_details();
  }

  public function Header() {
    if(isset($_SESSION['ln']) && $_SESSION['ln'] !== '') {
      $environment = Utilities::get_host_environment_key();
      $asset_urls = Config::get_assets_url();
      $logo_name = base64_decode($_SESSION['ln']);
      if($logo_name !== '') {
        $logo_path = $asset_urls[$_SESSION['bc']][$environment].'/'.$logo_name;
      } else {
        $logo_path = Utilities::get_site_url().'/assets/logo.png';
      }
      $this->Image($logo_path,10,6,50);
    }

    $client_details = $this->get_client_details();

    // Arial bold 15
    $this->SetFont('Arial','B',15);

    // Move to the right
    $this->Cell(70);

    // Title
    $this->Cell(30,-5,$client_details['businessName'],'',2,'L');

    $this->SetFont('Arial','B',8);    
    $this->Cell(100,14,$client_details['addr1'],'',2,'L');
    $this->Cell(100,-8,$client_details['addr2'],'',2,'L');
    $this->Cell(100,15,'Phone(s):'.$client_details['phones'],'',2,'L');
    $this->Cell(100,-8,'DL No.:'.$client_details['dlNo'],'',2,'L');
    if(isset($client_details['gstNo']) && $client_details['gstNo'] !== '') {
      $this->Cell(100,8,'GSTIN:'.$client_details['gstNo'],'',0,'R');
    }
    $this->Ln(6);
    $this->Cell(0,0,'','B',1);
    $this->Ln(6);   
  }

  // Page footer
  public function Footer() {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  }  

}