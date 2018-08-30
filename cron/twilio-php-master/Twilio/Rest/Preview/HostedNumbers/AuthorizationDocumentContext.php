<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\HostedNumbers;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Rest\Preview\HostedNumbers\AuthorizationDocument\DependentHostedNumberOrderList;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 * 
 * @property \Twilio\Rest\Preview\HostedNumbers\AuthorizationDocument\DependentHostedNumberOrderList dependentHostedNumberOrders
 */
class AuthorizationDocumentContext extends InstanceContext {
    protected $_dependentHostedNumberOrders = null;

    /**
     * Initialize the AuthorizationDocumentContext
     * 
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $sid AuthorizationDocument sid.
     * @return \Twilio\Rest\Preview\HostedNumbers\AuthorizationDocumentContext 
     */
    public function __construct(Version $version, $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('sid' => $sid, );

        $this->uri = '/AuthorizationDocuments/' . rawurlencode($sid) . '';
    }

    /**
     * Fetch a AuthorizationDocumentInstance
     * 
     * @return AuthorizationDocumentInstance Fetched AuthorizationDocumentInstance
     */
    public function fetch() {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new AuthorizationDocumentInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Update the AuthorizationDocumentInstance
     * 
     * @param array|Options $options Optional Arguments
     * @return AuthorizationDocumentInstance Updated AuthorizationDocumentInstance
     */
    public function update($options = array()) {
        $options = new Values($options);

        $data = Values::of(array(
            'HostedNumberOrderSids' => Serialize::map($options['hostedNumberOrderSids'], function($e) { return $e; }),
            'AddressSid' => $options['addressSid'],
            'Email' => $options['email'],
            'CcEmails' => Serialize::map($options['ccEmails'], function($e) { return $e; }),
            'Status' => $options['status'],
        ));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new AuthorizationDocumentInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Access the dependentHostedNumberOrders
     * 
     * @return \Twilio\Rest\Preview\HostedNumbers\AuthorizationDocument\DependentHostedNumberOrderList 
     */
    protected function getDependentHostedNumberOrders() {
        if (!$this->_dependentHostedNumberOrders) {
            $this->_dependentHostedNumberOrders = new DependentHostedNumberOrderList(
                $this->version,
                $this->solution['sid']
            );
        }

        return $this->_dependentHostedNumberOrders;
    }

    /**
     * Magic getter to lazy load subresources
     * 
     * @param string $name Subresource to return
     * @return \Twilio\ListResource The requested subresource
     * @throws \Twilio\Exceptions\TwilioException For unknown subresources
     */
    public function __get($name) {
        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     * 
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return \Twilio\InstanceContext The requested resource context
     * @throws \Twilio\Exceptions\TwilioException For unknown resource
     */
    public function __call($name, $arguments) {
        $property = $this->$name;
        if (method_exists($property, 'getContext')) {
            return call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Preview.HostedNumbers.AuthorizationDocumentContext ' . implode(' ', $context) . ']';
    }
}