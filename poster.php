<?php

use HelpScoutApp\DynamicApp;

include 'src/HelpScoutApp/DynamicApp.php';
include 'src/HelpScout/ApiClient.php';
include 'src/lib/curl.php';
include 'src/lib/curl_response.php';
include 'src/lib/curl_exception.php';
include 'src/HelpScout/model/Conversation.php';
include 'src/HelpScout/model/SearchConversation.php';

use HelpScout\ApiClient;

date_default_timezone_set('Europe/Paris');

define('SECRET_KEY','XXXXXXXXXX');

define('API_KEY','XXXXXXXXXX');


/*---------------------------------------------------------------------------*/

$app = new DynamicApp(SECRET_KEY);
if ($app->isSignatureValid()) {               
    

		$customer = $app->getCustomer();
        $user     = $app->getUser();
        $convo    = $app->getConversation();
        $mailbox  = $app->getMailbox();
        $email = $customer->getEmail();

        $html = "<ul class='unstyled'>Post conversations to the FAQ.".
					"<li><i class=\"icon-arrow muted\"></i><a target='_blank' href='https://helpscoutapp.weglot.com/poster-action.php?convid=".$convo->getId()."'><span class='badge blue'>PUSH</span></a></li>".
				"</ul>";

        echo $app->getResponse($html);
} 
else 
{
    echo $app->getResponse('Invalid Request');
}
/*---------------------------------------------------------------------------*/

function initDB() {
	/* DB Parameters*/
	$host = 'XXXXXX';
	$dbname = 'XXXXX';
	$user = 'XXXXXX';
	$pass = 'XXXXX';
	
	/* Connecting to DB */
	try {
		return new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass);		
	}
	catch (PDOException $e) {
		Log::WriteLog("Erreur !: " . $e->getMessage(),3);
	}
}

function techno($iswp) {
	switch ($iswp) {
		case "0":
			return "Unknown";
		case "1":
			return "WordPress";
		case "2":
			return "Shopify";
		case "3":
			return "BigCommerce";
        case "4":
            return "Jimdo";
        case "5":
            return "Javascript";
		default :
			return "Unknown";
		}
}

function tag($plan,$registration) {
    if($plan==1) {
        if(time()-$registration<5*86400) return "t1";
        if(time()-$registration<10*86400) return "t2";
       else return "free";
    }
    else {
        return "premium";
    }

}

function plan_name($plan) {
	switch ($plan) {
		case "-1":
			return "No plan";
		case "1":
			return "Free";
		case "2":
			return "Starter - MONTHLY";
		case "3":
			return "Starter - YEARLY";
		case "4":
			return "Business - MONTHLY";
		case "5":
			return "Business - YEARLY";
		case "6":
			return "Pro - MONTHLY";
		case "7":
			return "Pro - YEARLY";
		case "8":
			return "Enterprise - MONTHLY";
		case "9":
			return "Enterprise - YEARLY";
		case "10":
			return "Corporate - MONTHLY";
		case "11":
			return "Corporate - YEARLY";
	}
}