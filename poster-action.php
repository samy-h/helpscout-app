<?php

use HelpScoutApp\DynamicApp;

include 'src/HelpScoutApp/DynamicApp.php';
include 'src/HelpScout/ApiClient.php';
include 'src/lib/curl.php';
include 'src/lib/curl_response.php';
include 'src/lib/curl_exception.php';
include 'src/HelpScout/model/Conversation.php';
include 'src/HelpScout/model/SearchConversation.php';
include 'src/html2text.php';


use HelpScout\ApiClient;

date_default_timezone_set('Europe/Paris');
define('API_KEY','XXXXXXXXXX');


try {
    $hs = ApiClient::getInstance();
    $hs->setKey(API_KEY);

    $list = $hs->getConversationsForMailbox(108999, array('page' => 2));

    $db = initDB();

    $id_conv = $_GET['convid'];
    //$id_conv = '533986118';
    $conversation = $hs->getConversation($id_conv);  // 523071292=#16290   521749567=#16164   533986118=#17451  "ticketID":

    $preview = $conversation->getPreview(); // Calling preview to display it in the page.php file
    $threadCount = $conversation->getThreadCount(); // Calling threadCount to display it in the page.php file
    $id = $conversation->getNumber(); // Calling id to display it in the page.php file

    $subject = $conversation->getSubject(); // SQL Table: support_conversations
    $tagl = $conversation->getTags();
    $tagFiltered = $tagl=="" ? "":excludeTags($tagl);
    $taglist = implode(",", $tagFiltered);
}

catch (PDOException $e) {
        exit("Erreur !: " . $e->getMessage());
}

/*------------------------------PAGE-----------------------------------------*/
ob_start();
include('edit.php');
$content = ob_get_contents();
ob_end_clean();
//file_put_contents('public/yourpage.html', $content);
//exit("Work done!");
exit($content);
/*---------------------------------------------------------------------------*/

function excludeTags($tagl) { // Exclude tags corresponding to the current plan of the customer

    $toExclude = array('t1','t2','t0','free','premium','reviewwait','reviewpending','reviewok','review','warning','nosupport','cancellation');
    foreach ($toExclude as $key => $value) {
        if (in_array($value, $tagl))
        {
            $index = array_search($value, $tagl);
            unset($tagl[$index]);
        }
    }
    return $tagl;
}

function initDB() {
    /* DB Parameters*/
    $host = 'XX';
    $dbname = 'XX';
    $user = 'XX';
    $pass = 'XX';
    
    /* Connecting to DB */
    try {
        return new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass);       
    }
    catch (PDOException $e) {
        Log::WriteLog("Erreur !: " . $e->getMessage(),3);
    }
}