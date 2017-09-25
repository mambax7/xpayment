<?php

/*
 * Copyright (C) 2006 Google Inc.
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
 */

/**
 * Classes used to handle tax rules and tables
 */

/**
 * Represents a tax rule
 *
 * @see GoogleDefaultTaxRule
 * @see GoogleAlternateTaxRule
 *
 * @abstract
 */
class GoogleTaxRule
{
    public $tax_rate;

    public $world_area = false;
    public $country_codes_arr;
    public $postal_patterns_arr;
    public $state_areas_arr;
    public $zip_patterns_arr;
    public $country_area;

    public function __construct()
    {
    }

    public function SetWorldArea($world_area = true)
    {
        $this->world_area = $world_area;
    }

    public function AddPostalArea($country_code, $postal_pattern = '')
    {
        $this->country_codes_arr[]   = $country_code;
        $this->postal_patterns_arr[] = $postal_pattern;
    }

    public function SetStateAreas($areas)
    {
        if (is_array($areas)) {
            $this->state_areas_arr = $areas;
        } else {
            $this->state_areas_arr = [$areas];
        }
    }

    public function SetZipPatterns($zips)
    {
        if (is_array($zips)) {
            $this->zip_patterns_arr = $zips;
        } else {
            $this->zip_patterns_arr = [$zips];
        }
    }

    public function SetCountryArea($country_area)
    {
        switch ($country_area) {
            case 'CONTINENTAL_48':
            case 'FULL_50_STATES':
            case 'ALL':
                $this->country_area = $country_area;
                break;
            default:
                $this->country_area = '';
                break;
        }
    }
}

/**
 * Represents a default tax rule
 *
 * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_default-tax-rule <default-tax-rule>}
 */
class GoogleDefaultTaxRule extends GoogleTaxRule
{
    public $shipping_taxed = false;

    public function __construct($tax_rate, $shipping_taxed = 'false')
    {
        $this->tax_rate       = $tax_rate;
        $this->shipping_taxed = $shipping_taxed;

        $this->country_codes_arr   = [];
        $this->postal_patterns_arr = [];
        $this->state_areas_arr     = [];
        $this->zip_patterns_arr    = [];
    }
}

/**
 * Represents an alternate tax rule
 *
 * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_alternate-tax-rule <alternate-tax-rule>}
 */
class GoogleAlternateTaxRule extends GoogleTaxRule
{
    public function __construct($tax_rate)
    {
        $this->tax_rate = $tax_rate;

        $this->country_codes_arr   = [];
        $this->postal_patterns_arr = [];
        $this->state_areas_arr     = [];
        $this->zip_patterns_arr    = [];
    }
}

/**
 * Represents an alternate tax table
 *
 * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_alternate-tax-table <alternate-tax-table>}
 */
class GoogleAlternateTaxTable
{
    public $name;
    public $tax_rules_arr;
    public $standalone;

    public function __construct($name = '', $standalone = 'false')
    {
        if ('' != $name) {
            $this->name          = $name;
            $this->tax_rules_arr = [];
            $this->standalone    = $standalone;
        }
    }

    public function AddAlternateTaxRules($rules)
    {
        $this->tax_rules_arr[] = $rules;
    }
}
