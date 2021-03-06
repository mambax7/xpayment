<?php
/*
 * Copyright (C) 2007 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
/**
 * Classes used to represent shipping types
 */

/**
 * Class that represents flat rate shipping
 *
 * info:
 * {@link http://code.google.com/apis/checkout/developer/index.html#tag_flat-rate-shipping}
 * {@link http://code.google.com/apis/checkout/developer/index.html#shipping_xsd}
 *
 */
class GoogleFlatRateShipping
{
    public $price;
    public $name;
    public $type = 'flat-rate-shipping';
    public $shipping_restrictions;

    /**
     * @param string $name  a name for the shipping
     * @param double $price the price for this shipping
     */
    public function __construct($name, $price)
    {
        $this->name  = $name;
        $this->price = $price;
    }

    /**
     * Adds a restriction to this shipping.
     *
     * @param GoogleShippingFilters $restrictions the shipping restrictions
     */
    public function AddShippingRestrictions($restrictions)
    {
        $this->shipping_restrictions = $restrictions;
    }
}

/**
 * Represents a merchant calculated shipping
 *
 * info:
 * {@link http://code.google.com/apis/checkout/developer/index.html#shipping_xsd}
 * {@link http://code.google.com/apis/checkout/developer/index.html#merchant_calculations_specifying}
 */
class GoogleMerchantCalculatedShipping
{
    public $price;
    public $name;
    public $type = 'merchant-calculated-shipping';
    public $shipping_restrictions;
    public $address_filters;

    /**
     * @param string $name  a name for the shipping
     * @param double $price the default price for this shipping, used if the
     *                      calculation can't be made for some reason.
     */
    public function __construct($name, $price)
    {
        $this->price = $price;
        $this->name  = $name;
    }

    /**
     * Adds a restriction to this shipping.
     *
     * @param GoogleShippingFilters $restrictions the shipping restrictions
     */
    public function AddShippingRestrictions($restrictions)
    {
        $this->shipping_restrictions = $restrictions;
    }

    /**
     * Adds an address filter to this shipping.
     *
     * @param GoogleShippingFilters $filters the address filters
     */
    public function AddAddressFilters($filters)
    {
        $this->address_filters = $filters;
    }
}

/**
 * Represents carrier calculated shipping
 */
class GoogleCarrierCalculatedShipping
{
    public $name;
    public $type = 'carrier-calculated-shipping';

    public $CarrierCalculatedShippingOptions = [];
    //    var $ShippingPackages = array();
    public $ShippingPackage;

    /**
     * @param string $name the name of this shipping
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param GoogleCarrierCalculatedShippingOption $option the option to be
     *                                                      added to the carrier calculated shipping
     */
    public function addCarrierCalculatedShippingOptions($option)
    {
        $this->CarrierCalculatedShippingOptions[] = $option;
    }

    /**
     * @param GoogleShippingPackage $package
     */
    public function addShippingPackage($package)
    {
        //      $this->ShippingPackages[] = $package;
        $this->ShippingPackage = $package;
    }
}

/**
 * Represents a shipping method for which Google Checkout will obtain
 * shipping costs for the order.
 */
class GoogleCarrierCalculatedShippingOption
{
    public $price;
    public $shipping_company;
    public $shipping_type;
    public $carrier_pickup;
    public $additional_fixed_charge;
    public $additional_variable_charge_percent;
    //    var $shipping_restrictions;
    //    var $address_filters;

    /**
     * @param double    $price                              the default shipping cost to be used if Google is
     *                                                      unable to obtain the shipping_company's shipping rate for
     *                                                      the option
     * @param string    $shipping_company                   the name of the shipping_company
     * @param string    $shipping_type                      the shipping option, valid values are here:
     *                                                      http://code.google.com/apis/checkout/developer/Google_Checkout_XML_API_Carrier_Calculated_Shipping.html#tag_shipping-type
     * @param float|int $additional_fixed_charge            a handling charge that will be
     *                                                      added to the total cost of the order if this shipping option is selected.
     *                                                      defaults to 0
     * @param float|int $additional_variable_charge_percent A percentage by which
     *                                                      the shipping rate will be adjusted. The value may be positive or
     *                                                      negative. defaults to 0.
     * @param string    $carrier_pickup                     Specifies how the package will be
     *                                                      transfered from the merchand to the shipper. Valid values are
     *                                                      "REGULAR_PICKUP", "SPECIAL_PICKUP", "DROP_OFF". Defaults to "DROP_OFF".
     */
    public function __construct(
        $price,
        $shipping_company,
        $shipping_type,
        $additional_fixed_charge = 0,
        $additional_variable_charge_percent = 0,
        $carrier_pickup = 'DROP_OFF'
    ) {
        $this->price            = (double)$price;
        $this->shipping_company = $shipping_company;
        $this->shipping_type    = trim($shipping_type);
        switch (strtoupper($carrier_pickup)) {
            case 'DROP_OFF':
            case 'REGULAR_PICKUP':
            case 'SPECIAL_PICKUP':
                $this->carrier_pickup = $carrier_pickup;
                break;
            default:
                $this->carrier_pickup = 'DROP_OFF';
        }
        if ($additional_fixed_charge) {
            $this->additional_fixed_charge = (double)$additional_fixed_charge;
        }
        if ($additional_variable_charge_percent) {
            $this->additional_variable_charge_percent = (double)$additional_variable_charge_percent;
        }
    }

    //    function AddShippingRestrictions($restrictions) {
    //      $this->shipping_restrictions = $restrictions;
    //    }
    //
    //    function AddAddressFilters($filters) {
    //      $this->address_filters = $filters;
    //    }
}

/**
 * Represents an individual package that will be shipped to the buyer.
 */
class GoogleShippingPackage
{
    public $width;
    public $length;
    public $height;
    public $unit;
    public $ship_from;
    public $delivery_address_category;

    /**
     * @param GoogleShipFrom $ship_from                 where the package ships from
     * @param double         $width                     the width of the package
     * @param double         $length                    the length of the package
     * @param double         $height                    the height of the package
     * @param string         $unit                      the unit used to measure the width/length/height
     *                                                  of the package, valid values "IN", "CM"
     * @param string         $delivery_address_category indicates whether the shipping
     *                                                  method should be applied to a residential or commercial address, valid
     *                                                  values are "RESIDENTIAL", "COMMERCIAL"
     */
    public function __construct(
        $ship_from,
        $width,
        $length,
        $height,
        $unit,
        $delivery_address_category = 'RESIDENTIAL'
    ) {
        $this->width  = (double)$width;
        $this->length = (double)$length;
        $this->height = (double)$height;
        switch (strtoupper($unit)) {
            case 'CM':
                $this->unit = strtoupper($unit);
                break;
            case 'IN':
            default:
                $this->unit = 'IN';
        }

        $this->ship_from = $ship_from;
        switch (strtoupper($delivery_address_category)) {
            case 'COMMERCIAL':
                $this->delivery_address_category = strtoupper($delivery_address_category);
                break;
            case 'RESIDENTIAL':
            default:
                $this->delivery_address_category = 'RESIDENTIAL';
        }
    }
}

/**
 * Represents the location from where packages will be shipped from.
 * Used with {@link GoogleShippingPackage}.
 */
class GoogleShipFrom
{
    public $id;
    public $city;
    public $country_code;
    public $postal_code;
    public $region;

    /**
     * @param string $id           an id for this address
     * @param string $city         the city
     * @param string $country_code a 2-letter iso country code
     * @param string $postal_code  the zip
     * @param string $region       the region
     */
    public function __construct($id, $city, $country_code, $postal_code, $region)
    {
        $this->id           = $id;
        $this->city         = $city;
        $this->country_code = $country_code;
        $this->postal_code  = $postal_code;
        $this->region       = $region;
    }
}

/**
 *
 * Shipping restrictions contain information about particular areas where
 * items can (or cannot) be shipped.
 *
 * More info:
 * {@link http://code.google.com/apis/checkout/developer/index.html#tag_shipping-restrictions}
 *
 * Address filters identify areas where a particular merchant-calculated
 * shipping method is available or unavailable. Address filters are applied
 * before Google Checkout sends a <merchant-calculation-callback> to the
 * merchant. Google Checkout will not ask you to calculate the cost of a
 * particular shipping method for an address if the address filters in the
 * Checkout API request indicate that the method is not available for the
 * address.
 *
 * More info:
 * {@link http://code.google.com/apis/checkout/developer/index.html#tag_address-filters}
 */
class GoogleShippingFilters
{
    public $allow_us_po_box = true;

    public $allowed_restrictions  = false;
    public $excluded_restrictions = false;

    public $allowed_world_area = false;
    public $allowed_country_codes_arr;
    public $allowed_postal_patterns_arr;
    public $allowed_country_area;
    public $allowed_state_areas_arr;
    public $allowed_zip_patterns_arr;

    public $excluded_country_codes_arr;
    public $excluded_postal_patterns_arr;
    public $excluded_country_area;
    public $excluded_state_areas_arr;
    public $excluded_zip_patterns_arr;

    public function __construct()
    {
        $this->allowed_country_codes_arr   = [];
        $this->allowed_postal_patterns_arr = [];
        $this->allowed_state_areas_arr     = [];
        $this->allowed_zip_patterns_arr    = [];

        $this->excluded_country_codes_arr   = [];
        $this->excluded_postal_patterns_arr = [];
        $this->excluded_state_areas_arr     = [];
        $this->excluded_zip_patterns_arr    = [];
    }

    /**
     * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_allow-us-po-box <allow-us-po-box>}
     *
     * @param bool $allow_us_po_box whether to allow delivery to PO boxes in US,
     *                              defaults to true
     */
    public function SetAllowUsPoBox($allow_us_po_box = true)
    {
        $this->allow_us_po_box = $allow_us_po_box;
    }

    /**
     * Set the world as allowed delivery area.
     *
     * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_world-area <world-area>}
     *
     * @param bool $world_area Set worldwide allowed shipping, defaults to true
     */
    public function SetAllowedWorldArea($world_area = true)
    {
        $this->allowed_restrictions = true;
        $this->allowed_world_area   = $world_area;
    }

    // Allows

    /**
     * Add a postal area to be allowed for delivery.
     *
     * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_postal-area <postal-area>}
     *
     * @param string $country_code   2-letter iso country code
     * @param string $postal_pattern Pattern that matches the postal areas to
     *                               be allowed, as defined in {@link http://code.google.com/apis/checkout/developer/index.html#tag_postal-code-pattern}
     */
    public function AddAllowedPostalArea($country_code, $postal_pattern = '')
    {
        $this->allowed_restrictions          = true;
        $this->allowed_country_codes_arr[]   = $country_code;
        $this->allowed_postal_patterns_arr[] = $postal_pattern;
    }

    /**
     * Add a us country area to be allowed for delivery.
     *
     * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_us-country-area <us-country-area>}
     *
     * @param string $country_area the area to allow, one of "CONTINENTAL",
     *                             "FULL_50_STATES" or "ALL"
     *
     */
    public function SetAllowedCountryArea($country_area)
    {
        switch ($country_area) {
            case 'CONTINENTAL_48':
            case 'FULL_50_STATES':
            case 'ALL':
                $this->allowed_country_area = $country_area;
                $this->allowed_restrictions = true;
                break;
            default:
                $this->allowed_country_area = '';
                break;
        }
    }

    /**
     * Allow shipping to areas specified by state.
     *
     * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_us-state-area <us-state-area>}
     *
     * @param array $areas Areas to be allowed
     */
    public function SetAllowedStateAreas($areas)
    {
        $this->allowed_restrictions    = true;
        $this->allowed_state_areas_arr = $areas;
    }

    /**
     * Allow shipping to areas specified by state.
     *
     * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_us-state-area <us-state-area>}
     *
     * @param string $area Area to be allowed
     */
    public function AddAllowedStateArea($area)
    {
        $this->allowed_restrictions      = true;
        $this->allowed_state_areas_arr[] = $area;
    }

    /**
     * Allow shipping to areas specified by zip patterns.
     *
     * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_us-zip-area <us-zip-area>}
     *
     * @param array $zips
     */
    public function SetAllowedZipPatterns($zips)
    {
        $this->allowed_restrictions     = true;
        $this->allowed_zip_patterns_arr = $zips;
    }

    /**
     * Allow shipping to area specified by zip pattern.
     *
     * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_us-zip-area <us-zip-area>}
     *
     * @param string
     */
    public function AddAllowedZipPattern($zip)
    {
        $this->allowed_restrictions       = true;
        $this->allowed_zip_patterns_arr[] = $zip;
    }

    /**
     * Exclude postal areas from shipping.
     *
     * @see AddAllowedPostalArea
     * @param        $country_code
     * @param string $postal_pattern
     */
    public function AddExcludedPostalArea($country_code, $postal_pattern = '')
    {
        $this->excluded_restrictions          = true;
        $this->excluded_country_codes_arr[]   = $country_code;
        $this->excluded_postal_patterns_arr[] = $postal_pattern;
    }

    /**
     * Exclude state areas from shipping.
     *
     * @see SetAllowedStateAreas
     * @param $areas
     */
    public function SetExcludedStateAreas($areas)
    {
        $this->excluded_restrictions    = true;
        $this->excluded_state_areas_arr = $areas;
    }

    /**
     * Exclude state area from shipping.
     *
     * @see AddAllowedStateArea
     * @param $area
     */
    public function AddExcludedStateArea($area)
    {
        $this->excluded_restrictions      = true;
        $this->excluded_state_areas_arr[] = $area;
    }

    /**
     * Exclude shipping to area specified by zip pattern.
     *
     * @see SetAllowedZipPatterns
     * @param $zips
     */
    public function SetExcludedZipPatternsStateAreas($zips)
    {
        $this->excluded_restrictions     = true;
        $this->excluded_zip_patterns_arr = $zips;
    }

    /**
     * Exclude shipping to area specified by zip pattern.
     *
     * @see AddExcludedZipPattern
     * @param $zip
     */
    public function SetAllowedZipPatternsStateArea($zip)
    {
        $this->excluded_restrictions       = true;
        $this->excluded_zip_patterns_arr[] = $zip;
    }

    /**
     * Exclude shipping to country area
     *
     * @see SetAllowedCountryArea
     * @param $country_area
     */
    public function SetExcludedCountryArea($country_area)
    {
        switch ($country_area) {
            case 'CONTINENTAL_48':
            case 'FULL_50_STATES':
            case 'ALL':
                $this->excluded_country_area = $country_area;
                $this->excluded_restrictions = true;
                break;

            default:
                $this->excluded_country_area = '';
                break;
        }
    }
}

/**
 * Used as a shipping option in which neither a carrier nor a ship-to
 * address is specified
 *
 * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_pickup} <pickup>
 */
class GooglePickUp
{
    public $price;
    public $name;
    public $type = 'pickup';

    /**
     * @param string $name  the name of this shipping option
     * @param double $price the handling cost (if there is one)
     */
    public function __construct($name, $price)
    {
        $this->price = $price;
        $this->name  = $name;
    }
}
