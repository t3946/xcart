<?php

namespace Xcart;


class OrderToTicketResolver {
    protected $user;
    protected $pass;
    protected $url;
    protected $ns;
    protected $title_pattern;
    protected $url_pattern;
    protected $soap;

    function __construct($user, $pass, $url, $ns, $title_pattern, $url_pattern) {
        $this->user = $user;
        $this->pass = $pass;
        $this->url = $url;
        $this->ns = $ns;
        $this->title_pattern = $title_pattern;
        $this->url_pattern = $url_pattern;

        $this->soap = new \SoapClient(null, array(
            'location'      => $this->url,
            'uri'           => $this->ns,
            'trace'         => 1,
            'style'         => SOAP_RPC,
            'use'           => SOAP_ENCODED,
        ));
    }

    public function get_last_request_xml() {
        return $this->soap->__getLastRequest();
    }

    public function fmt_title_pattern ($title) {
        return '%' . sprintf($this->title_pattern, $title) . '%';
    }

    public function fmt_ticket_url($ticket_id) {
        return sprintf($this->url_pattern, $ticket_id);
    }

    public function fetch_ticket_info_array_by_orderno($title) {

        try {
            $result = $this->soap->__SoapCall('CustomSearch', array(
                new \SoapParam($this->user, 'UserLogin'),
                new \SoapParam($this->pass, 'Password'),
                new \SoapParam(array('Equals' => $title), 'DynamicField_OrderLink'),
                new \SoapParam('AllTickets', 'SearchInArchive'),
            ));
        } catch(\Exception $e){
        }

        if (isset($result)) {
            if (is_array($result) && is_array($result['Array'])) {
                $result = $result['Array'];
                return $result;
            }
            else {
                return array($result);
            }
        }
        else {
            return array();
        }
    }

    public function fetch_ticket_info_array_by_title($title) {

        try {
            $result = $this->soap->__SoapCall('CustomSearch', array(
                new \SoapParam($this->user, 'UserLogin'),
                new \SoapParam($this->pass, 'Password'),
                new \SoapParam($this->fmt_title_pattern($title), 'Title'),
                new \SoapParam('AllTickets', 'SearchInArchive'),
            ));
        } catch(\Exception $e){
        }

        if (isset($result)) {
            if (is_array($result) && is_array($result['Array'])) {
                $result = $result['Array'];
                return $result;
            }
            else {
                return array($result);
            }
        }
        else {
            return array();
        }
    }

    public function fetch_ticket_info($title) {
        $info = $this->fetch_ticket_info_array_by_orderno($title);
        if (count($info) == 0) {
            $info = $this->fetch_ticket_info_array_by_title($title);
        }
        $count = count($info);
        $result = array();
        for ($i = 0; $i < $count; $i++) {
            $ticket_info = (array)$info[$i];
            array_push($result, array(
                'url'           => $this->fmt_ticket_url($ticket_info['TicketID']),
                'messages'      => $ticket_info['Articles'],
            ));
        }
        return $result;
    }
}