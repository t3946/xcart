<?PHP
use Xcart\OrderToTicketResolver;

if ( !defined('XCART_SESSION_START') ) { header('Location: ../'); die('Access denied'); }

function check_url_exists($url) {
    $hdrs = @get_headers($url);
    return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
}

$chech_domain = "http://helpdesk.s3stores.com/";

if (!check_url_exists($chech_domain)){
	return true;
}

$url      = "http://helpdesk.s3stores.com/otrs/index.pl";
$curl_err = false;
$ch       = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
$output = curl_exec($ch);
if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200 || preg_match("/Can't connect to MySQL server/", $output)) {
    $curl_err = true;
}
curl_close($ch);
if ($curl_err) {
    $top_message["content"] = 'OTRS is NOT responding. Please report this problem to our programmers.';
    $top_message["type"] = 'E';
    return true;
}

$TicketConnector_link = "http://helpdesk.s3stores.com/otrs/nph-genericinterface.pl/Webservice/TicketConnector";

$resolver = new OrderToTicketResolver(
        "xcart", "@Pp6Lcg^VNMC",
       	$TicketConnector_link,
        "otrs-soap",
       	"%s",
        "http://helpdesk.s3stores.com/otrs/index.pl?Action=AgentTicketZoom;TicketID=%d"
);

if (!empty($orderid)){

	$order_prefix = func_query_first_cell("SELECT order_prefix FROM $sql_tbl[orders] WHERE orderid='$orderid'");
	$ticket_resolver = $resolver->fetch_ticket_info($order_prefix.$orderid);

//func_print_r($ticket_resolver);

	if (!empty($ticket_resolver[0]["url"])){
		$ticket_resolver_link = $ticket_resolver[0]["url"];

		if (!empty($ticket_resolver[0]["messages"])){

			$ticket_resolver_messages = $ticket_resolver[0]["messages"];
//			$ticket_resolver_link .= "&messages=".$ticket_resolver_messages;

            $t_arr = \Xcart\App\Main\Xcart::app()->cache->get('ticket_resolver_messages', []);
            $t_arr [$orderid] = $ticket_resolver_messages;
            \Xcart\App\Main\Xcart::app()->cache->set('ticket_resolver_messages', $t_arr);

			$smarty->assign('ticket_resolver_messages', $ticket_resolver_messages);
		}

		db_query("UPDATE $sql_tbl[orders] SET otrs_ticket='".addslashes($ticket_resolver_link)."' WHERE orderid='$orderid'");
		$smarty->assign('ticket_resolver_link', $ticket_resolver_link);
	}
}

if (!empty($prefix_product_question_id)){

        $ticket_resolver = $resolver->fetch_ticket_info($prefix_product_question_id);

//func_print_r($ticket_resolver);

        if (!empty($ticket_resolver[0]["url"])){
                $ticket_resolver_link = $ticket_resolver[0]["url"];

                if (!empty($ticket_resolver[0]["messages"])){

                        $ticket_resolver_messages = $ticket_resolver[0]["messages"];
//                      $ticket_resolver_link .= "&messages=".$ticket_resolver_messages;

                        $smarty->assign('ticket_resolver_messages', $ticket_resolver_messages);
                }

                db_query("UPDATE $sql_tbl[product_question] SET otrs_ticket='".addslashes($ticket_resolver_link)."' WHERE id='$id'");
                $smarty->assign('ticket_resolver_link', $ticket_resolver_link);
        }
}

?>
