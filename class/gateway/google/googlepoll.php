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
 */

/**
 * Classes used to poll for Google Checkout notifications (using the polling API)
 */

/**
 * Polls for notifications
 */
class GooglePoll
{
    public $continue_token;
    public $get_all_notifications  = true;
    public $has_more_notifications = true;

    public $merchant_id;
    public $merchant_key;
    public $environment;
    public $poll_request_xml;
    public $poll_result;
    public $notifications = [];
    public $certificate_path;

    public $schema_url              = 'http://checkout.google.com/schema/2';
    public $prod_base_server_url    = 'https://checkout.google.com/api/checkout/v2/reports/Merchant/';
    public $sandbox_base_server_url = 'https://sandbox.google.com/checkout/api/checkout/v2/reports/Merchant/';
    public $server_url;
    public $xml_data;

    /*
     * Constructor for the class
     * Inputs are: merchant id, merchant key, environment (default 'sandbox')
     * and a continue-token (from a ContinueTokenRequest)
     */
    public function __construct($id, $key, $env, $contToken, $cp = null)
    {
        $this->merchant_id      = $id;
        $this->merchant_key     = $key;
        $this->environment      = $env;
        $this->continue_token   = $contToken;
        $this->certificate_path = $cp;

        switch ($env) {
            case 'production':
                $this->server_url = $this->prod_base_server_url . $id;
                break;
            default:
                $this->server_url = $this->sandbox_base_server_url . $id;
        }
    }

    /*
     * Set whether polling should continue until there are no more
     * appropriate notifications to fetch.  Default = true, value false
     * will stop after one request.
     */
    public function getAllNotifications($get_all)
    {
        switch ($get_all) {
            case false:
                $this->get_all_notifications = false;
                break;
            case true:
                $this->get_all_notifications = true;
                break;
            default:
                true;
        }
    }

    /*
     * Polls for notifications as defined
     */
    public function RequestData()
    {
        //create GRequest object + post xml (googlecart.php line: 962)
        require_once __DIR__ . '/library/googlerequest.php';
        $GRequest = new GoogleRequest($this->merchant_id, $this->merchant_key);
        $GRequest->SetCertificatePath($this->certificate_path);

        while ('true' === $this->has_more_notifications) {
            $this->poll_request_xml = $this->getPollRequestXML();
            $this->poll_result      = $GRequest->SendReq($this->server_url, $GRequest->getAuthenticationHeaders(), $this->poll_request_xml);

            //Check response code
            if ('200' == $this->poll_result[0]) {
                $this->ExtractNotifications();
            } else {
                return false;
            }

            if (false === $this->get_all_notifications) {
                'false' === $this->has_more_notifications;
            }
        }

        return true;
    }

    /*
     * Returns an array containing all notifications from poll.
     * This includes notifications from multiple requests
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /*
     * Extracts data from poll response xml.
     * This includes, individual notifications, new continue token
     * and more notifications value
     */
    public function ExtractNotifications()
    {
        require_once __DIR__ . '/xml-processing/gc_xmlparser.php';
        $GXmlParser = new gc_XmlParser($this->poll_result[1]);
        $data       = $GXmlParser->getData();
        //Get the actual notifications
        foreach ($data['notification-data-response']['notifications'] as $notification) {
            $this->notifications[] = $notification;
        }
        //Get other useful info
        $this->has_more_notifications = $data['notification-data-response']['has-more-notifications']['VALUE'];
        $this->continue_token         = $data['notification-data-response']['continue-token']['VALUE'];
    }

    /*
     * Builds poll request XML
     */
    public function getPollRequestXML()
    {
        require_once __DIR__ . '/xml-processing/gc_xmlbuilder.php';
        $xml_data = new gc_XmlBuilder();

        $xml_data->Push('notification-data-request', ['xmlns' => $this->schema_url]);
        $xml_data->Element('continue-token', $this->continue_token);
        $xml_data->Pop('notification-data-request');

        return $xml_data->getXML();
    }
}

/**
 * Requests a continue token for polling
 */
class ContinueTokenRequest
{
    public $start_time;
    public $continue_token;

    public $merchant_id;
    public $merchant_key;
    public $environment;
    public $request_token_xml;
    public $token_response_xml;

    public $schema_url              = 'http://checkout.google.com/schema/2';
    public $prod_base_server_url    = 'https://checkout.google.com/api/checkout/v2/reports/Merchant/';
    public $sandbox_base_server_url = 'https://sandbox.google.com/checkout/api/checkout/v2/reports/Merchant/';
    public $server_url;
    public $xml_data;
    public $certificate_path;

    public function __construct($id, $key, $env, $cp = null)
    {
        $this->merchant_id      = $id;
        $this->merchant_key     = $key;
        $this->environment      = $env;
        $this->certificate_path = $cp;

        switch ($env) {
            case 'production':
                $this->server_url = $this->prod_base_server_url . $id;
                break;
            default:
                $this->server_url = $this->sandbox_base_server_url . $id;
        }
    }

    public function SetStartTime($poll_start_time)
    {
        $this->start_time = $poll_start_time;
    }

    public function getContinueToken()
    {
        if ('' != $this->continue_token) {
            return $this->continue_token;
        } else {
            return false;
        }
    }

    public function RequestToken()
    {
        $this->request_token_xml = $this->getTokenRequestXML();

        //create GRequest object + post xml (googlecart.php line: 962)
        require_once __DIR__ . '/library/googlerequest.php';
        $GRequest = new GoogleRequest($this->merchant_id, $this->merchant_key);
        /*---------------------------------------------------------------------------------------------------*/
        $GRequest->SetCertificatePath($this->certificate_path);

        $this->token_response_xml = $GRequest->SendReq($this->server_url, $GRequest->getAuthenticationHeaders(), $this->request_token_xml);

        //Check response code
        if ('200' == $this->token_response_xml[0]) {
            require_once __DIR__ . '/xml-processing/gc_xmlparser.php';
            $GXmlParser = new gc_XmlParser($this->token_response_xml[1]);
            $data       = $GXmlParser->getData();

            $this->continue_token = $data['notification-data-token-response']['continue-token']['VALUE'];

            return $this->continue_token;
        } //else return $token_result;
        else {
            return false;
        }
    }

    public function getTokenRequestXML()
    {
        require_once __DIR__ . '/xml-processing/gc_xmlbuilder.php';
        $xml_data = new gc_XmlBuilder();

        $xml_data->Push('notification-data-token-request', ['xmlns' => $this->schema_url]);
        $xml_data->Element('start-time', $this->start_time);
        $xml_data->Pop('notification-data-token-request');

        return $xml_data->getXML();
    }
}
