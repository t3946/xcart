<?PHP

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
			'location'	=> $this->url,
			'uri'		=> $this->ns,
			'trace'		=> 1,
			'style'		=> SOAP_RPC,
			'use'		=> SOAP_ENCODED,
		));
	}
	
	public function fmt_title_pattern ($title) {
		return '%' . sprintf($this->title_pattern, $title) . '%';
	}
	
	public function fmt_ticket_url($ticket_id) {
		return sprintf($this->url_pattern, $ticket_id);
	}
	
	public function fetch_ticket_ids($title) {
		$result = $this->soap->__SoapCall('Search', array(
			new SoapParam($this->user, 'UserLogin'),
			new SoapParam($this->pass, 'Password'),
			new SoapParam($this->fmt_title_pattern($title), 'Title'),
		));
		
		if (isset($result)) {
			if (is_array($result) && is_array($result['TicketID'])) {
				$result = $result['TicketID'];
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
	
	public function fetch_ticket_links($title) {
		$ids = $this->fetch_ticket_ids($title);
		$count = count($ids);
		$result = array();
		for ($i = 0; $i < $count; $i++) {
			array_push($result, $this->fmt_ticket_url($ids[$i]));
		}
		return $result;
	}
}

$resolver = new OrderToTicketResolver(
	"xcart", "vyZqB8EVuTM1",
	"http://helpdesk.s3stores.com/otrs/nph-genericinterface.pl/Webservice/TicketConnector",
	"otrs-soap",
	"%s",
	"http://helpdesk.s3stores.com/otrs/index.pl?Action=AgentTicketZoom;TicketID=%d"
);

$order_prefix = func_query_first_cell("SELECT order_prefix FROM $sql_tbl[orders] WHERE orderid='$orderid'");
$ticket_resolver = $resolver->fetch_ticket_links($order_prefix.$orderid);

if (!empty($ticket_resolver[0])){
	$smarty->assign('ticket_resolver_link', $ticket_resolver[0]);
}
?>
