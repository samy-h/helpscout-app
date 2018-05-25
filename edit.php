<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    	<link rel="stylesheet" type="text/css" href="https://helpscoutapp.weglot.com/public/weglot_1.css">
    	<link rel="stylesheet" type="text/css" href="https://helpscoutapp.weglot.com/public/weglot_2.css">
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>Conversations | Weglot</title>

    <script type="text/javascript">

      // SCROLL BUTTON

      $(document).ready(function(){
        //Check to see if the window is top if not then display button
        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scrollToTop').fadeIn();
            } else {
                $('.scrollToTop').fadeOut();
            }
        });
        //Click event to scroll to top
        $('.scrollToTop').click(function(){
            $('html, body').animate({scrollTop : 0},800);
            return false;
        });
      });

      // RESIZE TEXTAREAS

      var observe;
      if (window.attachEvent) {
          observe = function (element, event, handler) {
              element.attachEvent('on'+event, handler);
          };
      }
      else {
          observe = function (element, event, handler) {
              element.addEventListener(event, handler, false);
          };
      }

      function init () { 
        function resize (event) {
          if(event instanceof HTMLElement) {
            element = event;
          }
          else {
            element = event.target;
          }
          element.style.height = 'auto';
          element.style.height = element.scrollHeight+'px';
        }
          /* 0-timeout to get the already changed text */
        function delayedResize (event) {
              window.setTimeout(resize(event), 0);
        }

        var textareas = document.getElementsByClassName('textareas');
        for (i = 0; i < textareas.length; i++) {
            textarea = textareas[i];

          observe(textarea, 'change',  resize);
          observe(textarea, 'cut',     delayedResize);
          observe(textarea, 'paste',   delayedResize);
          observe(textarea, 'drop',    delayedResize);
          observe(textarea, 'keydown', delayedResize);

          resize(textarea);
        }

        // DELETE CHECKBOXES AND MAKE THEM OPAC

        $("#deleteMessage").on("click",function(){
          $("input:checkbox").each(function() {
              if ($(this).is(":checked")) {
                  $(this).parent().parent().parent().remove();
              }
          });
        });

        $('input:checkbox').change(function(){
          var message = $("p", $(this).closest(".messages__details"))
          if($(this).is(":checked")) {
              message.addClass("opac");
          } else {
              $('div.messages__details p').removeClass("opac");
          }
        });
      }
    </script>
  </head>

  <body class="bg-light-grey" onload="init();">

      <main id="main" class="main ">

  		<header class="global-nav">
  		  <div class="container">
  			  <div class="header__logo" style="display: inline-block;">
  			    <h1 class="nav-logo"><a href="https://weglot.com/" target="_blank">We<span class="nav-logo-g ">g</span>lot</a></h1>
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
  	        <div class="main-background pricing-background"></div>
  	        <div class="container">
  	            <div class="page-header-wrapper">
  	                <h1 class="page-title">Conversations</h1>
  	                <h2 class="page-subtitle">Here are displayed conversations that you can edit.</h2>
  	            </div
  	        </div>
  	    </section>

  	    <section class="pt-5 pb-5">
  	    	<h1 class="section-title text-center mt-5">Messages</h1>
   				<form action="confirm.php" method="post">
   					<div class="container pb-5">
              <div class="card text-center">
   							<div class="card-block">
   								<h1 class="h2"><textarea class="subject" name="subject"><?php echo $subject; ?></textarea></h1>
   								<span><textarea class="tags" name="tags"><?php echo $taglist; ?></textarea></span>
   							</div>
      				</div>
                  <?php
  					        $threads = $conversation->getThreads();
  					        $threads = array_reverse($threads);
  					        $i = 0;
                    include('names.php');
                    $sender_customer = $names[array_rand($names)];

  				    		foreach($threads as $thread) {

  						        if ($thread instanceof HelpScout\model\thread\Customer || $thread instanceof HelpScout\model\thread\Message) { // If thread is a message from customer or Max
  						            
  						            $message = $thread->getBody();
  						            $message_date = $thread->getCreatedAt();
  						            $tz = new DateTimeZone('Europe/Paris');
  						            $date = new DateTime($message_date);
  						            $date->setTimezone($tz);
  						            $message_date = $date->getTimestamp();

  						            if ($thread instanceof HelpScout\model\thread\Message) { // If thread is a message from Max
  						                $sender = 'Max from Weglot';
                              $additional_class = "messages__item--right weglot";

  						            }
  						            else { // If thread is a message from customer
  						                $sender = $sender_customer;
                              $additional_class = "user_name";
  						            }
  						    ?>
                    <div class="messages__item <?php echo $additional_class; ?>">
                      <div class="messages__details">
                        <p><textarea class="textareas" name="message[<?php echo $i; ?>]"><?php echo convert_html_to_text($message); ?></textarea></p>
                        <small><i class="zmdi zmdi-mail-send mr-1"></i><input class="sender" name="sender[<?php echo $i; ?>]" value="<?php echo $sender; ?>"></input>
                          <label class="checkbox"><input value="<?php echo $i; ?>" type="checkbox"></label><i class="zmdi zmdi-delete"></i></small>
                        <input name="message_time[<?php echo $i; ?>]" value="<?php echo $message_date; ?>" style="display: none!important;"></input>
                      </div>
                    </div>
  						    <?php
  						      $i++;
  						      }
  				    		}
  	              ?>
                  <input class="btn btn-primary" type="submit" name="validate" value="Save Case">
                  <button class="btn" type="button" id="deleteMessage">Delete</button>	       
                  <a href="#" class="scrollToTop"><i class="icon-chevron-up"></i></a>
  	        </div>
  				</form>
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
  	                    <li>
                          <a href="https://weglot.com//jimdo-translation" target="_blank">Jimdo Integration</a>
                        </li>
  	                    <li>
                          <a href="https://weglot.com//pricing" target="_blank">Pricing</a>
                        </li>
  	                </ul>
  	            </div>
  	            <div class="col">
  	                <h5>Company</h5>
  	                <ul class="list-unstyled">
  	                    <li>
                          <a href="https://weglot.com//jobs" target="_blank">Jobs</a>
                        </li>
  	                    <li>
                          <a href="https://weglot.com//partners" target="_blank">Partners</a>
                        </li>
  	                    <li>
                          <a href="https://weglot.com//terms" target="_blank">Terms</a>
                        </li>
  	                    <li>
                          <a href="http://support.weglot.com" target="_blank">Support</a>
                        </li>
                        <li>
                          <a href="https://weglot.com/questions/" target="_blank">Questions</a>
                        </li>
  	                </ul>
  	            </div>
  	            <div class="col">
  	                <h5>Social</h5>
  	                <ul class="list-unstyled">
  	                    <li>
                          <a target="_blank" href="https://www.facebook.com/weglot/" target="_blank">Facebook</a>
                        </li>
  	                    <li>
                          <a target="_blank" href="https://twitter.com/weglot" target="_blank">Twitter</a>
                        </li>
  	                    <li>
                          <a target="_blank" href="http://blog.weglot.com/" target="_blank">Blog</a>
                        </li>
  	                </ul>
  	            </div>
  	        </div>
  	        <p class="mt-5">Weglot © 2018, translation as a service.</p>
  	    </div>
  	</footer>
  </body>
</html>