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
 * Used to create a Google Checkout result as a response to a
 * merchant-calculations feedback structure, i.e shipping, tax, coupons and
 * gift certificates.
 *
 * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_result <result>}
 */
// refer to demo/responsehandlerdemo.php for usage of this code
class GoogleResult
{
    public $shipping_name;
    public $address_id;
    public $shippable;
    public $ship_price;

    public $tax_amount;

    public $coupon_arr   = [];
    public $giftcert_arr = [];

    /**
     * @param integer $address_id the id of the anonymous address sent by
     *                            Google Checkout.
     */
    public function __construct($address_id)
    {
        $this->address_id = $address_id;
    }

    public function SetShippingDetails($name, $price, $shippable = 'true')
    {
        $this->shipping_name = $name;
        $this->ship_price    = $price;
        $this->shippable     = $shippable;
    }

    public function SetTaxDetails($amount)
    {
        $this->tax_amount = $amount;
    }

    public function AddCoupons($coupon)
    {
        $this->coupon_arr[] = $coupon;
    }

    public function AddGiftCertificates($gift)
    {
        $this->giftcert_arr[] = $gift;
    }
}

/**
 * This is a class used to return the results of coupons the buyer supplied in
 * the order page.
 *
 * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_coupon-result <coupon-result>}
 */
class GoogleCoupons
{
    public $coupon_valid;
    public $coupon_code;
    public $coupon_amount;
    public $coupon_message;

    public function googlecoupons($valid, $code, $amount, $message)
    {
        $this->coupon_valid   = $valid;
        $this->coupon_code    = $code;
        $this->coupon_amount  = $amount;
        $this->coupon_message = $message;
    }
}

/**
 * This is a class used to return the results of gift certificates
 * supplied by the buyer on the place order page
 *
 * GC tag: {@link http://code.google.com/apis/checkout/developer/index.html#tag_gift-certificate-result} <gift-certificate-result>
 */
class GoogleGiftcerts
{
    public $gift_valid;
    public $gift_code;
    public $gift_amount;
    public $gift_message;

    public function googlegiftcerts($valid, $code, $amount, $message)
    {
        $this->gift_valid   = $valid;
        $this->gift_code    = $code;
        $this->gift_amount  = $amount;
        $this->gift_message = $message;
    }
}
