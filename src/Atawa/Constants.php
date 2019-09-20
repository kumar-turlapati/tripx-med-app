<?php

namespace Atawa;

class Constants
{

    public static $RECORD_STATUS = array(
        1 => 'Active',
        0 => 'Inactive',
    );

    public static $SALE_TYPES = array(
        'GEN' => 'General Sale',
        'OPS' => 'Outpatient Sale',
        'IPS' => 'Inpatient Sale',
    );

    public static $SALE_TYPES_NUM = array(
        1 => 'General Sale',
        2 => 'IP Sale',
        3 => 'OP Sale',
    );

    public static $SALE_TYPES_NUM1 = array(
        1 => 'GS',
        2 => 'IP',
        3 => 'OP',
    );    

    public static $SALE_TYPES_FORM = array(
        1   =>  'GEN',
        2   =>  'IPS',
        3   =>  'OPS',
    );      

    public static $PAYMENT_METHODS = array(
        0 => 'Cash',
        1 => 'Credit',
        2 => 'Credit Card',
    );

    public static $PAYMENT_METHODS_SHORT = array(
        0 => 'Cash',
        1 => 'Credit',
        2 => 'CC',
    );    

    public static $PAYMENT_METHODS_PURCHASE = array(
        0 => 'Cash',
        1 => 'Credit',
    );    

    public static $GENDERS = array(
        'm' => 'Male',
        'f' => 'Female',
        'o' => 'Others',
    );    

    public static $AGE_CATEGORIES = array(
        'years' => 'Years',
        'months' => 'Months',
        'days' => 'Days',
    );

    public static $SUPPLIER_TYPES = array(
        'phar' => 'Pharma',
        'gene' => 'General'
    );

    public static $VAT_PERCENTS = array(
        "0" => 'Nil',
        "5" => '5%',
        "14.5" => '14.5%'
    );

    public static $PATIENT_TYPES = array(
        1 => 'General',
        2 => 'In patient',
        3 => 'Out patient'
    );

    public static $SALE_MODES = array(
      '' => 'All',
      0 => 'Normal',
      1 => 'Package',
      2 => 'Internal/Self',
      3 => 'Aarogyasri',
      4 => 'Insurance',
    );

    public static $LOCATION_STATES = array(
      37 => 'Andhra Pradesh',
      29 => 'Karnataka',
      7 => 'Delhi',
      19 => 'West Bengal',
      33 => 'Tamil Nadu',
      9 => 'Uttar Pradesh',
      6 => 'Haryana',
      24 => 'Gujarat',
      27 => 'Maharashtra',
      2 => 'Himachal Pradesh',
      32 => 'Kerala',
      18 => 'Assam',
      8 => 'Rajasthan',
      10 => 'Bihar',
      1 => 'Jammu & Kashmir', 
      30 => 'Goa',
      23 => 'Madhya Pradesh',
      21 => 'Odisha',
      3 => 'Punjab',
      5 => 'Uttarakhand',
      11 => 'Sikkim',
      12 => 'Arunachal Pradesh',
      22 => 'Chhattisgarh',
      20 => 'Jharkhand',
      14 => 'Manipur',
      17 => 'Meghalaya',
      15 => 'Mizoram',
      13 => 'Nagaland',
      16 => 'Tripura',
      35 => 'Andaman and Nicobar Islands',
      4 => 'Chandigarh',
      26 => 'Dadra and Nagar Haveli',
      25 => 'Daman and Diu',
      31 => 'Lakshadweep',
      34 => 'Puducherry',
      36 => 'Telangana',      
    );

    public static $LOCATION_COUNTRIES = array(
      99 => 'India',
    );

    public static $PAYMENT_METHODS_RC = array(
      0 => 'Cash',
      1 => 'Credit / Debit Card',
      2 => 'Split Payment (Card + Cash)',
    );

    public static $PROMO_OFFER_CATEGORIES = array(
      'a' => 'Discount on an Item (Ex: 10% discount on Shirt)',
      'b' => 'Buy X Get Y Products (Ex: Buy 1 get 3, Buy 1 get 2 etc...)',
      'c' => 'Discount on Total Bill Value (Ex: 20% off on Bill Value > 1000)',
    );

    public static $PROMO_OFFER_CATEGORIES_DIGITS = array(
       0  => 'Discount on an Item (Ex: 10% discount on Shirt)',
       1  => 'Buy X Get Y Products (Ex: Buy 1 get 3, Buy 1 get 2 etc...)',
       2  => 'Discount on Total Bill Value (Ex: 20% off on Bill Value > 1000)',
    );

    public static $PROMO_OFFER_CATEGORIES_CRM = array(
       'email'  => 'Email',
       'mobile'  => 'Mobile',
       'emailmobile'  => 'Email or Mobile',
    );
}
