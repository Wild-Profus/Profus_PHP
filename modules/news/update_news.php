<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
/*
CREATE TABLE `1news` (
            `id` INT(4) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,            
            `posted` DATETIME NOT NULL,
            `author` VARCHAR(100) NOT NULL,
            `title` TEXT NOT NULL,
            `post` TEXT NOT NULL,
            `usefull` INT(4) DEFAULT 1,
            `announce` BOOLEAN DEFAULT FALSE,
            UNIQUE KEY `duplicate` (`posted`,`author`)) ENGINE = MYISAM";
INSERT INTO `1news` VALUES(1,"0000-00-00 00:00:00","profus","last update time","last time the news table was updated",0,false)
*/
$checkQuery = "SELECT TIMESTAMPDIFF(SECOND, `posted`, NOW()) as `delay` FROM `1news` WHERE `id`=1";
$date = sqldb::safesql($checkQuery);
if ($date[0]["delay"]>300){
    //Mise à jour de la dernière update
    $updateDate = "UPDATE `1news` SET posted = NOW() WHERE id=1";
    sqldb::safesql($updateDate,false,false);
    libxml_use_internal_errors(true);
    //Get anka tracker content only
    $url = "https://www.dofus.com/fr/forum/les-sujets?tracker=ANKA";
    $doc = dom_init($url);
    $xpath = new DomXPath($doc);
    $content = $xpath->query("//div[contains(@class,'ak-container ak-panel ak-thread ak-nocontentpadding  ak-topics-list-search')]")[0];   
    $xml = $content->ownerDocument->saveXML($content);
    $for = new DOMDocument();
    $for->preserveWhiteSpace = false;
    $for->validateOnParse = true;
    $for->loadXML($xml);
    $url = null;
    $doc = null;
    $content = null;
    $xpath = null;
    $xml = null;
    //Remove images from the post
    $images = $for->getElementsByTagName('img');    
    $imgs = [];    
    foreach($images as $img) {    
        $imgs[] = $img;    
    }    
    foreach($imgs as $img) {    
        $img->parentNode->removeChild($img);    
    }
    $images = null;
    $imgs = null;
    //Get posts' information
    $xpath = new DomXPath($for);    
    $titles = $xpath->query("//div[contains(@class,'ak-title')]//a");    
    $authors = $xpath->query("//span[contains(@class,'ak-name')]//a/text()");    
    $dates = $xpath->query("//span[contains(@class,'ak-date')]");    
    $posts = $xpath->query("//div[contains(@class,'ak-text')]");    
    //$img = $xpath->query("//span[contains(@class,'avatarimg')]/img/@src");    
    $for = null;    
    $xpath = null;
    //Save infos
    foreach ($dates as $a=>$title){
        $find = ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre","-","Aujourd'hui"];
        $replace = ["01","02","03","04","05","06","07","08","09","10","11","12","",date('d m Y')];
        $datetime = DateTime::createFromFormat("d m Y H:i:s",trim(str_replace($find,$replace,explode(' |',$dates[$a]->nodeValue)[0])));        
        $parameters['posted'] = $datetime->format('Y-m-d H:i:s');
        $parameters['author'] = str_replace(["[","]"],"",$authors[$a]->nodeValue);
        $parameters['title'] = str_replace("href=\"","href=\"https://www.dofus.com",str_replace('<a','<a target="_blank"',$titles[$a]->ownerDocument->saveHTML($titles[$a])));
        $check = "SELECT COUNT(`id`) as `cnt` FROM `1news` WHERE `author`=:author AND `title`=:title AND `posted`=:posted LIMIT 1";
        $res = sqldb::safesql($check,$parameters);
        if ($res[0]["cnt"]==="1") {
            break;
        }else{
            $parameters['post'] = str_replace(['<br>','<img border="0" alt="*" src="http://staticns.ankama.com/forum/dofus/style_images/dofus_img/post_snapback.gif">'],'',$posts[$a]->ownerDocument->saveHTML($posts[$a]));
            $insert = "INSERT INTO `1news`(`author`,`title`,`posted`,`post`) VALUES ( :author, :title, :posted, :post) ON DUPLICATE KEY UPDATE id=id";
            sqldb::safesql($insert,$parameters,false);
        }        
        $parameters = null;
    }
    $titles = null;
    $authors = null;
    $dates = null;
    $posts = null;
    //Check announcement
    getAnnounce();   
    //Mise à jour des fichiers xml
    updateXML("news");
    updateXML("devblog");
    updateXML("changelog");
}elseif($date[0]["delay"]==null){
    libxml_use_internal_errors(true);
    $staff = ["[lichen]","[briss]","[BillFR]","[Kylls]","[Raven]","[Tot]","[Kam]","#[Bek]","#[Sylfaen]","[Nazkan]","#[XyaLe]","[Simsoft]","[Wimkote]","[Ling-Authiair]","#[Konala]","[Rogers]","[Mazic]","#[ludo]","[theturtle]","#[RedFish]","[Khiin]","#Tyg","Simma","Tyn","Bii","#[quelconque]","Echt","[Sept]","Malfadrag"];
    foreach ($staff as $key=>$value){
        $url = "https://www.dofus.com/fr/forum/recherche?searchin=topics&thread_id=&post_author=".urlencode($value)."&topic_status=&pin=#jt_list";
        $doc = dom_init($url);
        $xpath = new DomXPath($doc);
        $pages = intval(explode("page=",$xpath->query("//nav/ul/li[last()]/a//@href")[0]->textContent)[1]);
        $doc = null;
        $xpath = null;
        $xml = null;
        for ($i = $pages; $i > 1; $i--) {
            $iUrl = $url."&page=".$pages;
        }
    }
}
getAnnounce();

function dom_init($url){

    $dom = new DOMDocument();

    libxml_use_internal_errors(true);

    $dom->preserveWhiteSpace = false;

    $dom->loadHTML(get_html($url));

    return $dom;

}

function get_html($url) {

    $ch = curl_init();

    $timeout = 10;

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Language: fr']);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    $data = trim(curl_exec($ch));

    curl_close($ch);

    return $data;

}

function updateXML($id){//news, devblog, changelog
    $url = "https://www.dofus.com/fr/rss/$id.xml";
    //print_r(get_headers($url));
    $fileStream =  @fopen($url, 'rb');            
    if ($fileStream !== null) {
        $xmlContent = stream_get_contents($fileStream);
        $RSS = new SimpleXMLElement($xmlContent, LIBXML_COMPACT);
        $check = @file_get_contents(dirname(__FILE__,1)."/$id.xml");
        $RSSCheck = new SimpleXMLElement($check, LIBXML_COMPACT);
        if($RSS->channel->item[0]->guid !== $RSSCheck->channel->item[0]->guid){
            file_put_contents(dirname(__FILE__,1)."/$id.xml",$xmlContent);
            $RSS = new SimpleXMLElement($xmlContent, LIBXML_COMPACT);                    
            $data = $RSS->xpath('/rss/channel/item');
            $html = "";
            foreach ($data as $key=>$news){
                $html .= '<div>'.recent($news->pubDate).' <a target="_blank" href="'.$news->link.'"><strong>'.$news->title.'</strong></a><br/></div>';
            }
            file_put_contents(dirname(__FILE__,1)."/$id.html",$html);
        }
    }
}

function recent($date){
    
    $time = date_create_from_format('D, d M Y H:i:s O',$date);

    $current = strtotime(date('y-m-d'));

    $date    = strtotime(date_format($time, 'y-m-d'));

    $difference = abs(floor(($date - $current)/(60*60*24)));

    if ($difference<4){

        $str = "<span class='text-important'>".date('d/m/y',$date)."</span>";

    }else{

        $str = "<span>".date('d/m/y',$date)."</span>";

    }

    return $str;

}

function getAnnounce(){
    $url = "https://www.dofus.com/fr/forum";
    $doc = dom_init($url);
    $xpath = new DomXPath($doc);
    $announce = $xpath->query("//div[contains(@class,'ak-container ak-main-center')]/div[contains(@class,'ak-container ak-panel ak-announce ')]")[0];   
    if ($announce==null){
        unlink(dirname(__FILE__,1)."/announce.html");
    }else{
        $xml = $announce->ownerDocument->saveXML($announce);
        $for = new DOMDocument();
        $for->preserveWhiteSpace = false;
        $for->validateOnParse = true;
        $for->loadXML($xml);
        $url = null;
        $doc = null;
        $announce = null;
        $xpath = null;
        $xml = null;
        $xpath = new DomXPath($for);    
        $title = $xpath->query("//div[contains(@class,'ak-panel-title')]/text()")[0]->nodeValue;
        $infos = $xpath->query("//div[contains(@class,'ak-panel-title')]/span/text()")[0]->nodeValue;
        $post = $xpath->query("//div[contains(@class,'ak-bbcode-content')]")[0]->nodeValue;
        $html = "<div class=\"row-fluid text-center\">        
                    <div class=\"col-xs-12\">
                        <div class=\"author\">$title</div>
                        <div class=\"post_title\">$infos</div>
                    </div>
                    <div class=\"col-xs-12\">
                        <div class=\"ak-text\">$post</div>                            
                    </div>
                </div>";
        file_put_contents(dirname(__FILE__,1)."/announce.html",$html);
    }
}

//$urgMsgUrl = "https://www.dofus.com/fr/forum";
/*
<div class="ak-container ak-panel ak-announce ak-announce-collapsed"> <div class="ak-panel-title">
<span class="ak-panel-title-icon ak-icon-med ak-warning"></span>
Maintenance exceptionnelle 30/10<span>Par : [Nazkan] - il y a 2 jours</span>
</div>
<div class="ak-panel-content">
<div class="ak-bbcode-content">Bonjour,<br>
<strong>Exceptionnellement, la maintenance hebdomadaire de la semaine prochaine est avancée au <u>Lundi 30/10</u></strong>. Elle débutera à 6h et devrait s'achever vers 11h30 (heure de Paris).<br>
Les <strong>migrations de la semaine seront effectuées lors de cette maintenance</strong>, vous avez donc <strong>jusqu'au lundi matin 6h</strong> pour vous y préparer !<br>
<br>
Merci de votre compréhension.</div>
</div>
<div class="ak-panel-footer">
<span class="ak-announce-toggle" data-message-see="Afficher le message" data-message-hide="Masquer le message">Afficher le message</span> </div>
</div>
in
<div class="ak-container ak-main-center">
*/