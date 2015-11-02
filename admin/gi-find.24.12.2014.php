<?PHP

//return true;

if ( !defined('XCART_SESSION_START') ) { header('Location: ../'); die('Access denied'); }

//error_reporting(E_ALL);

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

                $this->soap = new SoapClient(null, array(
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

        public function fetch_ticket_info_array($title) {
                $result = $this->soap->__SoapCall('CustomSearch', array(
                        new SoapParam($this->user, 'UserLogin'),
                        new SoapParam($this->pass, 'Password'),
                        new SoapParam($this->fmt_title_pattern($title), 'Title'),
                        new SoapParam('AllTickets', 'SearchInArchive'),
                ));

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
                $info = $this->fetch_ticket_info_array($title);
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

#
##
###
function check_url_exists($url) {
    $hdrs = @get_headers($url);
    return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
}

$chech_domain = "http://helpdesk.s3stores.com/";

if (!check_url_exists($chech_domain)){
	return true;
}
###
##
#

$TicketConnector_link = "http://helpdesk.s3stores.com/otrs/nph-genericinterface.pl/Webservice/TicketConnector";

$resolver = new OrderToTicketResolver(
        "xcart", "vyZqB8EVuTM1",
        $TicketConnector_link,
        "otrs-soap",
        "%s",
        "http://helpdesk.s3stores.com/otrs/index.pl?Action=AgentTicketZoom;TicketID=%d"
);

$order_prefix = func_query_first_cell("SELECT order_prefix FROM $sql_tbl[orders] WHERE orderid='$orderid'");
$ticket_resolver = $resolver->fetch_ticket_info($order_prefix.$orderid);

//func_print_r($ticket_resolver);

if (!empty($ticket_resolver[0]["url"])){
	$ticket_resolver_link = $ticket_resolver[0]["url"];

	if (!empty($ticket_resolver[0]["messages"])){

		$ticket_resolver_messages = $ticket_resolver[0]["messages"];
//		$ticket_resolver_link .= "&messages=".$ticket_resolver_messages;

		$smarty->assign('ticket_resolver_messages', $ticket_resolver_messages);
	}

	db_query("UPDATE $sql_tbl[orders] SET otrs_ticket='".addslashes($ticket_resolver_link)."' WHERE orderid='$orderid'");
	$smarty->assign('ticket_resolver_link', $ticket_resolver_link);
}
?>
