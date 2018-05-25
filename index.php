<?php

use HelpScoutApp\DynamicApp;

include 'src/HelpScoutApp/DynamicApp.php';

date_default_timezone_set('Europe/Paris');

define('SECRET_KEY','XXXXXXXXXX');

$app = new DynamicApp(SECRET_KEY);
if ($app->isSignatureValid()) {               
    

		$customer = $app->getCustomer();
        $user     = $app->getUser();
        $convo    = $app->getConversation();
        $mailbox  = $app->getMailbox();
        $email = $customer->getEmail();

        $db = initDB();

        $q = $db->prepare('SELECT * FROM t_user where email = :1');
		$q->execute(array(':1'=>$email));
		if($data=$q->fetch(PDO::FETCH_ASSOC) ) { 
			
			$q = $db->prepare("
			select t_user.*,t0.web,t3.plan_id,l_from from t_user
				left join (select owner_id,website as web from projects where owner_id= ".$data['id'].") t0 on t_user.id = t0.owner_id
				left join (select user_id,plan_id from user_plan where user_id = ".$data['id'].") t3 on t3.user_id = t_user.id
				left join (select user_id,l_from from dictionary where project_id in (select id from projects where owner_id= ".$data['id'].") order by id desc  limit 1) t4 on t4.user_id = t_user.id
				where t_user.id = ".$data['id']." order by t_user.id desc;");
			$q->execute(array(':1'=>$email));

			if($data=$q->fetch(PDO::FETCH_ASSOC) ) { 
				$html = "<span class='badge purple'>".tag($data['plan_id'],$data['registration'])."</span> ".
                        "<span class='badge blue'>".plan_name($data['plan_id'])."</span> ".
						"<span class='badge orange'>".techno($data['is_wordpress'])."</span><br>".
					"<ul class='unstyled'>".
						"<li><span class='muted'>Id: </span>".$data['id']."</li>".
						"<li><span class='muted'>Email: </span>".$data['email']."</li>".
						"<li><span class='muted'>Website: </span><a target='_blank' href='".$data['web']."'>".$data['web']."</a></li>".
						"<li><span class='muted'>Registration: </span>".date('d/m/Y H:i',$data['registration'])."</li>".
                        "<li><span class='muted'>Original Language: </span>".$data['l_from']."</li>".
						"<li><i class=\"icon-person muted\"></i><a target='_blank' href='https://bo.weglot.com/users/".$data['id']."/profile'>View in Back Office</a></li>".
					"</ul>";
			}
		}
		else {
			$html = "<p>No user with  email: ".$email."</p><p><i class=\"icon-person muted\"></i><a target='_blank' href='https://bo.weglot.com/users'>Go to Back Office</a></p>";
		}


       
        echo $app->getResponse($html);



    echo $app->getResponse($html);
} else {
    echo $app->getResponse('Invalid Request');
}



function initDB() {
	/* DB Parameters*/
	$host = 'XX';
	$dbname = 'XX';
	$user = 'XX';
	$pass = 'XXX';
	
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