<?php

	if(isset($_POST['validate'])){

		$subject=$_POST['subject'];
		$taglist=$_POST['tags'];
		
		$db = initDB();

		$stmt = $db->prepare("INSERT INTO support_conversations (subject,tags,created_at) VALUES (:subject,:tags,:created_at)");
		$stmt->bindParam(':subject', $subject);
		$stmt->bindParam(':tags', $taglist);
		$stmt->bindParam(':created_at', time());
		$stmt->execute();
		$lastId = $db->lastInsertId();

		$senders=$_POST['sender'];
		$messages=$_POST['message'];
		$message_times=$_POST['message_time'];


		if(count($senders)==count($messages)) {
			for($i=0;$i<count($senders);$i++) {

				$sender = ($senders[$i]=="Max from Weglot") ? "":$senders[$i];

				$stmt = $db->prepare("INSERT INTO support_conversations_messages (support_conversation_id,message,sender_name,created_at) VALUES (:support_conversation_id,:message,:sender_name,:created_at)");
				$stmt->bindParam(':support_conversation_id', $lastId);
		        $stmt->bindParam(':message', $messages[$i]);
		        $stmt->bindParam(':sender_name', $sender);
		        $stmt->bindParam(':created_at', $message_times[$i]);
				$stmt->execute();
			}

		}
		else {
			exit("Mismatch count. We have ".count($senders)." senders but we have ".count($messages)." messages.");
		}
	}

	function initDB() {
	    /* DB Parameters*/
	    $host = 'XXXX';
	    $dbname = 'XXXX';
	    $user = 'XXX';
	    $pass = 'XXXX';

	    /* Connecting to DB */
	    try {
	        return new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=UTF8', $user, $pass);
	    }
	    catch (PDOException $e) {
	        Log::WriteLog("Erreur !: " . $e->getMessage(),3);
	    }
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
			<link rel="stylesheet" type="text/css" href="https://helpscoutapp.weglot.com/public/weglot_1.css">
			<link rel="stylesheet" type="text/css" href="https://helpscoutapp.weglot.com/public/weglot_2.css">

		<title>Tickets | Weglot</title>
	</head>

	<body class="bg-light-grey">
	    <main id="main" class="main ">
			<header class="global-nav">
			    <div class="container">
				        <div class="header__logo" style="display: inline-block;">
				            <h1 class="nav-logo"><a href="https://weglot.com/" target="_blank">We<span class="nav-logo-g ">g</span>lot</a></h1>
				        </div>
				    <div class="nav-trigger hidden-md-up p-3" data-toggle="collapse" data-target="#mm-menu">
			            <div class="navigation-trigger__inner">
			                <i class="navigation-trigger__line"></i>
			                <i class="navigation-trigger__line"></i>
			                <i class="navigation-trigger__line"></i>
			            </div>
		        	</div>
				    <div class="collapse hidden-md-up mt-3" id="mm-menu">
			            <ul class="list-inline text-right">
			            	<li class="pt-3 pb-3"><a href="https://weglot.atlassian.net/wiki/spaces/CS/pages/1769495/Ticket+Pusher+-+Guide" target="_blank" class="wg-nav-item">Help</a></li>
			                <li class="pt-3 pb-3"><a href="https://weglot.com/documentation" target="_blank" class="wg-nav-item">Documentation</a></li>
			                <li class="pt-3 pb-3"><a href="https://weglot.com/pricing" target="_blank" class="wg-nav-item">Pricing</a></li>
			            </ul>
			        </div>

		        	<ul class="list-inline pull-right hidden-sm-down">
		        		<li class="list-inline-item"><a href="https://weglot.atlassian.net/wiki/spaces/CS/pages/1769495/Ticket+Pusher+-+Guide" target="_blank" class="wg-nav-item">Help</a></li>
			            <li class="list-inline-item"><a href="https://weglot.com/documentation" target="_blank" class="wg-nav-item">Documentation</a></li>
			            <li class="list-inline-item"><a href="https://weglot.com/pricing" target="_blank" class="wg-nav-item">Pricing</a></li>
		            </ul>
		    	</div>
			</header>

			<section class="intro">
		        <div class="main-background pricing-background">
		        </div>

		        <div class="container">
		            <div class="page-header-wrapper">
		                <h1 class="page-title">TICKETS</h1>
		                <h2 class="page-subtitle"></h2>
		            </div>
		        </div>
		    </section>

		     <section class="pricing-table pb-5">
		        <h1 class="section-title text-center mt-5">Confirmation Message</h1>
		        <h2 class="section-subtitle text-center mt-4 mb-5"><strong> Subject: </strong><?php echo $subject; ?></h2>
		        <h2 class="section-subtitle text-center mt-4 mb-5"><strong> This conversation has </strong><?php echo $i; ?><strong> messages.</strong></h2>
		        <h2 class="section-subtitle text-center mt-4 mb-5"><strong> Push Date: </strong><?php echo date('Y-m-d H:i:s') ?></h2>

		        <a class="btn btn-primary second" href="https://weglot.com/questions/">Result</a>

		        <p class="text-center"></p>
		        <div class="row mb-5">
		            <div class="col text-center">
		                <div class="pf-label">
		                    <h4></h4>
		                </div>
		                <div class="pf-label">
		                    <h4></h4>
		                    <span class="small"></span>
		                </div>
		            </div>
		        </div>
		    </section>


		</main>
		<footer id="footer" class="footer">
		    <div class="footer-background-2"></div>
		    <div class="container">
		        <div class="row text-left">
		            <div class="col">
		                <h5>Product</h5>
		                <ul class="list-unstyled">
		                    <li>
		                        <a href="https://weglot.com/documentation" target="_blank">Documentation</a>
		                    </li>
		                    <li>
		                        <a href="https://weglot.com//wordpress-translation-plugin" target="_blank">WordPress Plugin</a>
		                    </li>
		                    <li><a href="https://weglot.com//shopify-translation-app" target="_blank">Shopify App</a>
		                    </li>
		                    <li>
		                        <a href="https://weglot.com//bigcommerce-translation-app" target="_blank">BigCommerce App</a>
		                    </li>
		                    <li><a href="https://weglot.com//jimdo-translation" target="_blank">Jimdo Integration</a></li>
		                    <li><a href="https://weglot.com//pricing" target="_blank">Pricing</a></li>
		                </ul>
		            </div>
		            <div class="col">
		                <h5>Company</h5>
		                <ul class="list-unstyled">
		                    <li><a href="https://weglot.com//jobs" target="_blank">Jobs</a></li>
		                    <li><a href="https://weglot.com//partners" target="_blank">Partners</a></li>
		                    <li><a href="https://weglot.com//terms" target="_blank">Terms</a></li>
		                    <li><a href="http://support.weglot.com" target="_blank">Support</a></li>
		                    <li><a href="https://weglot.com/questions/" target="_blank">Questions</a></li>
		                </ul>

		            </div>
		            <div class="col">
		                <h5>Social</h5>
		                <ul class="list-unstyled">
		                    <li><a target="_blank" href="https://www.facebook.com/weglot/" target="_blank">Facebook</a></li>
		                    <li><a target="_blank" href="https://twitter.com/weglot" target="_blank">Twitter</a></li>
		                    <li><a target="_blank" href="http://blog.weglot.com/" target="_blank">Blog</a></li>
		                </ul>

		            </div>
		        </div>
		        <p class="mt-5">Weglot © 2018, translation as a service.</p>
		    </div>
		</footer>
	</body>
</html>