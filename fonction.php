<?
function prothttp(){
if (empty( $_SERVER["HTTPS"] )) {
			$valeur = 'http:';
	 }else{
		 $valeur = 'https:';
	 }
	 return $valeur;
}
function base64url_encode2($data) { 
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} 
$units = 5;

	function rating_bar($page_id, $units='', $static='') {
			
	
			
		//set some variables
		$rating_unitwidth = 30;
		$ip = md5($_SERVER['REMOTE_ADDR']);
		if (!$units) {$units = 10;}
		if (!$static) {$static = FALSE;}

		// get votes, values, ips for the current rating bar
		$query = DB::getInstance()->query("SELECT total_votes, total_value, used_ips FROM fullstart WHERE page_id='".$page_id."'");

		// insert the id in the DB if it doesn't exist already
		// see: //www.masugadesign.com/the-lab/scripts/unobtrusive-ajax-star-rating-bar/#comment-121
		$count = $query->rowCount();
		if ($count == 0) {
			$req = "INSERT INTO `fullstart` (`vote_id` ,`page_id` ,`total_votes` ,`total_value` ,`used_ips` ) VALUES (NULL , '".$page_id."', '0', '0', NULL);";
			$result = DB::getInstance()->exec($req);	
		}

		$numbers = $query->fetch(PDO::FETCH_ASSOC);
		
		if ($numbers['total_votes'] < 1) { $count = 0; }
		else { $count=$numbers['total_votes']; }
		
		$current_rating=$numbers['total_value']; //total number of rating added together and stored
		$tense=($count==1) ? "<span itemprop=\"itemReviewed\">votes</span>" : "<span itemprop=\"itemReviewed\">vote</span>"; //plural form votes/vote

		// determine whether the user has voted, so we know how to draw the ul/li
		$req = DB::getInstance()->query("SELECT used_ips FROM fullstart WHERE used_ips LIKE '%".$ip."%' AND page_id='".$page_id."'");
		$voted = $req->rowCount();

		// now draw the rating bar
		$rating_width = @number_format($current_rating/$count,2)*$rating_unitwidth;
		$rating1 = @number_format($current_rating/$count,1);
		$rating2 = @number_format($current_rating/$count,2);

		if ($static == 'static') {

				$static_rater = array();
				$static_rater[] .= "\n".'<div class="ratingblock">';
				$static_rater[] .= '<div id="unit_long'.$page_id.'">';
				$static_rater[] .= '<ul id="unit_ul'.$page_id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
				$static_rater[] .= '<li itemprop="aggregateRating" itemscope="" itemtype="//schema.org/AggregateRating" class="current-rating" style="width:'.$rating_width.'px;">Currently <span itemprop="ratingValue">'.$rating2.'</span>/<span itemprop="bestRating">'.$units.'</span></li>';
				$static_rater[] .= '</ul>';
				$static_rater[] .= '<p class="static"><strong><span itemprop="ratingValue">'.$rating1.'</span></strong>/<span itemprop="bestRating">'.$units.'</span> (<span itemprop="ratingCount">'.$count.'</span>'.$tense.' )</p>';
				$static_rater[] .= '</div>';
				$static_rater[] .= '</div>'."\n\n";

				return join("\n", $static_rater);
		} 
		else {
			
			$rater ='';
			$rater.='<div class="ratingblock">';
			$rater.='<div id="unit_long'.$page_id.'">';
			$rater.='<ul id="unit_ul'.$page_id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
			$rater.='<li class="current-rating" style="width:'.$rating_width.'px;">Currently '.$rating2.'/'.$units.'</li>';

			for ($ncount = 1; $ncount <= $units; $ncount++) { // loop from 1 to the number of units
			   if(!$voted) { // if the user hasn't yet voted, draw the voting stars
				  $rater.='<li><a href="//'.$_SERVER['HTTP_HOST'].'/vote/db.php?j='.$ncount.'&amp;q='.$page_id.'&amp;t='.$ip.'&amp;c='.$units.'" title="'.$ncount.' sur '.$units.'" class="r'.$ncount.'-unit rater" rel="nofollow">'.$ncount.'</a></li>';
			   }
			}
			
			$ncount=0; // resets the count

			$rater.='  </ul>';
			$rater.='  <p itemprop="aggregateRating" itemscope="" itemtype="//schema.org/AggregateRating"';
			if($voted){ $rater.=' class="voted"'; }
			$rater.='><strong><span itemprop="ratingValue">'.$rating1.'</span></strong>/<span itemprop="bestRating">'.$units.'</span> (<span itemprop="ratingCount">'.$count.'</span> '.$tense.')';
			$rater.='<meta itemprop="worstRating" content = "'.$rating1.'"></p>';
			$rater.='</div>';
			$rater.='</div>';
			
			return $rater;
		}
	}
	
	 function uniqueid22()
{
   $random_id_length = 20;
   $rnd_id           = crypt(uniqid(rand(), 1));
   $rnd_id           = strip_tags(stripslashes($rnd_id));
   $rnd_id           = str_replace(".", "", $rnd_id);
   $rnd_id           = strrev(str_replace("/", "", $rnd_id));
   $rnd_id           = substr($rnd_id, 0, $random_id_length);
   return $rnd_id;
} 
function getUrlContent($url){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$data = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
return $data;
}
function autoidtwitter($long_pass)
{
$consonnes = "bcdfghjklmnpqrstvwxz";
$voyelles = "aeiouy";
$mdp='';
for ($i=0; $i < $long_pass; $i++)
{
if (($i % 2) == 0)
{
$mdp = $mdp.substr ($voyelles, rand(0,strlen($voyelles)-1), 1);
}
else
{
$mdp = $mdp.substr ($consonnes, rand(0,strlen($consonnes)-1), 1);
}
}

return $mdp;
}
function autorenewpass($long_pass)
{
$consonnes = "bcdfghjklmnpqrstvwxz";
$voyelles = "aeiouy";
$mdp='';
for ($i=0; $i < $long_pass; $i++)
{
if (($i % 2) == 0)
{
$mdp = $mdp.substr ($voyelles, rand(0,strlen($voyelles)-1), 1);
}
else
{
$mdp = $mdp.substr ($consonnes, rand(0,strlen($consonnes)-1), 1);
}
}

return $mdp;
}
function creatfolder(){
	
	@chmod("./rss/cache", 0777);
	@chmod("./cache", 0777);
	@chmod("./temp", 0777);
	@chmod("./rss/cache", 0777);
if (!file_exists('./rss')) {
	mkdir("./rss", 0777);
}

if (!file_exists('./rss/cache')) {
	mkdir("./rss/cache", 0777);
}
if (!file_exists('./cache')) {
	mkdir("./cache", 0777);
		@chmod("./cache", 0777);
}
if (!file_exists('./cache')) {
	mkdir("../cache", 0777);
}
if (!file_exists('./temp')) {
	mkdir("./temp", 0777);
}
}
function strtoupperaccent($string) {

   $string = strtoupper($string);

   $string = str_replace(
 array('É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Î', 'Ï', 'Ô', 'Ù', 'Û'),
      array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),

     

      $string

   );

   return $string;

}
Function removeaccents($chaine)
    {
     $string= strtr($chaine,
   "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöŌøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
   "aaaaaaaaaaaaoooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
 
     return $string;
    } 
	Function chainelink($chaine)
    {
		$chaine2 = utf8_decode($chaine);
		$chaine1 = strtoupper($chaine2);
     $string= strtr($chaine1,
   "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ,;&$",
   "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn----");
 $string = str_replace(' ','-',$string);	
 $string = str_replace('Ō','O',$string);	
 $string = str_replace('&#332;','O',$string);	
 $string = str_replace('"','',$string);	
 $string = str_replace("'",'',$string);	
 $string = str_replace("/",'-',$string);	
 $string = str_replace(".",'',$string);	
 $string = str_replace("(",'-',$string);	
 $string = str_replace(")",'-',$string);	
 $string = str_replace("[",'-',$string);	
 $string = str_replace("]",'-',$string);	
 $string = str_replace(":",'-',$string);	
 $string = str_replace("/",'-',$string);	
 $string = str_replace("\/",'-',$string);	
     return $string;
    } 
	function remote_image($url){
	
    if(@$size = getimagesize($url))
    {
        $largeur = $size[0];
        $hauteur = $size[1];
	}
	if($largeur > '0' && $largeur > '0'){
	$url1 =$url;
	}else{
		$url1 ='//'.$_SERVER['HTTP_HOST'].'/images/not-found.png';
	}
	return $url1;
	} 

function testimg($url){
	
    if(@$size = getimagesize($url))
    {
        $largeur = $size[0];
        $hauteur = $size[1];
	}
	if($largeur > '0' && $largeur > '0'){
	$retour ='0';
	}else{
		$retour ='1';
	}
		if(preg_match('`static.fullsharez.com`i', $url )){
				$retour ='1';
		}
	return $retour;
	} 
	
function testimgv2($url){
	$head = array_change_key_case(get_headers($url, TRUE));
$type = $head['content-type'];
$filesize = $head['content-length'];

	
if($filesize > '8000' && preg_match('`image`i', $type) && preg_match('`http://|https://`i', $url))
{
$retour ='1';	
	}elseif(preg_match('`acsta.net`i', $url)){
	$retour ='1';
	}else{
		$retour ='0';
	}
	
	return $retour;
	} 
function testlinkstream($lien,$titre,$id,$niv)
{
	if($niv >= '3'){
		$iframe = $lien;
include("fonction_taille.php");

	echo '<a data-toggle="modal" data-target="#test'.$id.'" href="#"  ><span class="label label-info">'.$titre.'</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="test'.$id.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Contrôle du lien</h4>
      </div>
      <div class="modal-body">
                    <div> 
						'.$iframe.'
</div> </div> </div></div></div> ';
}else{
		echo $titre;
	}}
	
	function delmort($table,$id,$niv)
{
		if($niv >= '3')
{
							$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
echo '<a  class="label label-danger" href="'.$nom_de_domaine.'/insert/deleteep.php?id='.$id.'&tb='.$table.'&type=lienmort&retour='.urlencode($_SERVER['REQUEST_URI']).'">Non Mort</a>';
}}	
function testlinkdl($lien,$titre,$id,$niv)
{
	if($niv >= '3'){

	echo '<a class="label label-info" href="'.$lien.'" target="_blank">'.$titre.'</a> ';
	}else{
		echo $titre;
	}}
	
	function simil($table,$mot)
{

									  $goto2 = couperChaine($mot, 1) ;
						$cnx = Utils::connexionbd();
							$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
							if($table=='films_streaming'){
	$reponsecc = $cnx->query("SELECT * FROM ".$table."  WHERE genre LIKE '%".$goto2."%'  group by titre ORDER BY rand() desc limit 9");
		echo'<div class="container">  <div class="jumbotron"><center>';
   while($result1 = $reponsecc->fetch()) {
	   if ($result1['sm']== '0') { $sm ="" ; }else{ $sm = "(Sourds et Malentendants)";}
										////URL REECRITE////
									$idfilm = stripslashes($result1['id']);
									$titrefilm = stripslashes($result1['titre']).'-'.stripslashes($result1['qualite']).'-'.stripslashes($result1['langue']);
									$lienfilm = filmrewrite($idfilm,$titrefilm);
									$description = str_replace('"','',$result1['description']);
										$description = substr($description,0, 100);
										echo (
										   "<a style='padding: 3px;' href=\"".$lienfilm."\"  ><img alt='".$result1['titre']."' src='//static.fullsharez.com/retail.php?src=".$result1['image']."&h=160&w=110'  data-original-title=\"".$result1['titre']." "."" .$result1['langue']." Qualité : ".$result1['qualite']." ".$sm." 
										   
										   Description : ".$description."...\"></a>");
									}}
if($table=='series'){
									$reponsecc = $cnx->query("SELECT * FROM ".$table."  WHERE genre LIKE '%".$goto2."%'  group by titre ORDER BY rand() desc limit 9");
		echo'<div class="container">  <div class="jumbotron"><center>';
   while($result1 = $reponsecc->fetch()) {
	 
										////URL REECRITE////
								$idseries = stripslashes($result1['id']);
									$titreseries = stripslashes($result1['titre']);
									$lienseries = seriesrewrite($idseries,$titreseries);
									$description = str_replace('"','',$result1['description']);
										$description = substr($description,0, 100);
										echo (
										   "<a style='padding: 3px;' href=\"".$lienseries."\"  ><img  alt='".$result1['titre']."'  src='//static.fullsharez.com/retail.php?src=".$result1['image']."&h=160&w=110'  data-original-title=\"".$titreseries."     Description : ".$description."...\"></a>");
									}}
									echo'</div></div></center>';
}
$autoidtw = autoidtwitter(8); /*mot de passe de 8 caracteres */
$renewpass = autorenewpass(8); /*mot de passe de 8 caracteres */

function autoidfb($long_pass)
{
$consonnes = "bcdfghjklmnpqrstvwxz";
$voyelles = "aeiouy";
$mdp='';
for ($i=0; $i < $long_pass; $i++)
{
if (($i % 2) == 0)
{
$mdp = $mdp.substr ($voyelles, rand(0,strlen($voyelles)-1), 1);
}
else
{
$mdp = $mdp.substr ($consonnes, rand(0,strlen($consonnes)-1), 1);
}
}

return $mdp;
}

$autoidfb = autoidfb(8); /*mot de passe de 8 caracteres */

function unix_timestamp($date)
{
	$date = str_replace(array(' ', ':'), '-', $date);
	$c    = explode('-', $date);
	$c    = array_pad($c, 6, 0);
	array_walk($c, 'intval');
 
	return mktime($c[3], $c[4], $c[5], $c[1], $c[2], $c[0]);
}

function bbCode2($texte)
    {
    // Mise en forme du texte
	$nom_de_domaine = ''.$_SERVER['HTTP_HOST'].'';
	$texte = preg_replace('#\[size=(.+)\]#isU', '<span style="font-size:$1%">', $texte);
	
	$texte = str_replace('[/size]', '</span>', $texte);
	$texte = str_replace('[b]', '<b>', $texte);
	$texte = str_replace('[/b]', '</b>', $texte);
	//$texte = preg_replace('#\[b\](.+)\[/b\]#', '<b>$1</b>', $texte);
    $texte = preg_replace('#\[s\](.+)\[/s\]#', '<u>$1</u>', $texte);
    $texte = preg_replace('#\[i\](.+)\[/i\]#', '<i>$1</i>', $texte);
    //$texte = preg_replace('#`\[u\](.+)\[/u\]#','<span style="text-decoration: underline">$1</span>',$texte);
	$texte = str_replace('[u]', '<span style="text-decoration: underline">', $texte);
	$texte = str_replace('[/u]', '</span>', $texte);
	$texte = str_replace('[center]', '<center>', $texte);
	$texte = str_replace('[/center]', '</center>', $texte);
	$texte = str_replace('[quote]', '<blockquote style="background-color: #EBEADD;">', $texte);
	$texte = str_replace('[/quote]', '</blockquote>', $texte);
   $texte = preg_replace('#\[color=(red|green|blue|yellow|purple|olive|silver|grey|orange|pink|brown|navy|\#[A-Za-z0-9]{6})\](.+)\[/color\]#isU', '<span style="color:$1">$2</span>', $texte);
 //$texte = preg_replace("/[size=\[0-9]*/]/", "width='640'", $texte);
		//$texte = preg_replace('#\[size=(\#[0-9])\](.+)\[/size\]#si', '<span style="font-size: $1;%">$2</span>', $texte);
	 $texte = preg_replace('#\[a\](.+)\[/a\]#','<a href="$1" target="_blank">$1</a>',$texte);
     
    // Mise en place des smileys
    $texte = str_replace(':)', '<img src="/forum/images/smileys/souriant.png" title="souriant" alt=":)"/>', $texte);
    $texte = str_replace('=)', '<img src="/forum/images/smileys/souriant.png" title="souriant" alt="=)"/>', $texte);
    $texte = str_replace('^^', '<img src="/forum/images/smileys/content.png" title="content" alt="^^"/>', $texte);
    $texte = str_replace('lol', '<img src="/forum/images/smileys/mortDeRire.png" title="lol" alt="lol"/>', $texte);
    $texte = str_replace(':(', '<img src="/forum/images/smileys/malheureux.png" title="malheureux" alt=":("/>', $texte);
    $texte = str_replace(';)', '<img src="/forum/images/smileys/complice.png" title="complice" alt=";)"/>', $texte);
    $texte = str_replace(':D', '<img src="/forum/images/smileys/lol.png" title="rire" alt=":D"/>', $texte);
    $texte = str_replace(':s', '<img src="/forum/images/smileys/confus.png" title="confus" alt=":s"/>', $texte);
    $texte = str_replace(':o', '<img src="/forum/images/smileys/surpris.png" title="surpris" alt=":O"/>', $texte);
    $texte = str_replace(':|', '<img src="/forum/images/smileys/bof.png" title=":|" alt=":|"/>', $texte);
    $texte = str_replace('%)', '<img src="/forum/images/smileys/fou.png" title="fou" alt="%)"/>', $texte);
    $texte = str_replace(':pleure:', '<img src="/forum/images/smileys/malheureux.png" title="malheureux" alt=":pleure:"/>', $texte);
    $texte = str_replace('mdr', '<img src="/forum/images/smileys/mortDeRire.png" title="mort de rire" alt="mdr"/>', $texte);
     
    return ($texte);
    }
	
	function bbCode3($texte)
    {
    // Mise en forme du texte
	$nom_de_domaine = ''.$_SERVER['HTTP_HOST'].'';
	$texte = preg_replace('#\[size=(.+)\]#isU', '<span style="font-size:$1%">', $texte);
	
	$texte = str_replace('[/size]', '</span>', $texte);
	$texte = str_replace('[b]', '<b>', $texte);
	$texte = str_replace('[/b]', '</b>', $texte);
   $texte = preg_replace('#\[s\](.+)\[/s\]#', '<u>$1</u>', $texte);
    $texte = preg_replace('#\[i\](.+)\[/i\]#', '<i>$1</i>', $texte);
    $texte = preg_replace('#`\[u\](.+)\[/u\]#','<span style="text-decoration: underline">$1</span>',$texte);
	$texte = str_replace('[u]', '<span style="text-decoration: underline">', $texte);
	$texte = str_replace('[/u]', '</span>', $texte);
	$texte = str_replace('[center]', '<center>', $texte);
	$texte = str_replace('[/center]', '</center>', $texte);
	$texte = str_replace('[quote]', '<div id="quote" style="padding-left: 124px;padding-right: 10px;"><blockquote style="background-color: #F6E497; color:grey;border-radius: 15px;border: solid 2px #fff;"><div id="quote" style="padding-left: 4px;padding-right: 4px;">', $texte);
	$texte = str_replace('[/quote]', '</div></blockquote></div>', $texte);
    $texte = preg_replace('#\[color=(red|green|blue|yellow|purple|olive|silver|grey|orange|pink|brown|navy|\#[A-Za-z0-9]{6})\](.+)\[/color\]#isU', '<span style="color:$1">$2</span>', $texte);
//$texte = preg_replace("/[size=\[0-9]*/]/", "width='640'", $texte);
		//$texte = preg_replace('#\[size=(\#[0-9])\](.+)\[/size\]#si', '<span style="font-size: $1;%">$2</span>', $texte);
	// $texte = preg_replace('#\[a\](.+)\[/a\]#','<a href="$1" target="_blank">$1</a>',$texte);
     
    // Mise en place des smileys
	$texte = preg_replace('#\[img\](.+)\[/img\]#isU', '<a href="$1" target="_blank"><img style="max-width: 500px;max-height: 600px;" class="img-responsive" src="$1" /></a>', $texte);
   // $texte = str_replace('[img]','<img  style="max-width: 500px;max-height: 600px;" class="img-responsive" src="',$texte);
  //  $texte = str_replace('[/img]','">',$texte);    
	
	$texte = str_replace('[youtube]https://www.youtube.com/watch?v=','<iframe class="embed-responsive-item" width="300px" height="215" src="https://www.youtube.com/embed/',$texte);
	$texte = str_replace('[youtube]//www.youtube.com/watch?v=','<iframe class="embed-responsive-item" width="300px" height="215" src="https://www.youtube.com/embed/',$texte);
    $texte = str_replace('[/youtube]','" frameborder="0" allowfullscreen></iframe>',$texte);
   	$texte = str_replace('[dailymotion]https://www.dailymotion.com/video/','<iframe class="embed-responsive-item" width="300px" height="215" src="https://www.dailymotion.com/embed/video/',$texte);
	$texte = str_replace('[dailymotion]//www.dailymotion.com/video/','<iframe class="embed-responsive-item" width="300px" height="215" src="https://www.dailymotion.com/embed/video/',$texte);
    $texte = str_replace('[/dailymotion]','" frameborder="0" allowfullscreen></iframe>',$texte);
    $texte = str_replace(':spoiler:','<img height=42 width=45 src="//'.$nom_de_domaine.'/img/smilies/spoiler.png">',$texte);
 $texte = str_replace(':D','<img height=24 width=26 src="//'.$nom_de_domaine.'/img/smilies/icon_e_biggrin.gif">',$texte);
$texte = str_replace('<img height=24 width=26 src="//'.$nom_de_domaine.'/img/smilies/icon_e_surprised.gif">',':o',$texte);
$texte = str_replace(':(','<img height=24 width=26 src="//'.$nom_de_domaine.'/img/smilies/icon_e_sad.gif">',$texte);
$texte = str_replace('=$','<img height=24 width=26 src="//'.$nom_de_domaine.'/img/smilies/icon_redface.gif">',$texte);
$texte = str_replace(':s','<img height=24 width=26 src="//'.$nom_de_domaine.'/img/smilies/icon_e_confused.gif">',$texte);
$texte = str_replace('&lt;3','<img height=50 width=50 src="//'.$nom_de_domaine.'/images/favorie.png">',$texte);
$texte = str_replace('&lt;3_&lt;3','<img height=50 width=50 src="//'.$nom_de_domaine.'/images/smilies/love.gif">',$texte);
$texte = str_replace('(inlove)','<img height=50 width=50 src="//'.$nom_de_domaine.'/img/smilies/Amour.gif">',$texte);
$texte = str_replace(':!:','<img height=55 width=60 src="//'.$nom_de_domaine.'/img/smilies/Dormir_18.gif">',$texte);
$texte = str_replace(':@@:','<img height=55 width=60 src="//'.$nom_de_domaine.'/img/smilies/vomi.gif">',$texte);
$texte = str_replace(':2:','<img height=55 width=60 src="//'.$nom_de_domaine.'/img/smilies/Violence_24.gif">',$texte);
$texte = str_replace(':6:','<img height=55 width=60 src="//'.$nom_de_domaine.'/img/smilies/pensif_28.gif">',$texte);
$texte = str_replace(':7:','<img height=55 width=60 src="//'.$nom_de_domaine.'/img/smilies/Colere_53.gif">',$texte);
$texte = str_replace(':8:','<img height=80 width=80 src="//'.$nom_de_domaine.'/img/smilies/Anniversaire_7.gif">',$texte);
$texte = str_replace(':o', '<img src="//'.$nom_de_domaine.'/img/smilies/icon_e_surprised.gif" title="surpris" alt=":O"/>', $texte);
// $texte = str_replace('merci ','<img height=80 width=80 src="//'.$nom_de_domaine.'/img/smilies/merci.gif">',$texte);
$texte = str_replace(':9:','<img height=80 width=80 src="//'.$nom_de_domaine.'/img/smilies/Amour_4.gif">',$texte);
$texte = str_replace(':10:','<img height=80 width=80 src="//'.$nom_de_domaine.'/img/smilies/Amour_26.gif">',$texte);
$texte = str_replace(':11:','<img height=80 width=80 src="//'.$nom_de_domaine.'/img/smilies/Amour_11.gif">',$texte);
$texte = str_replace(':3:','<img height=50 width=50 src="//'.$nom_de_domaine.'/img/smilies/Appetit_6.gif">',$texte);
$texte = str_replace(':5:','<img height=55 width=60 src="//'.$nom_de_domaine.'/img/smilies/Divers_33.gif">',$texte);
$texte = str_replace(':12:','<img height=50 width=50 src="//'.$nom_de_domaine.'/img/smilies/Colere_16.gif">',$texte);
$texte = str_replace(':13:','<img height=50 width=50 src="//'.$nom_de_domaine.'/img/smilies/Content_41.gif">',$texte);
$texte = str_replace(':14:','<img height=50 width=50 src="//'.$nom_de_domaine.'/img/smilies/Content_26.gif">',$texte);
$texte = str_replace(':15:','<img height=55 width=60 src="//'.$nom_de_domaine.'/img/smilies/Content_51.gif">',$texte);
$texte = str_replace(':16:','<img height=55 width=60 src="//'.$nom_de_domaine.'/img/smilies/Content_3.gif">',$texte);
$texte = str_replace(':4:','<img height=55 width=60 src="images/smilies/Divers_21.gif">',$texte);
$texte = str_replace(':P','<img height=17 width=19 src="//'.$nom_de_domaine.'/img/smilies/icon_razz.gif">',$texte);
$texte = str_replace(';(','<img height=17 width=19 src="//'.$nom_de_domaine.'/img/smilies/icon_cry.gif">',$texte);
$texte = str_replace(';)','<img height=17 width=19 src="//'.$nom_de_domaine.'/img/smilies/icon_e_wink.gif">',$texte);
$texte = str_replace(':evil:','<img height=37 width=39 src="//'.$nom_de_domaine.'/img/smilies/Colere_1.gif">',$texte);
$texte = str_replace('x(','<img height=23 width=25 src="//'.$nom_de_domaine.'/img/smilies/icon_twisted.gif">',$texte);
$texte = str_replace(':lol:','<img height=30 width=30 src="//'.$nom_de_domaine.'/img/smilies/icon_lol.gif">',$texte);
     
    return ($texte);
    }
	
	function debbCode($commentaire)
    {
		$nom_de_domaine = ''.$_SERVER['HTTP_HOST'].'';
    $commentaire = str_replace('<img height=42 width=45 src="//'.$nom_de_domaine.'/images/spoiler.png">',':spoiler:',$commentaire);
 $commentaire = str_replace('<img height=24 width=26 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_e_biggrin.gif">',':D',$commentaire);
$commentaire = str_replace('<img height=24 width=26 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_e_surprised.gif">',':o',$commentaire);
$commentaire = str_replace('<img height=24 width=26 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_e_sad.gif">',':(',$commentaire);
$commentaire = str_replace('<img height=24 width=26 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_redface.gif">','=$',$commentaire);
$commentaire = str_replace('<img height=24 width=26 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_e_confused.gif">',':s',$commentaire);
$commentaire = str_replace('<img height=50 width=50 src="//'.$nom_de_domaine.'/images/favorie.png">','&lt;3',$commentaire);
$commentaire = str_replace('<img height=50 width=50 src="//'.$nom_de_domaine.'/images/smilies/love.gif">','&lt;3_&lt;3',$commentaire);
$commentaire = str_replace('<img height=50 width=50 src="//'.$nom_de_domaine.'/forum/images/smilies/Amour.gif">','(inlove)',$commentaire);
$commentaire = str_replace('<img height=55 width=60 src="//'.$nom_de_domaine.'/images/smilies/Dormir_18.gif">',':!:',$commentaire);
$commentaire = str_replace('<img height=55 width=60 src="//'.$nom_de_domaine.'/images/smilies/Violence_24.gif">',':2:',$commentaire);
$commentaire = str_replace('<img height=55 width=60 src="//'.$nom_de_domaine.'/images/smilies/pensif_28.gif">',':6:',$commentaire);
$commentaire = str_replace('<img height=55 width=60 src="//'.$nom_de_domaine.'/images/smilies/Colere_53.gif">',':7:',$commentaire);
$commentaire = str_replace('<img height=80 width=80 src="//'.$nom_de_domaine.'/images/smilies/Anniversaire_7.gif">',':8:',$commentaire);
$commentaire = str_replace('<img height=80 width=80 src="//'.$nom_de_domaine.'/images/smilies/merci.gif">','merci ',$commentaire);
$commentaire = str_replace('<img height=80 width=80 src="//'.$nom_de_domaine.'/images/smilies/Amour_4.gif">',':9:',$commentaire);
$commentaire = str_replace('<img height=80 width=80 src="//'.$nom_de_domaine.'/images/smilies/Amour_26.gif">',':10:',$commentaire);
$commentaire = str_replace('<img height=80 width=80 src="//'.$nom_de_domaine.'/images/smilies/Amour_11.gif">',':11:',$commentaire);
$commentaire = str_replace('<img height=50 width=50 src="//'.$nom_de_domaine.'/images/smilies/Appetit_6.gif">',':3:',$commentaire);
$commentaire = str_replace('<img height=55 width=60 src="//'.$nom_de_domaine.'/images/smilies/Divers_33.gif">',':5:',$commentaire);
$commentaire = str_replace('<img height=50 width=50 src="//'.$nom_de_domaine.'/images/smilies/Colere_16.gif">',':12:',$commentaire);
$commentaire = str_replace('<img height=50 width=50 src="//'.$nom_de_domaine.'/images/smilies/Content_41.gif">',':13:',$commentaire);
$commentaire = str_replace('<img height=50 width=50 src="//'.$nom_de_domaine.'/images/smilies/Content_26.gif">',':14:',$commentaire);
$commentaire = str_replace('<img height=55 width=60 src="//'.$nom_de_domaine.'/images/smilies/Content_51.gif">',':15:',$commentaire);
$commentaire = str_replace('<img height=55 width=60 src="//'.$nom_de_domaine.'/images/smilies/Content_3.gif">',':16:',$commentaire);
$commentaire = str_replace('<span style="color: #ff0000;"><strong>','[rouge]',$commentaire);
$commentaire = str_replace('</strong></span>','[/rouge]',$commentaire);
$commentaire = str_replace('<img height=55 width=60 src="images/smilies/Divers_21.gif">',':4:',$commentaire);
$commentaire = str_replace('<img height=17 width=19 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_razz.gif">',':P',$commentaire);
$commentaire = str_replace('<img height=17 width=19 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_razz.gif">',':p',$commentaire);
$commentaire = str_replace('<img height=17 width=19 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_cry.gif">',';(',$commentaire);
$commentaire = str_replace('<img height=17 width=19 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_e_wink.gif">',';)',$commentaire);
$commentaire = str_replace('<img height=37 width=39 src="//'.$nom_de_domaine.'/images/smilies/Colere_1.gif">',':evil:',$commentaire);
$commentaire = str_replace('<img height=23 width=25 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_twisted.gif">','x(',$commentaire);
$commentaire = str_replace('<img height=30 width=30 src="//'.$nom_de_domaine.'/forum/images/smilies/icon_lol.gif">',':lol:',$commentaire);
 
    return ($commentaire);
    }
function remote_file_exists($url){
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	    if(@$size = getimagesize($url))
    {
        $largeur = $size[0];
        $hauteur = $size[1];
	}
	if($largeur > '0' && $largeur > '0'){
	 $status = true; 
	}else{
		 $status = false;
	}
	return $status;
	} 
	

 function CreatePass($long_pass)
{
$consonnes = "bcdfghjklmnpqrstvwxz";
$voyelles = "aeiouy";
$mdp='';
for ($i=0; $i < $long_pass; $i++)
{
if (($i % 2) == 0)
{
$mdp = $mdp.substr ($voyelles, rand(0,strlen($voyelles)-1), 1);
}
else
{
$mdp = $mdp.substr ($consonnes, rand(0,strlen($consonnes)-1), 1);
}
}

return $mdp;
}
$mdp = CreatePass(8);
function CreatePass16($long_pass)
{
$consonnes = "bcdfghjklmnpqrstvwxz";
$voyelles = "aeiouy";
$mdp='';
for ($i=0; $i < $long_pass; $i++)
{
if (($i % 2) == 0)
{
$mdp = $mdp.substr ($voyelles, rand(0,strlen($voyelles)-1), 1);
}
else
{
$mdp = $mdp.substr ($consonnes, rand(0,strlen($consonnes)-1), 1);
}
}

return $mdp;
}

$adsheader = CreatePass16(8); /*mot de passe de 8 caracteres */
function likebox($long_pass)
{
$consonnes = "bcdfghjklmnpqrstvwxz";
$voyelles = "aeiouy";
$mdp='';
for ($i=0; $i < $long_pass; $i++)
{
if (($i % 2) == 0)
{
$mdp = $mdp.substr ($voyelles, rand(0,strlen($voyelles)-1), 1);
}
else
{
$mdp = $mdp.substr ($consonnes, rand(0,strlen($consonnes)-1), 1);
}
}

return $mdp;
}

$likebox = likebox(8); /*mot de passe de 8 caracteres */


function couperChaine($chaine, $nbmot) {
  $mots = explode(' ', $chaine);
  $res  = array();
  for($u=0; $u<$nbmot; $u++) {
    if(isset($mots[$u])) {
      $res[] = $mots[$u];
    } else {
      break;
    }
  }
  return implode(' ', $res);
}

function erreur4014($uris,$type) {
	$cnx = Utils::connexionbd();
$uris= mysql_real_escape_string($uris);
$uris = str_replace('1080P',' ',$uris);
$uris = str_replace('R5',' ',$uris);
$uris = str_replace('720P',' ',$uris);
$uris = str_replace('#[0-9]#',' ',$uris);	
$uris = str_replace('0',' ',$uris);	
$uris = str_replace('1',' ',$uris);	
$uris = str_replace('2',' ',$uris);	
$uris = str_replace('3',' ',$uris);	
$uris = str_replace('4',' ',$uris);	
$uris = str_replace('5',' ',$uris);	
$uris = str_replace('6',' ',$uris);	
$uris = str_replace('7',' ',$uris);	
$uris = str_replace('8',' ',$uris);	
$uris = str_replace('9',' ',$uris);	
$uris = str_replace('/',' ',$uris);
$uris = str_replace('-',' ',$uris);
$uris = str_replace('.',' ',$uris);
$uris = str_replace('html',' ',$uris);
$uris = str_replace('films',' ',$uris);
$uris = str_replace('film',' ',$uris);
$uris = str_replace('series',' ',$uris);
$uris = str_replace('serie',' ',$uris);
$uris = str_replace('streaming',' ',$uris);
$uris = str_replace('téléchargement',' ',$uris);
$uris = str_replace('telechargement',' ',$uris);
$uris = str_replace('saison',' ',$uris);
$uris = str_replace('episode',' ',$uris);
$uris = str_replace('fr',' ',$uris);
$uris = str_replace('FR',' ',$uris);
$uris = str_replace('VOSTFR',' ',$uris);
$uris = str_replace('VO',' ',$uris);
$uris = str_replace('DVDRIP',' ',$uris);
$uris = str_replace('BDRIP',' ',$uris);
$uris = str_replace('TS',' ',$uris);
$uris = str_replace('CAM',' ',$uris);
$uris = str_replace('mangas',' ',$uris);
$uris = str_replace('manga',' ',$uris);
$uris = str_replace('  ',' ',$uris);	
$uris = trim($uris);
$uris2 = couperChaine($uris, 1) ;
$uris2 = mb_strtolower($uris2);
$variabledeb =		"<div class=\"container\">
<div class=\"jumbotron\">
<h1 style=\"font-size: 15px;\">Il semblerait que nous ne soyons pas en mesure de trouver  <a href=\"https://".$_SERVER['HTTP_HOST']."/recherche_site_unique.php?s=".$uris."\">".$uris."</a> . Essayez en lançant une recherche, ou à l’aide de l’un des lien ci-dessous.</h1>
";
if($type == 'film'){

	$reponse = $cnx->query("SELECT * FROM fiche WHERE titre LIKE '%".$uris."%' and categorie ='film' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
$idfilm = stripslashes($result1['id']);
$titrefilm = stripslashes($result1['titre']);
$lienfilm = filmrewriteunique($idfilm,$titrefilm);
$variabletext .= "Films <a href=\"".$lienfilm."\" >".$result1['titre']." </a></p>
";						}

		}		  
		

function remote_image($url){
	
    if(@$size = getimagesize($url))
    {
        $largeur = $size[0];
        $hauteur = $size[1];
	}
	if($largeur > '0' && $largeur > '0'){
	$url1 =$url;
	}else{
		$url1 ='//'.$_SERVER['HTTP_HOST'].'/images/not-found.png';
	}
	return $url1;
	} 
if($type == 'serie'){
		////URL REECRITE////
$reponse = $cnx->query("SELECT * FROM series WHERE titre LIKE '%".$uris2."%' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
	$idseries = stripslashes($result1['id']);
									$titreseries = stripslashes($result1['titre']);
									$lienseries = seriesrewriteunique($idseries,$titreseries);
$variabletext .= "Séries <a href=\"".$lienseries."\" >".$result1['titre']." </a></p>";								}		  
}

$variablefin = "</div>
</div>";
$variable = ''.$variabledeb.''.$variabletext.''.$variablefin.'';
	return $variable;
}
function messagerie($pseudo,$id,$newpm,$class) {
	$cnx = Utils::connexionbd();
	$name = $pseudo;
$newpm = $newpm;
$userId = $id;
if ($newpm == '1') { 
$requete = $cnx->exec("UPDATE membre SET newpm = '0' WHERE id = '$userId'") ;
 } 
$unread_pms  = $cnx->query("SELECT count(pmid) AS total_unread_pms FROM privatemessages WHERE touser = '$userId' && status = '0'");
$get_unread_pms  = $unread_pms->fetch(PDO::FETCH_ASSOC);
$userUnreadpms = $get_unread_pms['total_unread_pms'];

if ($userUnreadpms > '0'){ $msg= '<span class="'.$class.'" >'.$userUnreadpms.'</span> ';} else {$msg ='';} 
echo $msg;
}
 function delfavorie($id,$pseudo) {
	$cnx = Utils::connexionbd();
	if(!empty($id) or !empty($pseudo) ){
$requete = $cnx->exec("DELETE FROM favorie2  WHERE id =".(int)$id." and pseudo_du_membre like '".stripslashes($pseudo)."'") ;
		$delretour = '//'.$_SERVER['HTTP_HOST'].'/gestion-favoris.php?del=ok';
}else{
	$delretour = '//'.$_SERVER['HTTP_HOST'].'/gestion-favoris.php?del=no';
}

	$delfavor = header('Location: '.$delretour.'');
return $delfavor;
}

function statut($pseudo) {
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos  = $cnx->query("SELECT * FROM membre WHERE pseudo = '".$pseudo."'");
$requette99  = $infos->fetch(PDO::FETCH_ASSOC);

	if ($requette99['vip'] == '1') {
																echo "<span style='color: #06E6D0;'>[Staff_VIP]</span></strong></span>";
															} else{
															if ($requette99['niveau_du_membre'] >= '4') {
																echo "<strong><span style='color: #ff0000;'>[Staff_Administrateur]</span></strong>";
															
															} elseif ($requette99['niveau_du_membre'] >= '3') {
																echo "<strong><span style='color: #46b8da;'>[Staff_Modérateur]</span></strong></strong>";
															} elseif ($requette99['niveau_du_membre'] >= '2') {
																echo "<strong><span style='color: #F6E497;'>[Staff_Ajouteur]</span></strong>";
																} elseif ($requette99['niveau_du_membre'] >= '0') {
																echo "<strong><span style='color: #fff;'>[Staff_Membre]</span></strong>";
															
															}
															}
}
	function statut2($niv,$vip) {


	if ($vip == '1') {
																$statut2 = "<strong style='color: #06E6D0;'>[Staff_VIP]</strong>";
															} else{
															if ($niv >= '4') {
																$statut2 = "<strong style='color: #ff0000;'>[Staff_Administrateur]</strong>";
															
															} elseif ($niv >= '3') {
																$statut2 = "<strong style='color: #46b8da;'>[Staff_Modérateur]</strong>";
															} elseif ($niv >= '2') {
																$statut2 ="<strong style='color: #F6E497;'>[Staff_Ajouteur]</strong>";
																
																
																} elseif ($niv >= '0') {
																$statut2 = "<strong style='color: #fff;'>[Staff_Membre]</strong>";
															
															}
														
															}	
															return $statut2;
}
function viewDate($date){
	$heure='';
  if(date('Y-m-d', strtotime($date)) == date('Y-m-d')) return ''.$heure.'';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 1 DAY'))) return 'Hier ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 2 DAY'))) return 'Il y a 2 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 3 DAY'))) return 'Il y a 3 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 4 DAY'))) return 'Il y a 4 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 5 DAY'))) return 'Il y a 5 Jours '; 
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 6 DAY'))) return 'Il y a 6 Jours '; 
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 7 DAY'))) return 'Il y a 7 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 8 DAY'))) return 'Il y a 8 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 9 DAY'))) return 'Il y a 9 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 10 DAY'))) return 'Il y a 10 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 11 DAY'))) return 'Il y a 11 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 12 DAY'))) return 'Il y a 12 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 13 DAY'))) return 'Il y a 13 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 14 DAY'))) return 'Il y a 14 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 15 DAY'))) return 'Il y a 15 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 16 DAY'))) return 'Il y a 16 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 17 DAY'))) return 'Il y a 17 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 18 DAY'))) return 'Il y a 18 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 19 DAY'))) return 'Il y a 19 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 20 DAY'))) return 'Il y a 20 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 21 DAY'))) return 'Il y a 21 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 22 DAY'))) return 'Il y a 22 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 23 DAY'))) return 'Il y a 23 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 24 DAY'))) return 'Il y a 24 Jours ';
  else if(date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime(date('Y-m-d').' - 25 DAY'))) return 'Il y a 25 Jours ';
  else return $date;
}
function countmort($table){
	global $cnx;
	$qz = $cnx->query("SELECT * FROM ".$table." WHERE  mort = 'Mort' or mort = 'Erreur'");
$resulcount = $qz->rowCount();
$countsection = '<span class="badge" style="background-color: red;">'.$resulcount.'</span>';
return $countsection;
}
function countheb($table){
	global $cnx;
	$qz = $cnx->query("SELECT * FROM ".$table." WHERE  active = '0' ");
$resulcount = $qz->rowCount();
$countsection = $resulcount;
return $countsection;
}
function imgheb($urlorigine,$urlsecureorigine){
		$urlorigine = str_replace('SRC','src',$urlorigine);
		$urlsecureorigine = str_replace('SRC','src',$urlsecureorigine);
		$urlorigine = str_replace("'",'"',$urlorigine);
		$urlsecureorigine = str_replace("'",'"',$urlsecureorigine);
	if(preg_match('`<iframe|src="`i', $urlorigine)) {
		$url= extstres22(''.$urlorigine.'', 'src="', '"');
	}else{
		$url = $urlorigine;
		
	}
	if(preg_match('`<iframe|src="`i', $urlsecureorigine)) {
		$urlsecure= extstres22(''.$urlsecureorigine.'', 'src="', '"');
	}else{
		$urlsecure = $urlsecureorigine;
		
	}
		$prehost = str_replace('hqq','netu',$url);
		 $host =	parse_url($prehost,PHP_URL_HOST );
		 if(empty($url)){
			 		$prehost = str_replace('hqq','netu',$urlsecure);
		 $host =	parse_url($prehost,PHP_URL_HOST );
		 }else{
			 		$prehost = str_replace('hqq','netu',$url);
		 $host =	parse_url($prehost,PHP_URL_HOST );
		 }
		 $valeur = "<img   src='//www.google.com/s2/favicons?domain=".$host."' style='text-align:left;' width='20px' height='20px' title='".$host."'  alt='".$host."' class='tip' rel='popover' data-trigger='hover' data-content='' data-original-title='".$host."' data-html='true'>";

		 
		 return $valeur;
		 }
function trouveheb($liens){
	global $cnx;
	
	$liens = str_replace('SRC=','src=',$liens);
	if(preg_match('`src=`i', $liens )){
	$liens = str_replace("'",'"',$liens);
	$aaa=  extstres22($liens, 'src="','"');
	}else{
	$aaa=  $liens;
	}
	$host =	parse_url($aaa,PHP_URL_HOST );
	$infos = $cnx->query("SELECT * FROM hebergeurs WHERE  domaine like '%".$host."%' ");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);
$resultaheb = $zz['id'];
if($resultaheb == ''){
$countsection = '0';
}else{
	$countsection = $resultaheb;
}
return $countsection;
}

function trouveep($liens){
	global $cnx;
	 $requeseries1 = $cnx->query("SELECT * FROM episodes  where lien like '%".trim($liens)."%' or protectiframe like '%".trim($liens)."%' or liendlprotect like '%".trim($liens)."%'");
    $verifseries1  = $requeseries1->fetch(PDO::FETCH_ASSOC);	
	if($verifseries1['id'] == ''){
		$valeurretour= 'ok';
	
	}else{
		$valeurretour1= 'déjà présent '.$liens;
		$valeurretour= urlencode($valeurretour1);
	}
	return $valeurretour;
}
function trouvelink($liens){
	global $cnx;
	 $requeseries1 = $cnx->query("SELECT * FROM lien_fiche  where lien like '%".trim($liens)."%' or lienprotect like '%".trim($liens)."%'");
    $verifseries1  = $requeseries1->fetch(PDO::FETCH_ASSOC);	
	if($verifseries1['id'] == ''){
		$valeurretour= 'ok';
	
	}else{
		$valeurretour1= 'déjà présent '.$liens;
		$valeurretour= urlencode($valeurretour1);
	}
	return $valeurretour;
}
function modepmangazz($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM episodes_mangas WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	
if($zz['underground']=='0'){$underground ='non';}else{$underground ='oui';}
if($zz['news']=='0'){$news ='non';}else{$news ='oui';}

			
$Oddzz1 = ' <a data-toggle="modal" data-target="#edit'.$idep.'" href="#"   ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="edit'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_episodes_mangas.php" method="POST">
           
<div class="form-group">
<label for="Langue" class="col-lg-2 control-label">Langue</label>
<div class="col-lg-10">
<select class="form-control" name="Langue" id="Langue">
<option value="'.$zz['Langue'].'">'.$zz['Langue'].'</option>
											<option value="FR">FR (Langue francaise)</option>
											<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
											<option value="VO">VO (Version original)</option>
</select>

</div>
</div><div class="form-group">
<label for="underground" class="col-lg-2 control-label">underground</label>
<div class="col-lg-10">
<select class="form-control" name="underground" id="underground">
<option value="'.$zz['underground'].'">'.$underground.'</option>
											<option value="0">Non</option>
											<option value="1">Oui</option>
</select>

</div>
</div>
</br></br></br><div class="form-group">
<label for="underground" class="col-lg-2 control-label">News</label>
<div class="col-lg-10">
<select class="form-control" name="news" id="news">
<option value="'.$zz['news'].'">'.$news.'</option>
											<option value="0">Non</option>
											<option value="1">Oui</option>
</select>

</div>
</div>
</br></br></br>

<div class="form-group">
<label for="saisons" class="col-lg-2 control-label">saisons</label>
<div class="col-lg-10">
<select class="form-control" name="saisons" id="saisons">
<option value="'.$zz['saisons'].'">'.$zz['saisons'].'</option>';

$i3 = 0;
while ($i3 <= 40)
{

$Oddzz2 .= '<option value="'.$i3.'">'.$i3.'</option>';
$i3 = $i3 + 1;
}

$Oddzz3 = '
</select>
</div>
</div>
</br></br>

<div class="form-group">
<label for="titre" class="col-lg-2 control-label">Episode</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="titre" value="'.$zz['titre'].'">
<input type="hidden" class="form-control" name="titre_mangas" value="'.$zz['titre_mangas'].'">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
</div>
</div>

</br></br>
<div class="form-group">
<label for="qualite" class="col-lg-2 control-label">Qualité</label>
<div class="col-lg-10">
<select class="form-control" name="qualite" id="qualite">
<option value="'.$zz['qualite'].'">'.$zz['qualite'].'</option>
				<option value="WEBRIP">WEBRIP</option>
										<option value="DVDRIP">DVDRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
</select>

</div>
</div>
</br></br>
<div class="form-group">
 				<label for="titre" class="col-lg-2 control-label">Date réélle de sortie</label>
				<div class="col-lg-10">

       <input id="datetimepicker'.$zz['id'].'" name="post_date_gmt2"  class="form-control"  type="text" value="'.$zz['post_date_gmt2'].'">

                </div>
            </div>
      
        <script type="text/javascript">
            $(function () {
				jQuery("#datetimepicker'.$zz['id'].'").datetimepicker();
               element.datetimepicker({
  format:      "YYYY-MM-DDTHH:mm:ssZ",
  formatTime:  "HH:mm",
  formatDate:  "YYYY-MM-DD",
})
            });
        </script>
</br></br>
	  

									
									<div class="form-group">
<label for="lien" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<textarea class="form-control" rows="3" name="lien" id="lien">'.$zz['lien'].'</textarea></br>
</div>
</div>

</br></br>
 <div class="form-group">
                    <label for="tag" class="col-lg-2 control-label">Tag (sépare chaque mot par un espace)</label>
                    <div class="col-lg-10">
            <input type="text" class="form-control" name="tag"  id="tag" value="'.$zz['tag'].'"></br>
              
				
                    </div>
			</div>  	</br>
</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
					<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="type" value="mangasstream">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>					
				  </br></br><a href="//www.mangas-anime.com/dffjlfvdofksksd.php" target="_blank">efface cache manga animé</a></br></br></div> </div> </div></div></div>';
				$Oddzz=  ''.$Oddzz1.''.$Oddzz2.''.$Oddzz3.'';
				  return $Oddzz;
}
					
					
					}	
					
function modepseries($idep,$titre,$saisons,$Langue,$tag,$lien,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';



		$tag= '  <div class="control-group" >
                    <label for="tag" class="control-label"  style="width: 0px;text-align: left;">Tag:</label>
                      <div class="controls">
            <input type="text" class="span6" name="tag"  id="tag" value="'.$tag.'" maxlength="10">

                    </div>
			</div>';
$Oddzz1 = ' <a data-toggle="modal" data-target="#edit'.$idep.'" href="#"   ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="edit'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien</h4>
      </div>
      <div class="modal-body">
                  <div class="widget-body form">
					<form name="insertion"  class="form-horizontal" action="'.$nom_de_domaine.'/scripts/modifier_episodes_streaming.php" method="POST">
     <div class="control-group">
      <label class="control-label" style="width: 0px;text-align: left;">Langue</label>
                                 <div class="controls">
                                    <select  class="btn dropdown-toggle"  name="Langue" id="Langue" tabindex="1">
<option value="'.$Langue.'">'.$Langue.'</option>
											<option value="FR">FR (Langue francaise)</option>
											<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
											<option value="VO">VO (Version original)</option>
                                    </select>
                                 </div>
                              </div>      

<div class="control-group" >
<label for="saisons" class="control-label"  style="width: 0px;text-align: left;">saisons</label>
 <div class="controls">
<select class="btn dropdown-toggle" name="saisons" id="saisons">
<option value="'.$saisons.'">'.$saisons.'</option>';
$Oddzz2='';
$i3 = 0;
while ($i3 <= 40)
{

$Oddzz2 .= '<option value="'.$i3.'">'.$i3.'</option>';
$i3 = $i3 + 1;
}

$Oddzz3 = '
</select>
</div>
</div>
 <div class="control-group" >
<label for="titre" class="control-label"  style="width: 0px;text-align: left;">Episode</label>
 <div class="controls">
<input type="text" class="span6" name="titre" value="'.$titre.'">
<input type="hidden"  name="id" value="'.$idep.'">
</div>
</div>
 <div class="control-group" >
<label for="lien" class="control-label"  style="width: 0px;text-align: left;">lien</label>
 <div class="controls">
<textarea class="span12" rows="3" name="lien" id="lien">'.$lien.'</textarea></br>
</div>
</div>

</br>
'.$tag.'
 <input type="submit" class="btn btn-success" value="Modifier"></form>
					<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$idep.'">
					<input type="hidden" class="form-control" name="type" value="seriesstream">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>					
				  </div> </div> </div></div></div>';
				$Oddzz=  ''.$Oddzz1.''.$Oddzz2.''.$Oddzz3.'';
				  return $Oddzz;
}
					
					
					}
					
					
					function modepseriesdl($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM episodes_telechargement WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			
$Oddzz1 = '<a class="label label-info" data-toggle="modal" data-target="#modifepdl'.$idep.'" href="#"  >Editer</a>
<!-- Modal -->
<div class="modal fade" id="modifepdl'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					'.$zz['raison_mort'].'
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_episodes_dl.php" method="POST">
           
<div class="form-group">
<label for="langue" class="col-lg-2 control-label">langue</label>
<div class="col-lg-10">
<select class="form-control" name="langue" id="langue">
<option value="'.$zz['langue'].'">'.$zz['langue'].'</option>
											<option value="FR">FR (Langue francaise)</option>
											<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
											<option value="VO">VO (Version original)</option>
</select>

</div>
</div>
</br></br></br>



<div class="form-group">
<label for="saisons" class="col-lg-2 control-label">saisons</label>
<div class="col-lg-10">
<select class="form-control" name="saisons" id="saisons">
<option value="'.$zz['saisons'].'">'.$zz['saisons'].'</option>';
$i3 = 0;
while ($i3 <= 40)
{

$Oddzz2 .='<option value="'.$i3.'">'.$i3.'</option>';
$i3 = $i3 + 1;
}

$Oddzz3='
</select>
</div>
</div>
</br></br>

<div class="form-group">
<label for="titre" class="col-lg-2 control-label">Episode</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="titre" value="'.$zz['titre'].'">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
</div>
</div>

</br></br>
	  
<div class="form-group">
<label for="type" class="col-lg-2 control-label">Qualité</label>
<div class="col-lg-10">
<select class="form-control" name="type" id="type">
<option value="'.$zz['type'].'">'.$zz['type'].'</option>
				<option value="WEBRIP">WEBRIP</option>
										<option value="DVDRIP">DVDRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
</select>

</div>
</div>
</br></br></br>
									
									<div class="form-group">
<label for="lien" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<input class="form-control"  name="lien" id="lien" value="'.$zz['lien'].'"></br>
</div>
</div>
</br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse"  value="'.$zz['mdp'].'"></br>
</div>
</div>
</br></br>
</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
					<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
						<input type="hidden" class="form-control" name="type" value="seriesdl">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>					
				  </div> </div> </div></div></div>';
				$Oddzz=  ''.$Oddzz1.''.$Oddzz2.''.$Oddzz3.'';
				  return $Oddzz;
}
					
					
					}function modepdocdl($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM episodes_documentaire_ddl WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			
$Oddzz1 = '<a class="label label-info" data-toggle="modal" data-target="#modifepdl'.$idep.'" href="#"  >Editer</a>
<!-- Modal -->
<div class="modal fade" id="modifepdl'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_episodes_doc_dl.php" method="POST">
           
<div class="form-group">
<label for="langue" class="col-lg-2 control-label">langue</label>
<div class="col-lg-10">
<select class="form-control" name="langue" id="langue">
<option value="'.$zz['Langue'].'">'.$zz['Langue'].'</option>
											<option value="FR">FR (Langue francaise)</option>
											<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
											<option value="VO">VO (Version original)</option>
</select>

</div>
</div>
</br></br></br>



<div class="form-group">
<label for="saisons" class="col-lg-2 control-label">saisons</label>
<div class="col-lg-10">
<select class="form-control" name="saisons" id="saisons">
<option value="'.$zz['saisons'].'">'.$zz['saisons'].'</option>';
$i3 = 0;
while ($i3 <= 40)
{

$Oddzz2 .='<option value="'.$i3.'">'.$i3.'</option>';
$i3 = $i3 + 1;
}

$Oddzz3='
</select>
</div>
</div>
</br></br>

<div class="form-group">
<label for="titre" class="col-lg-2 control-label">Episode</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="titre" value="'.$zz['titre'].'">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
</div>
</div>

</br></br>
	  
<div class="form-group">
<label for="type" class="col-lg-2 control-label">Qualité</label>
<div class="col-lg-10">
<select class="form-control" name="type" id="type">
<option value="'.$zz['type'].'">'.$zz['type'].'</option>
				<option value="WEBRIP">WEBRIP</option>
										<option value="DVDRIP">DVDRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
</select>

</div>
</div>
</br></br></br>
									
									<div class="form-group">
<label for="lien" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<input class="form-control"  name="lien" id="lien" value="'.$zz['lien'].'"></br>
</div>
</div>
</br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse"  value="'.$zz['mdp'].'"></br>
</div>
</div>
</br></br>
</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
					<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="type" value="epdocdl">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>					
				  </div> </div> </div></div></div>';
				$Oddzz=  ''.$Oddzz1.''.$Oddzz2.''.$Oddzz3.'';
				  return $Oddzz;
}
					
					
					}	function modepmangadl($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM episodes_mangas_ddl WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			if($zz['underground']=='0'){$underground ='non';}else{$underground ='oui';}
$Oddzz1 = '<a class="label label-info" data-toggle="modal" data-target="#modifepdl'.$idep.'" href="#"  >Editer</a>
<!-- Modal -->
<div class="modal fade" id="modifepdl'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_episodes_mangas_dl.php" method="POST">
           
<div class="form-group">
<label for="langue" class="col-lg-2 control-label">langue</label>
<div class="col-lg-10">
<select class="form-control" name="langue" id="langue">
<option value="'.$zz['langue'].'">'.$zz['langue'].'</option>
											<option value="FR">FR (Langue francaise)</option>
											<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
											<option value="VO">VO (Version original)</option>
</select>

</div>
</div>
</br></br></br>
<div class="form-group">
<label for="underground" class="col-lg-2 control-label">underground</label>
<div class="col-lg-10">
<select class="form-control" name="underground" id="underground">
<option value="'.$zz['underground'].'">'.$underground.'</option>
											<option value="0">Non</option>
											<option value="1">Oui</option>
</select>

</div>
</div>
</br></br></br>

<div class="form-group">
<label for="saisons" class="col-lg-2 control-label">saisons</label>
<div class="col-lg-10">
<select class="form-control" name="saisons" id="saisons">
<option value="'.$zz['saison'].'">'.$zz['saison'].'</option>';
$i3 = 0;
while ($i3 <= 40)
{

$Oddzz2 .='<option value="'.$i3.'">'.$i3.'</option>';
$i3 = $i3 + 1;
}

$Oddzz3='
</select>
</div>
</div>
</br></br>

<div class="form-group">
<label for="titre" class="col-lg-2 control-label">Episode</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="titre" value="'.$zz['titre'].'">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
<input type="hidden" class="form-control" name="titre_mangas" value="'.$zz['titre_mangas'].'">
</div>
</div>

</br></br>
	  
<div class="form-group">
<label for="qualite" class="col-lg-2 control-label">Qualité</label>
<div class="col-lg-10">
<select class="form-control" name="qualite" id="qualite">
<option value="'.$zz['qualite'].'">'.$zz['qualite'].'</option>
				<option value="WEBRIP">WEBRIP</option>
										<option value="DVDRIP">DVDRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
</select>

</div>
</div>
</br></br></br>
									
									<div class="form-group">
<label for="lien" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<input class="form-control"  name="lien" id="lien" value="'.$zz['lien'].'"></br>
</div>
</div>
</br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse"  value="'.$zz['mdp'].'"></br>
</div>
</div>
</br></br>
</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
					<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="type" value="mangadl">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>					
				  </div> </div> </div></div></div>';
				$Oddzz=  ''.$Oddzz1.''.$Oddzz2.''.$Oddzz3.'';
				  return $Oddzz;
}
					
					
					}	
					
								
function modifiches($id,$cat,$niv) {	
if($niv >= '3'){
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
if($cat == 'film'){
$mod = '<a href="'.$nom_de_domaine.'/sb-admin/modification-film.php?idfilms='.$id.'" target="_blank" class="label label-warning">Modifier la fiche</a></br></br>';
}
if($cat == 'ebook_dl'){
$mod = '<a href="'.$nom_de_domaine.'/sb-admin/modification-ebook-telechargement.php?idebook='.$id.'" target="_blank" class="label label-warning">Modifier la fiche</a></br></br>';
}
if($cat == 'jeux_dl'){
$mod = '<a href="'.$nom_de_domaine.'/sb-admin/modification-jeux-telechargement.php?idjeux='.$id.'" target="_blank" class="label label-warning">Modifier la fiche</a></br></br>';
}
if($cat == 'manga'){
$mod = '<a href="'.$nom_de_domaine.'/sb-admin/modification-mangas.php?idmangas='.$id.'" target="_blank" class="label label-warning">Modifier la fiche</a></br></br>';
}
if($cat == 'logiciel'){
$mod = '<a href="'.$nom_de_domaine.'/sb-admin/modification-logiciel-telechargement.php?idlogiciel='.$id.'" target="_blank" class="label label-warning">Modifier la fiche</a></br></br>';
}
if($cat == 'serie'){
$mod = '<a href="'.$nom_de_domaine.'/sb-admin/modification-series.php?idseries='.$id.'" target="_blank" class="label label-warning">Modifier la fiche</a></br></br>';
}if($cat == 'docu'){
$mod = '<a href="'.$nom_de_domaine.'/sb-admin/modification-doc.php?iddoc='.$id.'" target="_blank" class="label label-warning">Modifier la fiche</a></br></br>';
}

}
return $mod;
}


function modalepserie($idep,$frame,$heb) {
	$linkfinal='  <!-- Button trigger modal -->
<a class="label label-warning" data-toggle="modal" data-target="#epser'.$idep.'" href="#">Lecture</a>
<!-- Modal -->
<div class="modal fade" id="epser'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="
    padding-top: 10%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Lecteur '.$heb.'</h4>
      </div>
      <div class="modal-body">
 
                    <div>
            <center>'.$frame.'</center>
 </div> </div> </div></div></div>';
	
	
return $linkfinal;
}

function modalev2link($idep,$frame,$heb,$table) {
	$linkfinal='  <!-- Button trigger modal -->
<a class="label label-warning" data-toggle="modal" data-target="#epser'.$idep.'" onclick="$.get(\'//'.$_SERVER['HTTP_HOST'].'/pagecount.php?id='.$idep.'&table='.$table.'\');" href="#">Lecture</a>
<!-- Modal -->
<div class="modal fade" id="epser'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="
    padding-top: 10%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Lecteur '.$heb.'</h4>
      </div>
      <div class="modal-body">
 
                    <div>
            <center>'.$frame.'</center>
 </div> </div> </div></div></div>';
	
	
return $linkfinal;
}function modalev3link($idep,$frame,$heb,$table) {
	$frame = str_replace('SRC','src',$frame);
$frame = str_replace('\'','"',$frame);
$liendsfsdf= extstres22($frame, 'src="', '"');
$liendsfsdf = str_replace('protect-iframe.com/embed-','ifp.re/',$liendsfsdf);
$liendsfsdf = str_replace('ifp.re','pframe.eu',$liendsfsdf);
$linkfinal='  <!-- Button trigger modal -->
<a class="label label-warning" data-toggle="modal" data-target="#epser'.$idep.'" onclick="displaymodalev3link'.($idep).'()" href="#">Lecture</a>
<!-- Modal -->
<div class="modal fade" id="epser'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" onclick="displaymodalev3linkclose'.($idep).'()">
  <div class="modal-dialog modal-lg" style="
    padding-top: 10%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"  onclick="displaymodalev3linkclose'.($idep).'()"><span aria-hidden="true"  >&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Lecteur '.$heb.'</h4>
      </div>
      <div class="modal-body">
 
                    <div>
        <center><div class="alert alert-dismissible alert-warning">
                <button type="button" class="close" data-dismiss="alert">×</button>
               Si vous ne voyez aucune video veuillez desactivé votre bloqueur de pub ou metter ifp.re et protect-iframe.com en exception car le filtre easy Fr on décidé de bloqué entiérement certain site (qui ne passe pas à la caisse comme google)
              </div>
		<iframe    id="modalev3link'.($idep).'" name="modalev3link'.($idep).'" frameborder="0" scrolling="no" width="100%" height="480" webkitAllowFullScreen="true" mozallowfullscreen="true" allowFullScreen="true" ></iframe>
		<a href="'.$liendsfsdf.'" target="_blank">Ouvrir votre video</a> </center><script>
function displaymodalev3link'.($idep).'()  {
document.getElementById("modalev3link'.($idep).'").src = "'.$liendsfsdf.'";
$.get(\'http://'.$_SERVER['HTTP_HOST'].'/pagecount.php?id='.$idep.'&table='.$table.'\');
	 }
	 function displaymodalev3linkclose'.($idep).'()  {
document.getElementById("modalev3link'.($idep).'").src = "";
	 }</script>
 </div> </div> </div></div></div>';
	
	
return $linkfinal;
}
	
	
	function modepdoc($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM episodes_documentaire WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			
$odd1 = ' <a data-toggle="modal" data-target="#modifdocustream'.$idep.'" href="#"  ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="modifdocustream'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_episodes_documentaire.php" method="POST">
           
<div class="form-group">
<label for="Langue" class="col-lg-2 control-label">Langue</label>
<div class="col-lg-10">
<select class="form-control" id="Langue" name="Langue">
<option value="'.$zz['Langue'].'">'.$zz['Langue'].'</option>
											<option value="FR">FR (Langue francaise)</option>
											<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
											<option value="VO">VO (Version original)</option>
</select>

</div>
</div>

</br></br></br>

<div class="form-group">
<label for="saisons" class="col-lg-2 control-label">saisons</label>
<div class="col-lg-10">
<select class="form-control" id="saisons" name="saisons">
<option value="'.$zz['saisons'].'">'.$zz['saisons'].'</option>';
$i3 = 0;
while ($i3 <= 40)
{

$odd2 = '<option value="'.$i3.'">'.$i3.'</option>';
$i3 = $i3 + 1;
}

$odd3 = '
</select>
</div>
</div>
</br></br>

<div class="form-group">
<label for="titre" class="col-lg-2 control-label">Episode</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="titre" value="'.$zz['titre'].'">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
</div>
</div>

</br></br>
	  

									
									<div class="form-group">
<label for="lien" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<textarea class="form-control" rows="3" name="lien" id="lien">'.$zz['lien'].'</textarea></br>
</div>
</div>

</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
					<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="type" value="docstream">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>											
				  </div> </div> </div></div></div>';
				  $odd = ''.$odd1.''.$odd2.''.$odd3.'';
}
				return $odd;
					
					}	
				  
				  
				  function modfilmstream($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM lien_streaming WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			
$Odd = ' <a data-toggle="modal" data-target="#'.$idep.'" href="#"  ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien de '.$zz['titre'].'</h4>
      </div>
      <div class="modal-body">
                    <div> 
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_liens_streaming_moderateur.php" method="POST">
           
<div class="form-group">
<label for="correspondance_films" class="col-lg-2 control-label">Id Fiche</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="correspondance_films" value="'.$zz['correspondance_films'].'">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
</div>
</div>

</br></br><div class="form-group">
<label for="titre" class="col-lg-2 control-label">Titre Fiche</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="titre" value="'.$zz['titre'].'">
</div>
</div>

</br></br>
	  

									
									<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<textarea class="form-control" rows="3" name="liens" id="liens">'.$zz['liens'].'</textarea></br>
</div>
</div>

</br></br>
 	  <div class="form-group">
                    <label for="qualite" class="col-lg-2 control-label">Qualité</label>
                    <div class="col-lg-10">
               <select class="form-control" name="qualite">
									  	  <option value="'.$zz['qualite'].'">'.$zz['qualite'].'</option>
									  	<option value="BDRIP">BDRIP</option>
										<option value="CAM">CAM</option>
										<option value="DVDRIP">DVDRIP</option>
										<option value="DVDSRC">DVD SCREEN</option>
										<option value="R5">R5</option>
										<option value="TS">TS</option>
										<option value="WEBRIP">WEBRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
										</select>
                      
                    </div>
                  </div>    
</br></br></br>
<div class="form-group">
<label for="Langue" class="col-lg-2 control-label">Langue</label>
<div class="col-lg-10">
<select class="form-control" id="langue" name="langue">
<option value="'.$zz['langue'].'">'.$zz['langue'].'</option>
											<option value="FR">FR (Langue francaise)</option>
											<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
											<option value="VO">VO (Version original)</option>
</select>
</div>
</div>
</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
															<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="type" value="filmstream">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>	
				   </div> </div> </div></div></div> ';
}
					return $Odd;
					
					}	

					
	function modfilm($idep,$titre_fiche,$titre2,$qualite,$langue,$liens,$niv,$type){
	if($niv >= '3')
{
$valeurtitre2 ='';
if($type =='telechargement'){
	$valeurtitre2= '<div class="control-group">
      <label class="control-label" style="width: 0px;text-align: left;" for="titre2">Plusieurs parti ?</label>
                                 <div class="controls">
<select class="btn dropdown-toggle" name="titre2"><option value="'.$titre2.'">'.$titre2.'</option>
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	';}
			
$Odd = ' <a data-toggle="modal" data-target="#'.$idep.'" href="#"  ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien de '.$titre_fiche.'</h4>
      </div>
      <div class="modal-body">
                    <div> 
<form name="insertion"  class="form-horizontal"  action="../scripts/modifier_liens_streaming_moderateur.php" method="POST">
<input type="hidden" class="form-control" name="id" value="'.$idep.'">
<div class="control-group">
  <label class="control-label" style="width: 0px;text-align: left;" for="titre">Titre</label>
<div class="controls">
<input type="text" class="span6" name="titre" value="'.$titre_fiche.'">
</div>
</div>
'.$valeurtitre2.'
<div class="control-group">
  <label class="control-label" style="width: 0px;text-align: left;" for="liens">Lien</label>
<div class="controls">
<textarea class="span6" rows="3" name="liens" id="liens">'.$liens.'</textarea></br>
</div>
</div>
<div class="control-group">
  <label class="control-label" style="width: 0px;text-align: left;" for="qualite">Qualité</label>
<div class="controls">
               <select class="btn dropdown-toggle" name="qualite">
									  	  <option value="'.$qualite.'">'.$qualite.'</option>
									  	<option value="BDRIP">BDRIP</option>
										<option value="CAM">CAM</option>
										<option value="DVDRIP">DVDRIP</option>
										<option value="DVDSRC">DVD SCREEN</option>
										<option value="R5">R5</option>
										<option value="TS">TS</option>
										<option value="WEBRIP">WEBRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
										</select>
                      
                    </div>
                  </div>    
<div class="control-group">
  <label class="control-label" style="width: 0px;text-align: left;" for="langue">Langue</label>
<div class="controls">
<select class="btn dropdown-toggle" id="langue" name="langue">
<option value="'.$langue.'">'.$langue.'</option>
<option value="FR">FR (Langue francaise)</option>
<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
<option value="VO">VO (Version original)</option>
</select>
</div>
</div>
 <input type="submit" class="btn btn-success" value="Modifier"> <a  href="../insert/deleteep.php?id='.$idep.'&type=film"  oneclick="return confirmDeleteep()" class="btn btn-danger" >Effacer</a>	
				   </form>
	   </div> </div> </div></div></div> ';
}
					return $Odd;
					
					}	
					

function modebookdl($idep,$ifiche,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM ebook_telechargement WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			
$Odd = ' <a data-toggle="modal" data-target="#modebookdl'.$idep.'" href="#"  ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="modebookdl'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien de '.$zz['titre'].'</h4>
      </div>
      <div class="modal-body">
                    <div> 
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_lien_ebook.php" method="POST">
           
<div class="form-group">
<label for="correspondance_films" class="col-lg-2 control-label">Id Fiche</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="correspondance_films" value="'.$ifiche.'">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
</div>
</div>
</br></br>
<div class="form-group">
<label for="titre2" class="col-lg-2 control-label">Plusieurs parti ?</label>
<div class="col-lg-10">  
<select class="form-control" name="titre2"><option value="'.$zz['titre2'].'">'.$zz['titre2'].'</option>
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	</br></br>
</br></br>
	  

									
						
</br></br>
	  

									
									<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<input class="form-control"  name="liens" id="liens" value="'.$zz['liens'].'"></br>
</div>
</div>
</br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse"  value="'.$zz['motdepasse'].'"></br>
</div>
</div>
</br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
															<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="type" value="ebookdl">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>	
				   </div> </div> </div></div></div> ';
}
					return $Odd;
					
					}
					
					
					function modjeuxdl($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM lien_jeux WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			
$Odd = ' <a data-toggle="modal" data-target="#modjeuxdl'.$idep.'" href="#"  ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="modjeuxdl'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien de '.$zz['titre'].'</h4>
      </div>
      <div class="modal-body">
                    <div> 
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_lien_jeux.php" method="POST">
           
<div class="form-group">
<label for="correspondance_jeux" class="col-lg-2 control-label">Id Fiche</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="correspondance_jeux" value="'.$zz['correspondance_jeux'].'">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
</div>
</div>
</br></br>
<div class="form-group">
<label for="titre2" class="col-lg-2 control-label">Plusieurs parti ?</label>
<div class="col-lg-10">  
<select class="form-control" name="titre2"><option value="'.$zz['titre2'].'">'.$zz['titre2'].'</option>
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	</br></br>
</br></br>
	  

									
						
</br></br>
	  

									
									<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<input class="form-control"  name="liens" id="liens" value="'.$zz['liens'].'"></br>
</div>
</div>
</br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse"  value="'.$zz['mdp'].'"></br>
</div>
</div>
</br></br>
</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
	<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="type" value="jeuxdl">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>	
				   </div> </div> </div></div></div> ';
}
					return $Odd;
					
					}	

					function modlogicieldl($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM lien_logiciel WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			
$Odd = ' <a data-toggle="modal" data-target="#modlogicieldl'.$idep.'" href="#"  ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="modlogicieldl'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien de '.$zz['titre'].'</h4>
      </div>
      <div class="modal-body">
                    <div> 
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_lien_logiciel.php" method="POST">
           
<div class="form-group">
<label for="correspondance_musique" class="col-lg-2 control-label">Id Fiche</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="correspondance_musique" value="'.$zz['correspondance_musique'].'">

					<input type="hidden" class="form-control" name="id" id="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
</div>
</div>
</br></br>
<div class="form-group">
<label for="titre2" class="col-lg-2 control-label">Plusieurs parti ?</label>
<div class="col-lg-10">  
<select class="form-control" name="titre2" id="titre2"><option value="'.$zz['titre2'].'">'.$zz['titre2'].'</option>
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	</br></br>
</br></br>
	  

									
						
</br></br>
	  

									
									<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<input class="form-control"  name="liens" id="liens" value="'.$zz['liens'].'"></br>
</div>
</div>
</br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse"  value="'.$zz['mdp'].'"></br>
</div>
</div>
</br></br>
</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="id" id="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
					<input type="hidden" class="form-control" name="type" value="logicieldl">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>	
				   </div> </div> </div></div></div> ';
}
					return $Odd;
					
					}			


function modfilmdl($idep,$niv) {
	if($niv >= '3')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM lien_telechargement WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	

			
$Odd = ' <a data-toggle="modal" data-target="#modfilmdl'.$idep.'" href="#"  ><span class="label label-info">Editer</span></a>

</button> 
<!-- Modal -->
<div class="modal fade" id="modfilmdl'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifier Lien de '.$zz['titre'].'</h4>
      </div>
      <div class="modal-body">
                    <div> 
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/modifier_liens_dl_moderateur.php" method="POST">
           
<div class="form-group">
<label for="correspondance_films" class="col-lg-2 control-label">Id Fiche</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="correspondance_films" value="'.$zz['correspondance_films'].'">
<input type="hidden" class="form-control" name="id" id="id" value="'.$zz['id'].'">
<input type="hidden" class="form-control" name="hebergeur" value="'.$zz['hebergeur'].'">
</div>
</div>
</br></br><div class="form-group">
<label for="titre" class="col-lg-2 control-label">Titre Fiche</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
</div>
</div>
</br></br>
<div class="form-group">
<label for="titre2" class="col-lg-2 control-label">Plusieurs parti ?</label>
<div class="col-lg-10">  
<select class="form-control" name="titre2"><option value="'.$zz['titre2'].'">'.$zz['titre2'].'</option>
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	</br></br>
</br></br>
	  

									
						
</br></br>
	  

									
									<div class="form-group">
<label for="lien" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<input class="form-control"  name="liens" id="liens" value="'.$zz['liens'].'"></br>
</div>
</div>
</br></br>
 	  <div class="form-group">
                    <label for="qualite" class="col-lg-2 control-label">Qualité</label>
                    <div class="col-lg-10">
               <select class="form-control" name="qualite">
									  <option value="'.$zz['qualite'].'">'.$zz['qualite'].'</option>
									  	<option value="BDRIP">BDRIP</option>
										<option value="CAM">CAM</option>
										<option value="DVDRIP">DVDRIP</option>
										<option value="DVDSRC">DVD SCREEN</option>
										<option value="R5">R5</option>
										<option value="TS">TS</option>
										<option value="WEBRIP">WEBRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
										</select>
                      
                    </div>
                  </div>    
</br></br>
<div class="form-group">
<label for="Langue" class="col-lg-2 control-label">Langue</label>
<div class="col-lg-10">
<select class="form-control" id="langue" name="langue">
<option value="'.$zz['langue'].'">'.$zz['langue'].'</option>
											<option value="FR">FR (Langue francaise)</option>
											<option value="VOSTFR">VOSTFR (vo sous-titré francais)</option>
											<option value="VO">VO (Version original)</option>
</select>
</div>
</div>
</br></br>	
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse"  value="'.$zz['motdepasse'].'"></br>
</div>
</div>
</br></br>
</br></br></br>
 <input type="submit" class="btn btn-success" value="Modifier"></form>
															<form name="insertion" action="'.$nom_de_domaine.'/insert/deleteep.php" method="POST" onsubmit="return confirmDeleteep()">
					<input type="hidden" class="form-control" name="titre" value="'.$zz['titre'].'">	
					<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
					<input type="hidden" class="form-control" name="type" value="filmdl">
 <input type="submit" class="btn btn-danger" value="Effacer"></form>	
				   </div> </div> </div></div></div> ';
}
					return $Odd;
					
					}			



					function ajfilmstream($idep,$niv,$qualite,$langage) {
	if($niv >= '2')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM fiche WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	
if(!empty($qualite)){
	$optionqualite='<option value="'.$qualite.'">'.$qualite.'</option>';
}
if(!empty($langage)){if($langage =='FR'){$checked1='checked=1';}elseif($langage =='VOSTFR'){$checked2='checked=1';}elseif($langage =='VO'){$checked3='checked=1';}}
			
$oddfilmstream ='<a data-toggle="modal" data-target="#alinkstreamfilm'.$idep.'" href="#"  ><span class="label label-info">Ajouter un Iframe</span></a>


<!-- Modal -->
<div class="modal fade" id="alinkstreamfilm'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="height: 600px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Ajouter un Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					
					<center><span style="color: #00ff00;">liste des hebergeurs autorisé </span><a href="'.$nom_de_domaine.'/heb_autoriser.php" target="_blank"><span style="color: red;">Cliquez ici</span></a></center></h4>
								
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/inserer-fiche.php?id=lienstream" method="POST">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">

<input type="hidden" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
<div class="form-group">
                    <label for="qualite" class="col-lg-2 control-label">Qualité</label>
                    <div class="col-lg-10">
               <select class=\'form-control\' name="qualite" id="qualite">
			   '.$optionqualite.'
									  	<option value="0">Séléctionne qualité</option>
									  		<option value="BDRIP">BDRIP</option>
												<option value="CAM">CAM</option>
										<option value="DVDRIP">DVDRIP</option>
											<option value="DVDSRC">DVD SCREEN</option>
										<option value="R5">R5</option>
										<option value="TS">TS</option>
										<option value="WEBRIP">WEBRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
										</select>
                      
                    </div>
                  </div>   
				  <div class="form-group">
                    <label for="langue" class="col-lg-2 control-label">langue</label>
                    <div class="col-lg-10">
               <a href="javascript:langue(\'1\');" onClick="cacherbb();"><input type="radio" name="langue" id="langue1" value="FR" onClick="cacherbb();" '.$checked1.'>FR (Langue francaise) </a></p>
<a href="javascript:langue(\'2\');"  onClick="afficherbb();"><input type="radio" name="langue" id="langue2" value="VOSTFR" '.$checked2.'>VOSTFR (vo sous-titré francais)</a></p>
<a href="javascript:langue(\'3\');" onClick="cacherbb();"><input type="radio" name="langue" id="langue3" value="VO" onClick="cacherbb();" '.$checked3.'>VO (Version original)</a></br></br>
                      <p id="champ_trad">
type sous titre ? ..<br/>
    <input type="radio" name="trad" id="trad1" value="1"/><a href="javascript:cocher2(\'1\');">Google trad </a><br/>
   <input type="radio" name="trad" id="trad2" value="2"/><a href="javascript:cocher2(\'2\');">Fastsub</a><br/>
	 <input type="radio" name="trad"  id="trad3" value="3"/><a href="javascript:cocher2(\'3\');">TeamSub</a></br>
</p>
                    </div>
                  </div>    
	  

									
									<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
<textarea class="form-control" rows="3" name="liens" id="liens"></textarea></br>
</div>
</div>

</br></br></br>
<div class="col-lg-10 col-lg-offset-2">
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
 </form></br></br></br>
															
				  </div> </div> </div></div></div>	<script>
							function langue(id)
							{
								document.getElementById(\'langue\'+id).checked=1;
							}
						</script><script>
							function cocher2(id)
							{
								document.getElementById(\'trad\'+id).checked=1;
							}
						</script>	<script type="text/javascript">
document.getElementById("champ_trad").style.display = "none";
 
function afficherbb()
{
    document.getElementById("champ_trad").style.display = "block";
}
 
function cacherbb()
{
    document.getElementById("champ_trad").style.display = "none";
}
</script>';
					}
					return $oddfilmstream;
					}	
					
					
					
					function ajfilmdl($idep,$niv,$qualite,$langage) {
	if($niv >= '2')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM fiche WHERE id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	
if(!empty($qualite)){
	$optionqualite='<option value="'.$qualite.'">'.$qualite.'</option>';
}
if(!empty($langage)){if($langage =='FR'){$checked1='checked=1';}elseif($langage =='VOSTFR'){$checked2='checked=1';}elseif($langage =='VO'){$checked3='checked=1';}}
		
$oddaaaa=  ' <a data-toggle="modal" data-target="#alinkdl'.$idep.'" href="#"  ><span class="label label-info">Ajouter un lien</span></a>


<!-- Modal -->
<div class="modal fade" id="alinkdl'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Ajouter un Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					
					<center><span style="color: #00ff00;">liste des hebergeurs autorisé </span><a href="'.$nom_de_domaine.'/heb_autoriser.php" target="_blank"><span style="color: red;">Cliquez ici</span></a></center></h4>
								
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/inserer-fiche.php?id=liendl" method="POST">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
<input type="hidden" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
</br></br>
 	  <div class="form-group">
                    <label for="qualite" class="col-lg-2 control-label">Qualité'.$qualite.'</label>
                    <div class="col-lg-10">
               <select class="form-control" name="qualite">
			   '.$optionqualite.'
									  	<option value="0">Séléctionne qualité</option>
									  	<option value="BDRIP">BDRIP</option>
										<option value="CAM">CAM</option>
										<option value="DVDRIP">DVDRIP</option>
										<option value="DVDSRC">DVD SCREEN</option>
										<option value="R5">R5</option>
										<option value="TS">TS</option>
										<option value="WEBRIP">WEBRIP</option>
										<option value="720P">720P</option>
										<option value="1080P">1080P</option>
										</select>
                      
                    </div>
                  </div>    
</br></br>
 <div class="form-group">
                    <label for="langue" class="col-lg-2 control-label">langue</label>
                    <div class="col-lg-10">
               <a href="javascript:languedl(\'4\');" onClick="cacherbb2();"><input type="radio" name="langue" id="langue4" value="FR" onClick="cacherbb2();" '.$checked1.'>FR (Langue francaise) </a></p>
<a href="javascript:languedl(\'5\');"  onClick="afficherbb2();"><input type="radio" name="langue" id="langue5" value="VOSTFR" '.$checked2.'>VOSTFR (vo sous-titré francais)</a></p>
<a href="javascript:languedl(\'6\');" onClick="cacherbb2();"><input type="radio" name="langue" id="langue6" value="VO" onClick="cacherbb2();"'.$checked3.'>VO (Version original)</a></br></br>
                      <p id="champ_trad2">
type sous titre ? ..<br/>
    <input type="radio" name="trad" id="trad4" value="4"/><a href="javascript:cocher44(\'4\');">Google trad </a><br/>
   <input type="radio" name="trad" id="trad5" value="5"/><a href="javascript:cocher44(\'5\');">Fastsub</a><br/>
	 <input type="radio" name="trad"  id="trad6" value="6"/><a href="javascript:cocher44(\'6\');">TeamSub</a></br>
</p>
                    </div>
                  </div>    
	  
</br></br>	</br>	
<div class="form-group">
<label for="titre2" class="col-lg-2 control-label">Plusieurs parti ?</label>
<div class="col-lg-10">  
<select class="form-control" name="titre2">
									  <OPTION SELECTED VALUE="">-Selectionnez une partie
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	</br></br>
<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
'.$attention.'
<span style="color: #00ff00;">Protégez vos liens </span><a href="//protect-link.biz/" target="_blank"><span style="color: red;">Cliquez ici</span></a></br>
<input type="text" class="form-control" name="liens" id="liens" ></br>
</div>	
	</div>
</br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse" ></br>
</div>
</div>
</br></br>	
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
<input type="hidden" class="form-control" name="titre" value="'.$zz['titre'].'">	
</div>
</div>

</br><div class="form-group">
<div class="col-lg-10"> 
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div> </div></br></br>
 </form></br></br></br></br></br>	</br>	
															
				  </div> </div> </div></div></div><script type="text/javascript">
document.getElementById("champ_trad2").style.display = "none";
 
function afficherbb2()
{
    document.getElementById("champ_trad2").style.display = "block";
}
 
function cacherbb2()
{
    document.getElementById("champ_trad2").style.display = "none";
}
</script><script>
							function languedl(id)
							{
								document.getElementById(\'langue\'+id).checked=1;
							}
						</script><script>
							function cocher44(id)
							{
								document.getElementById(\'trad\'+id).checked=1;
							}
						</script>	';
					}
					return $oddaaaa;
					}
				  	function ajebookdl($idep,$niv) {
	if($niv >= '2')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM fiche WHERE categorie='ebook' and id = ".$idep ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	


echo ' <a data-toggle="modal" data-target="#a'.$idep.'" href="#"  ><span class="label label-info">Ajouter un lien</span></a>


<!-- Modal -->
<div class="modal fade" id="a'.$idep.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Ajouter un Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					
					<center><span style="color: #00ff00;">liste des hebergeurs autorisé </span><a href="'.$nom_de_domaine.'/heb_autoriser.php" target="_blank"><span style="color: red;">Cliquez ici</span></a></center></h4>
						Ajout de lien sur '.$zz['titre'].'</br>		
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/inserer-fiche.php?id=lienebookdl" method="POST">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
<input type="hidden" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
</br></br></br></br>
<div class="form-group">
<label for="titre2" class="col-lg-2 control-label">Plusieurs parti ?</label>
<div class="col-lg-10">  
<select class="form-control" name="titre2">
									  <OPTION SELECTED VALUE="">-Selectionnez une partie
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	</br></br>
<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
'.$attention.'
<span style="color: #00ff00;">Protégez vos liens </span><a href="//protect-link.biz/" target="_blank"><span style="color: red;">Cliquez ici</span></a></br>
<input type="text" class="form-control" name="liens" id="liens" ></br>
</div>	
	</div></br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse" ></br>
</div>
</div>
</br></br>
</br><div class="form-group">
<div class="col-lg-10"> 
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div> </div></br></br>
 </form></br></br></br>
															
				  </div> </div> </div></div></div>';
					}}	




					function ajjeuxdl($idep,$niv) {
	if($niv >= '2')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM  `fiche` WHERE  `categorie` =  'jeux' AND  `titre` LIKE  '".addslashes($idep)."'");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	


echo ' <a data-toggle="modal" data-target="#ajeux" href="#"  ><span class="label label-info">Ajouter un lien</span></a>


<!-- Modal -->
<div class="modal fade" id="ajeux" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Ajouter un Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					
					<center><span style="color: #00ff00;">liste des hebergeurs autorisé </span><a href="'.$nom_de_domaine.'/heb_autoriser.php" target="_blank"><span style="color: red;">Cliquez ici</span></a></center></h4>
								
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/inserer-fiche.php?id=lienjeuxdl" method="POST">
<input type="hidden" class="form-control" name="id" value="'.$zz['id'].'">
<input type="hidden" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
</br></br></br></br>
<div class="form-group">
<label for="titre2" class="col-lg-2 control-label">Plusieurs parti ?</label>
<div class="col-lg-10">  
<select class="form-control" name="titre2">
									  <OPTION SELECTED VALUE="">-Selectionnez une partie
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	</br></br>
<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
'.$attention.'
<span style="color: #00ff00;">Protégez vos liens </span><a href="//protect-link.biz/" target="_blank"><span style="color: red;">Cliquez ici</span></a></br>
<input type="text" class="form-control" name="liens" id="liens" ></br>
</div>	
	</div></br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse" ></br>
</div>
</div>
</br></br>
</br><div class="form-group">
<div class="col-lg-10"> 
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div> </div></br></br>
 </form></br></br></br>
															
				  </div> </div> </div></div></div>';
					}}
				  			function ajlogicieldl($idep,$niv) {
	if($niv >= '2')
{
$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
$cnx = Utils::connexionbd();
				$infos               = $cnx->query("SELECT * FROM  `fiche` WHERE  `categorie` =  'logiciel' AND  `titre` LIKE  '".addslashes($idep)."'");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	


$variable =  ' <a data-toggle="modal" data-target="#alogiciel" href="#"  ><span class="label label-info">Ajouter un lien</span></a>


<!-- Modal -->
<div class="modal fade" id="alogiciel" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Ajouter un Lien</h4>
      </div>
      <div class="modal-body">
                    <div>
					
					<center><span style="color: #00ff00;">liste des hebergeurs autorisé </span><a href="'.$nom_de_domaine.'/heb_autoriser.php" target="_blank"><span style="color: red;">Cliquez ici</span></a></center></h4>
								
					<form name="insertion" action="'.$nom_de_domaine.'/scripts/inserer-fiche.php?id=lienlogiciel" method="POST">
<input type="hidden" class="form-control" name="id"  id="id" value="'.$zz['id'].'">
<input type="hidden" class="form-control" name="titre" id="titre" value="'.$zz['titre'].'">
</br></br></br></br>
<div class="form-group">
<label for="titre2" class="col-lg-2 control-label">Plusieurs parti ?</label>
<div class="col-lg-10">  
<select class="form-control" name="titre2" id="titre2">
									  <OPTION SELECTED VALUE="">-Selectionnez une partie
										<option value="Part 1">Partie 1</option>
										<option value="Part 2">Partie 2</option>
										<option value="Part 3">Partie 3</option>
										<option value="Part 4">Partie 4</option>
										<option value="Part 5">Partie 5</option>
										<option value="Part 6">Partie 6</option>
										<option value="Part 7">Partie 7</option>
										<option value="Part 8">Partie 8</option>
										<option value="Part 9">Partie 9</option>
										<option value="Part 10">Partie 10</option>
										<option value="Part 11">Partie 11</option>
										<option value="Part 12">Partie 12</option>
										<option value="Part 13">Partie 13</option>
										<option value="Part 14">Partie 14</option>
										<option value="Part 15">Partie 15</option>
										<option value="Part 16">Partie 16</option>
										<option value="Part 17">Partie 17</option>
										<option value="Part 18">Partie 18</option>
										<option value="Part 19">Partie 19</option>
										<option value="Part 20">Partie 20</option>
										</select>
</div>
</div>	</br></br>
<div class="form-group">
<label for="liens" class="col-lg-2 control-label">lien</label>
<div class="col-lg-10">
'.$attention.'
<span style="color: #00ff00;">Protégez vos liens </span><a href="//protect-link.biz/" target="_blank"><span style="color: red;">Cliquez ici</span></a></br>
<input type="text" class="form-control" name="liens" id="liens" ></br>
</div>	
	</div></br></br>		
			<div class="form-group">
<label for="motdepasse" class="col-lg-2 control-label">Mot de passe</label>
<div class="col-lg-10">
<input type="text" class="form-control" name="motdepasse" id="motdepasse" ></br>
</div>
</div>
</br></br>
</br><div class="form-group">
<div class="col-lg-10"> 
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div> </div></br></br>
 </form></br></br></br>
															
				  </div> </div> </div></div></div>';
					}else{$variable='';}
					return $variable;
					}
				  
				  
function _make_url_clickable_cb($matches) {
	$ret = '';
	$url = $matches[2];
 
	if ( empty($url) )
		return $matches[0];
	
	if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
		$ret = substr($url, -1);
		$url = substr($url, 0, strlen($url)-1);
	}
	return $matches[1] . "<a href=\"$url\" rel=\"nofollow\" target=\"_blank\"><span style='color: #ff0000;'><strong>Lien</strong></span></a>" . $ret;
}
 
function _make_web_ftp_clickable_cb($matches) {
	$ret = '';
	$dest = $matches[2];
	$dest = '//' . $dest;
 
	if ( empty($dest) )
		return $matches[0];
	
	if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
		$ret = substr($dest, -1);
		$dest = substr($dest, 0, strlen($dest)-1);
	}
	return $matches[1] . "<a href=\"$dest\" target=\"_blank\" ><span style='color: #ff0000;'><strong>Lien</strong></span></a>" . $ret;
}
 
function _make_email_clickable_cb($matches) {
	$email = $matches[2] . '@' . $matches[3];
	return $matches[1] . "<a href=\"mailto:$email\" target=\"_blank\">$email</a>";
}
 ////Fonction principale
function make_clickable($ret) {
	$ret = ' ' . $ret;
	//On verifie si c un ftp, email ou lien normal
	$ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret);
	$ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_web_ftp_clickable_cb', $ret);
	$ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret);
 
	
	$ret = preg_replace("#(<a( [^>]+?>
<p>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
	$ret = trim($ret);
	return $ret;
}
function get_ip() {
  if($_SERVER) {
if (preg_match( "/^([d]{1,3}).([d]{1,3}).([d]{1,3}).([d]{1,3})$/", getenv('HTTP_X_FORWARDED_FOR')))
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    elseif(!empty($_SERVER['HTTP_CLIENT_IP']))
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    else
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  else {
    if(getenv('HTTP_X_FORWARDED_FOR'))
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    elseif(getenv('HTTP_CLIENT_IP'))
      $ip = getenv('HTTP_CLIENT_IP');
    else
      $ip = getenv('REMOTE_ADDR');
  }
  return $ip;
} $ip = get_ip(); 

function detectmobile()
{
$bots_list=array(
"android"=>"android",
"mobile"=>"mobile",
"j2me"=>"j2me",
"opera mini"=>"opera mini",
"ipad"=>"ipad",
"smart-tv"=>"smart-tv",
"smart tv"=>"smart tv",
"smarttv"=>"smarttv",
"googletv"=>"googletv",
"appletv"=>"appletv",
"hbbtv"=>"hbbtv",
"pov_tv"=>"pov_tv",
"netcast.tv"=>"netcast.tv",
"iphone"=>"iphone"
/*Rajoute en dans le tableau au dessus*/
);
$regexp='/'.  implode("|", $bots_list).'/';
$ua= strtolower($_SERVER['HTTP_USER_AGENT']);
if(preg_match($regexp, $ua,$matches))
{
$bot=  array_search($matches[0], $bots_list);
return true;
}
else
{
return false;
}
}
function delfiche($id,$table,$type,$retour){
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	$del = '<a onclick="return confirmDeleteep()" href="'.$nom_de_domaine.'/insert/deleteep.php?id='.$id.'&tb='.$table.'&del='.$table.'&cat='.$retour.'&type='.$type.'" class="label label-warning">Effacer</a>';
	return $del;
}
function base64img($url){
	//$b64image = base64_encode(file_get_contents($url));
$image = file_get_contents($url);
if ($image !== false){
    return 'data:image/jpg;base64,'.base64_encode($image);

}}
function delallfiche($id,$table,$retour){
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	$del = '<a onclick="return confirmDeleteep()" href="'.$nom_de_domaine.'/insert/deleteep.php?id='.$id.'&tb='.$table.'&del='.$table.'&cat='.$retour.'&type=all"  class="label label-warning">Effacer</a>';
	return $del;
}
	function getnommembre($idms){
	$reqmm = mysql_query("SELECT pseudo FROM membre WHERE id = '".$idms."'");
	if (mysql_num_rows($reqmm)) {
		$rowmm = mysql_fetch_array($reqmm);
		$nommm = $rowmm['pseudo'];
	} else{
		$nommm = "Inconnu";
	}
	return $nommm;
}
function getidmmembre($pseudo){
	
	$cnx = Utils::connexionbd();
		$infos               = $cnx->query('SELECT * FROM membre WHERE pseudo = "' . $pseudo. '"');
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	
	
	
	if ($zz['id']) {
		$nommm = $zz['id'];
	} else{
			$nommm = $zz['id'];
	}
	return $nommm;
}
function getpseudommembre($id){
	
	$cnx = Utils::connexionbd();
		$infos               = $cnx->query('SELECT * FROM membre WHERE id = "'.(int)$id.'"');
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	
	
	
	if ($zz['pseudo']) {
		$nommm = $zz['pseudo'];
	} else{
			$nommm = $zz['pseudo'];
	}
	return $nommm;
}

function getnamecategorie($id){
	
	$cnx = Utils::connexionbd();
		$infos               = $cnx->query('SELECT * FROM blog_categorie WHERE id ='.(int)$id.'');
$zz  = $infos->fetch(PDO::FETCH_ASSOC);	
$nommm = $zz['name'];
	
	return $nommm;
}
$Search = array("\\n", "\\r", "\n", "\r", " ", " ", "&amp;", " ", " ", " ", " ", " ", " ", " ", " ", "à", "á", "â", "à", "À", "ç", "ç", "Ç", "é", "è", "ê", "ë", "É", "È", "é", "è", "ê", "í", "ï", "ï", "î", "ñ", "ô", "ò", "ö", "ô", "ó", "Ó", "ù", "û", ";");
$Replace = array("-","-","-","-", "-", "", "-", "-", "-", "-", "-", "-", "-", "-", "-", "a", "a", "a", "a", "A", "c", "c", "C", "e", "e", "e", "e", "E", "E", "e", "e", "e", "i", "i", "i", "n", "o", "o", "o", "o", "o", "O", "u", "u", "\;");
//SERIES
function serierewrite($id, $titre, $saison ,$episode, $idserie,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );
$serieurl = "//".$nom_de_domaine."/series-streaming-".$stitre."-".$idserie."-".$id."-saison-".$saison."-episode-".$episode."-".$langue.".html";
return $serieurl;
} else {
return  "//".$nom_de_domaine."/series_infos_streaming.php?idseries=".$idserie."&ep=".$id;
}
}


//series episode dl
function seriesdlrewrite($id, $titre, $saison ,$episode, $idserie,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );
$seriedlurl = "//".$nom_de_domaine."/series-telechargement-".$stitre."-".$idserie."-".$id."-saison-".$saison."-episode-".$episode."-".$langue.".html";
return $seriedlurl;
} else {
return  "//".$nom_de_domaine."/series_infos_telechargement.php?idseries=".$idserie."&ep=".$id;
}
}
//SERIES
function seriesrewrite($idseries, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$serie2url = "//".$nom_de_domaine."/series-streaming-".$idseries."-".$stitre.".html";
return $serie2url;
} else {
return  "//".$nom_de_domaine."/series_infos_streaming.php?idseries=".$idseries."";
}
}

//blogarticleunique
function postrewriteunique($idseries, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$serie2url = "//".$nom_de_domaine."/blog/article-".$idseries."-".$stitre.".html";
return $serie2url;
} else {
return  "//".$nom_de_domaine."/blog/article.php?id=".$idseries."";
}
}
//blogcategorieunique
function catblogrewriteunique($idseries, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$serie2url = "//".$nom_de_domaine."/blog/categorie-".$idseries."-".$stitre.".html";
return $serie2url;
} else {
return  "//".$nom_de_domaine."/blog/categorie.php?id=".$idseries."";
}
}

function seriesrewriteunique($idseries, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$serie2url = "//".$nom_de_domaine."/series-".$idseries."-".$stitre.".html";
return $serie2url;
} else {
return  "//".$nom_de_domaine."/series_infos_streaming2.php?idseries=".$idseries."";
}
}

//mangafichestream
function mangasrewriteunique($idseries, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$serie2url = "//".$nom_de_domaine."/manga-".$idseries."-".$stitre.".html";
return $serie2url;
} else {
return  "//".$nom_de_domaine."/mangas_infos_streaming2.php?idmangas=".$idseries."";
}
}
//mangas dl fiche
function mangasfidlrewrite($idseries, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$mangas2url = "//".$nom_de_domaine."/mangas-telechargement-".$idseries."-".$stitre.".html";
return $mangas2url;
} else {
return  "//".$nom_de_domaine."/mangas_infos_telechargement.php?idmangas=".$idseries."";
}
}//jeux dl fiche
function jeuxfidlrewrite($idseries, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$mangas2url = "//".$nom_de_domaine."/jeux-telechargement-".$idseries."-".$stitre.".html";
return $mangas2url;
} else {
return  "//".$nom_de_domaine."/jeux_infos_telechargement.php?idjeux=".$idseries."";
}
}
//documentaire dl fiche
function docufidlrewrite($iddocudl, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$docu2url = "//".$nom_de_domaine."/documentaire-telechargement-".$iddocudl."-".$stitre.".html";
return $docu2url;
} else {
return  "//".$nom_de_domaine."/documentaire_infos_telechargement.php?iddocumentaire=".$iddocudl."";
}
}
//DOCUMENTAIRES dl
function docudlrewrite($id, $titre, $saison ,$episode, $iddocudl,$Langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$Langue = trim( strtolower($Langue), "-" );
$docudlurl = "//".$nom_de_domaine."/docs-telechargement-".$stitre."-".$iddocudl."-".$id."-saison-".$saison."-episode-".$episode."-".$Langue.".html";
return $docudlurl;
} else {
return  "//".$nom_de_domaine."/documentaire_infos_telechargement.php?iddocumentaire=".$iddocudl."&ep=".$id;
}
}
//SERIES dl
function seriesfidlrewrite($idseries, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$seriedl2url = "//".$nom_de_domaine."/series-telechargement-".$idseries."-".$stitre.".html";
return $seriedl2url;
} else {
return  "//".$nom_de_domaine."/series_infos_telechargement.php?idseries=".$idseries."";
}
}
//MANGAS dl
function mangadlrewrite($id, $titre, $saison ,$episode, $idmanga,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );
$mangaurl = "//".$nom_de_domaine."/mangas-telechargement-".$stitre."-".$idmanga."-".$id."-saison-".$saison."-episode-".$episode."-".$langue.".html";
return $mangaurl;
} else {
return  "//".$nom_de_domaine."/mangas_infos_telechargement.php?idmangas=".$idmanga."&ep=".$id;
}
}
//MANGAS
function mangarewrite($id, $titre, $saison ,$episode, $idmanga,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );
$serieurl = "//".$nom_de_domaine."/mangas-streaming-".$stitre."-".$idmanga."-".$id."-saison-".$saison."-episode-".$episode."-".$langue.".html";
return $serieurl;
} else {
return  "//".$nom_de_domaine."/mangas_infos_streaming.php?idmangas=".$idmanga."&ep=".$id;
}
} 
//DOCUMENTAIRES
function docurewrite($id, $titre, $saison ,$episode, $iddocu,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );
$docuurl = "//".$nom_de_domaine."/docs-streaming-".$stitre."-".$iddocu."-".$id."-saison-".$saison."-episode-".$episode."-".$langue.".html";
return $docuurl;
} else {
return  "//".$nom_de_domaine."/documentaire_infos_streaming.php?idseries=".$iddocu."&ep=".$id;
}
}
//FILMS
function filmrewrite($id, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
 $filmurl = "//".$nom_de_domaine."/films-streaming-".$id."-".$stitre.".html";

return $filmurl;
} else {
return  "//".$nom_de_domaine."/films_infos_streaming.php?idfilms=".$id;
}
}

	//FILMSunique
function filmrewriteunique($id, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
 $filmurl = "//".$nom_de_domaine."/film-".$id."-".$stitre.".html";

return $filmurl;
} else {
return  "//".$nom_de_domaine."/films_infos_streaming.php?idfilms=".$id;
}
}

//chaine
function chaine($id, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
 $filmurl = "//".$nom_de_domaine."/chaine-streaming-".$id."-".$stitre.".html";

return $filmurl;
} else {
return  "//".$nom_de_domaine."/chaine_infos_streaming.php?idchaine=".$id;
}
}
//radio
function radio($id, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
 $filmurl = "//".$nom_de_domaine."/radio-streaming-".$id."-".$stitre.".html";

return $filmurl;
} else {
return  "//".$nom_de_domaine."/chaine_infos_streaming.php?idchaine=".$id;
}
}

//FILMS dl
function filmdlrewrite($id, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$filmurl = "//".$nom_de_domaine."/films-telechargement-".$id."-".$stitre.".html";
return $filmurl;
} else {
return  "//".$nom_de_domaine."/films_infos_telechargement.php?idfilms=".$id;
}
}
//documentaire
function docu2rewrite($id6, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$docu2url = "//".$nom_de_domaine."/documentaire-streaming-".$id6."-".$stitre.".html";
return $docu2url;
} else {
return  "//".$nom_de_domaine."/documentaire_infos_streaming.php?iddocumentaire=".$id6."";
}
}//documentaire
function docu2rewriteunique($id6, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$docu2url = "//".$nom_de_domaine."/documentaires-".$id6."-".$stitre.".html";
return $docu2url;
} else {
return  "//".$nom_de_domaine."/documentaire_infos_streaming2.php?iddocumentaire=".$id6."";
}
}
//jeuxdl
function jeuxdl($id6, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$docu2url = "//".$nom_de_domaine."/jeux-dl-".$id6."-".$stitre.".html";
return $docu2url;
} else {
return  "//".$nom_de_domaine."/jeux_infos_telechargement.php?idjeux=".$id6."";
}
}
//ebookdl
function ebookdl($id6, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$docu2url = "//".$nom_de_domaine."/ebook-dl-".$id6."-".$stitre.".html";
return $docu2url;
} else {
return  "//".$nom_de_domaine."/ebook_infos_telechargement.php?idebook=".$id6."";
}
}
//logicieldl
function logicieldl($id6, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$docu2url = "//".$nom_de_domaine."/logiciel-dl-".$id6."-".$stitre.".html";
return $docu2url;
} else {
return  "//".$nom_de_domaine."/logiciels_infos_telechargement.php?idlogiciel=".$id6."";
}
}
//mangas
function manga2rewrite($id8, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$manga2url = "//".$nom_de_domaine."/mangas-streaming-".$id8."-".$stitre.".html";
return $manga2url;
} else {
return  "//".$nom_de_domaine."/mangas_infos_streaming.php?idmangas=".$id6."";
}
}
//mangasunique
function manga2rewriteunique($id8, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$manga2url = "//".$nom_de_domaine."/mangas-".$id8."-".$stitre.".html";
return $manga2url;
} else {
return  "//".$nom_de_domaine."/mangas_infos_streaming.php?idmangas=".$id6."";
}
}
//MUSIQUE
function musiquerewrite($id, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$musiqueurl = "//".$nom_de_domaine."/musique-streaming-".$id."-".$stitre.".html";
return  $musiqueurl;
} else {
return  "//".$nom_de_domaine."/musique_infos_streaming.php?idmusique=".$id;
}
}//MUSIQUE
function musiquedlrewrite($id, $titre) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$musiqueurl = "//".$nom_de_domaine."/musique-telechargement-".$id."-".$stitre.".html";
return  $musiqueurl;
} else {
return  "//".$nom_de_domaine."/musique_infos_telechargement.php?idmusique=".$id;
}
}
//SERIES
function seriere2dlwrite($id, $titre, $saison ,$episode, $idserie,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );


   $serieurl = "//".$nom_de_domaine."/series-telechargement-".$stitre."-".$idserie."-".$id."-saison-".$saison."-episode-".$episode."-".$langue.".html";

return $serieurl;
} else {
return  "//".$nom_de_domaine."/series_infos_telechargement.php?idseries=".$idserie."&ep=".$id;
}
}
		 function addvues($table,$id,$ip){
			global $cnx;
			 		$requete22 = $cnx->query("SELECT * FROM ".$table." WHERE id =".$id);
$result6 = $requete22->fetch();
 if(!preg_match('`'.$ip.'`i', $result6['ipvus'])){
	  $newip = $result6['ipvus'].' | '.$ip;
		  $cnx->exec("UPDATE ".$table." SET vue = vue + 1, ipvus = '".$newip."' WHERE id = '".$id."'");
 }
	     }
		 
function getvues($cnx,$table,$id){
	$getvues2 = $cnx->query("SELECT vue FROM ".$table." WHERE id = '".$id."'");
	foreach($getvues2 as $getvues) 
	{
	$nbvue = $getvues['vue'];
	}
	return $nbvue;
	}
	
	function tags($mot,$lien){
		global $cnx;
		$keyword0 = explode(',',$mot);
foreach($keyword0 as $keywordsu){ 
$keywords = ''.$keywordsu.'';
	 if (strlen($keywordsu) >= '3') {
		 $keywordsu= str_replace("l'", "", $keywordsu);
		 $keywordsu= str_replace("'", "", $keywordsu);
		 $keywordsu= str_replace("...", "", $keywordsu);
$metatag .=  '<a href="'.stripslashes(trim($lien)).'" rel="tag" >'.stripslashes(trim($keywordsu)).'</a>, ';
	 }}
	 	return $metatag;
		}
	
function extstres22($content, $start, $end)
{
		if ((($content and $start) and $end))
		{
				$r = explode($start, $content);
				if (isset($r[1]))
				{
						$r = explode($end, $r[1]);
						return $r[0];
				}
				return '';
		}
}
	function controlparental($genre,$actif,$niv){
		if($actif=='1' && $niv >='0'){
		$genres = explode(',',$genre);
foreach($genres as $gender){
	if(preg_match('`action|epouvante|horreur|thriller|erotique`i', $gender)) { 
$controle= true;

}
	
	}
		if($controle == true){
	echo '<div class="alert alert-dismissible alert-warning">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4>Attention!</h4>
                <p>le contrôle parental à était activé ce type de video vous est interdit pour avoir accés <a href="//'.$nom_de_domaine.'/account.php" class="alert-link">Veuillez désactiver le contrôle parental dans votre compte</a>.</p>
              </div>';
	echo "</div></div>";
	include('footer.php');
	exit();
}
		}
		
		return $controle;
		}
		function decode_numeric_entities($s){
   

		if(preg_match('`eacute`i', $s)){
        
           
   $result = html_entity_decode($s,ENT_COMPAT, "ISO8859-1"); 
    }else{
		 $result = $s;
	}
    return $result;
}
	function metafiche($titre,$description,$keyword,$image,$rss,$datepublish,$datemodif,$next,$prev){
		global $nametwitter;
	if($image ==''){
			$imagemeta = ''.prothttp().'//'.$_SERVER['HTTP_HOST'].'/favicon.png';
		}else{
			$imagemeta = ''.prothttp().'//cdnscreenshot.xyz/retail.php?src='.$image.'&h=240&w=215';
		}
			$keyword0 = explode(' ',$description);
				$metatag ='';
foreach($keyword0 as $keywordsu){ 
$keywords = ''.$keywordsu.'';
	 if (strlen($keywordsu) >= '3') {
		 $keywordsu= str_replace("l'", "", $keywordsu);
		 $keywordsu= str_replace("'", "", $keywordsu);
		 $keywordsu= str_replace("...", "", $keywordsu);
$metatag .=  '<meta property="article:tag" content="'.strip_tags(stripslashes(trim($keywordsu))).'" />
';
	 }}
	 $nextcanonical ='';
	 	$prevcanonical ='';
	 if(!empty($next)){
		$nextcanonical ='
		<link rel="next" href="'.$next.'">
		';
	 }else{
		 		$nextcanonical ='';
	 }
	 if(!empty($prev)){
		$prevcanonical ='
		<link rel="prev" href="'.$prev.'">
		';
	 	 }else{
		 		$prevcanonical ='
				';
	 }
		 $description= str_replace('"', '', $description);
		 $keyword= str_replace(', ,', ', ', $keyword);
		 $keyword= str_replace('  ', ' ', $keyword);
		 $keyword= str_replace('...', '', $keyword);
		 $datetimetamp2 = strtotime($datemodif);
	$recupdate = date('c', $datetimetamp2);
	
		 $datetimetamp = strtotime($datepublish);
	$publishdate = date('c', $datetimetamp);
	$metadescrip =  substr(''.strip_tags(stripslashes(trim($description))).'', 0,315);
	$metatitle =  substr(''.ucfirst($titre).'', 0, 165);
$metaresul= '<title itemprop="name">'.$metatitle.'</title>
<meta http-equiv="content-language" content="fr" /> 
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<META NAME="DESCRIPTION" itemprop="description"  CONTENT="'.$metadescrip.'" />
<META NAME="keywords" itemprop="keywords"  CONTENT="'.ucfirst($titre).', '.$keyword.', serie streaming, serie en streaming, streaming tv, serie streaming, serie tv streaming, serie tv 2016, serie '.date('Y').', serie 2015, vk streaming serie, openload serie, streaming vk, full streaming serie, full serie, full streaming, full stream, serie full stream , streaming hd, streaming 720p, streaming sans limite, streaming illimité, stream no limite, Serie en Streaming HD 2015, Series en Streaming 2015, Serie en Streaming Netflix, Netflix streaming, voirfilms, filmsregarder, Netflix, google, youtube, les meilleurs series, meilleur site de streaming, top site de streaming" />
<meta name="robots" content="all"/>
<link rel="canonical" href="'.prothttp().'//'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'" />'.trim($prevcanonical).''.trim($nextcanonical).'
<meta property="article:published_time" content="'.$publishdate.'" />
<meta property="article:modified_time" content="'.$recupdate.'" />
<meta name="subject" content="'.ucfirst($titre).'"/>
'.trim($metatag).'
<meta property="article:tag" content="streaming" />
<meta property="article:tag" content="telechargement" />
<meta property="fb:profile_id" content="100004374937668" />
<meta property="fb:app_id" content="966242223397117"> 
<meta property="og:url" content="'.prothttp().'//'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'"> 
<meta property="og:title" content="'.$metatitle.'">
<meta property="og:description" content="'.$metadescrip.'"> 
<meta property="og:image" content="'.$imagemeta.'">
<meta property="og:site_name" content="'.namesite().'" />
<meta property="og:type" content="website" />
<meta name="twitter:card"           content="summary_large_image">
<meta name="twitter:site"           content="@'.$nametwitter.'">
<meta name="twitter:title"          content="'.$metatitle.'">
<meta name="twitter:description"    content="'.$metadescrip.'">
<meta name="twitter:image:src"      content="'.$imagemeta.'">
<link  rel = "image_src"  href = "'.$imagemeta.'" />'.$rss.'
<meta name="robots" content="all, index, follow" />
<meta name="author" content="'.namesite().'"/>
<meta name="copyright" content="'.namesite().'"/>
<meta name="Content-language" content="French"/>
<meta http-equiv="Content-Language" content="fr"/>
<meta name="rating" content="general"/>';
return trim($metaresul);
	}
	
		
	function meta($cnx,$table,$id){
		function extstres($content, $start, $end)
{
		if ((($content and $start) and $end))
		{
				$r = explode($start, $content);
				if (isset($r[1]))
				{
						$r = explode($end, $r[1]);
						return $r[0];
				}
				return '';
		}
}
	if(!empty($id)){
		$cnx = Utils::connexionbd();
		$infos               = $cnx->query("SELECT * FROM fiche WHERE  id = ".$id ."");
$zz  = $infos->fetch(PDO::FETCH_ASSOC);
		 $datetimetamp = strtotime($zz['post_date_gmt']);
	$recupdate = date('c', $datetimetamp);	
		 $datetimetamp2 = strtotime($zz['post_date_gmt2']);
	$recupdate2 = date('c', $datetimetamp);	
$publish = '<meta property="article:published_time" content="'.$recupdate.'" />
<meta property="article:modified_time" content="'.$recupdate2.'" />';	
	}
		
		if(preg_match('`-dl-`i', $_SERVER['REQUEST_URI'])){
			$cat = 'en telechargement illimité';
			$typekey = 'telechargement, illimité';
		}else{
			$cat = 'en streaming et telechargement illimité';
			$typekey = 'streaming, telechargement, illimité';
		}
		if(preg_match('`saison|episode`i', $_SERVER['REQUEST_URI'])){
			$sainum=extstres($_SERVER['REQUEST_URI'], 'saison-', '-');
			$type=extstres($_SERVER['REQUEST_URI'], '/', '-');
			$epinum=extstres($_SERVER['REQUEST_URI'], 'episode-', '-');
			$lanlan=extstres($_SERVER['REQUEST_URI'], ''.$epinum.'-', '.html');
			$episode = 'saison '.$sainum.' episode '.(int)$epinum.' '.$lanlan.' ';
	}
	$meta = $cnx->query("SELECT * FROM ".$table." WHERE id = '".$id."'");
    $resulmeta                = $meta->fetch(PDO::FETCH_ASSOC);
	$str =strip_tags($resulmeta['description']);
	
	$str = str_replace('"', '', $str); 
	$description = preg_replace("/(\r\n|\n|\r)/", " ", $str);
		$keyword3 = str_replace(",", " ", $description);
		$keyword3 = str_replace(", ", " ", $description);
		$keyword3 = str_replace("  ", " ", $description);
		$keyword3 = str_replace(" ", ",", $description);
	$description = substr($description,0, 450);
	$keyword0 = explode(',',$keyword3);
foreach($keyword0 as $keywordsu){ 
$keywords = ' '.$keywordsu.' ';
	 if (strlen($keywordsu) >= '5') {
		 $keyword .=  ' '.$keywordsu.' ';
	 }

}

foreach($keyword0 as $keywordsuz){ 
$keywords = ''.$keywordsuz.'';
	 if (strlen($keywordsuz) >= '5') {
		 $keywordsuz= str_replace("l'", "", $keywordsuz);
		 $keywordsuz= str_replace("'", "", $keywordsuz);
		 $keywordsuz= str_replace("...", "", $keywordsuz);
$metatag .=  '<meta property="article:tag" content="'.strip_tags(stripslashes(trim($keywordsuz))).'" />
';
	 }}
	
		$keyword = str_replace("  ", " ", $keyword);
	$keyword = str_replace("&", "", $keyword);
	$keyword = str_replace(")", "", $keyword);
	$keyword = str_replace("(", "", $keyword);
	$keyword = str_replace(" ", ", ", $keyword); 
	$keyword = str_replace('"', '', $keyword); 
	$keytitre = $resulmeta['titre'];
	$keytitre = str_replace("&", "", $keytitre);
	$keytitre = str_replace(")", " ", $keytitre);
	$keytitre = str_replace("(", " ", $keytitre);
	$keytitre = str_replace(":", " ", $keytitre);
	$keytitre = str_replace('"', ' ', $keytitre); 
	$keytitre = str_replace('.', '', $keytitre); 
	    $keytitre = str_replace("  ", " ", $keytitre);
	$keytitre = str_replace(" ", ", ", $keytitre); 
	$motclef = ''.$typekey.', '.$keytitre.''.$keyword.'vk, dailymotion, en avance, gratuit, sans limite';
		$type=extstres($_SERVER['REQUEST_URI'], '/', '-');
		if($resulmeta['image'] ==''){
			$image = '//'.$_SERVER['HTTP_HOST'].'/favicon.png';
		}else{
			$image = $resulmeta['image'];
		}
		if(!empty($resulmeta['tag'])){
			$tager =' '.$resulmeta['tag'].' ';
		}
			$metadescrip =  substr(''.ucfirst($type).' streaming et telechargement en hd et illimité '.$resulmeta['titre'].' '.$episode.''.$tager.' replay '.$resulmeta['titre'].' '.$episode.''.$tager.'en avance '.$description.'', 0, 415);
	$metatitle =  substr(''.ucfirst($type).' streaming '.$resulmeta['titre'].' '.$episode.''.$tager.' Streaming et telechargement VF VOSTFR en HD '.ucfirst($type).' complet streaming en illimité| '.namesite().'', 0, 205);
$metaresul=   '<title itemprop="name">'.$metatitle.'</title>
<meta http-equiv="content-language" content="fr" /> 
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<META NAME="DESCRIPTION"  itemprop="description" CONTENT="'.$metadescrip.'" />
<META NAME="keywords"  itemprop="keywords" CONTENT="'.$resulmeta['titre'].' '.$episode.' '.$resulmeta['tag'].', '.$motclef.', serie streaming, serie en streaming, streaming tv, serie streaming, serie tv streaming, serie tv 2016, serie '.date('Y').', serie 2015, vk streaming serie, youwatch serie, streaming vk, full streaming serie, full serie, full streaming, full stream, serie full stream , streaming hd, streaming 720p, streaming sans limite, streaming illimité, stream no limite, Serie en Streaming HD 2015, Series en Streaming 2015, Serie en Streaming Netflix, Netflix streaming, voirfilms, filmsregarder, Netflix, google, youtube, les meilleurs series, meilleur site de streaming, top site de streaming" />
<meta name="robots" content="all"/>
<meta name="revisit-after" content="7 days"/>
<meta name="subject" content="'.$metatitle.'"/>
'.trim($metatag).'
<link href="//'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'" rel="canonical"/>
'.trim($publish).'
<!--Facebook Meta Tags-->
<meta property="fb:app_id" content="966242223397117"> 
<meta property="og:url" content="//'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'"> 
<meta property="og:title" content="'.$metatitle.'">
<meta property="og:description" content="'.$metadescrip.'"> 
<meta property="og:image" content="//'.$_SERVER['HTTP_HOST'].'/retail.php?src='.$image.'&h=140&w=115">
<!--End Facebook Meta Tags-->
<meta property="og:site_name" content="'.namesite().'" />
<meta name="twitter:card"           content="summary_large_image">
<meta name="twitter:site"           content="@'.namesite().'">
<meta name="twitter:title"          content="'.$metatitle.'">
<meta name="twitter:description"    content="'.$metadescrip.'">
<meta name="twitter:image:src"      content="//'.$_SERVER['HTTP_HOST'].'/retail.php?src='.$imagemeta.'&h=240&w=215">
<link  rel = "image_src"  href = "//'.$_SERVER['HTTP_HOST'].'/retail.php?src='.$image.'&h=240&w=215" />
<meta name="robots" content="all, index, follow" />
<meta name="author" content="'.namesite().'"/>
<meta name="copyright" content="'.namesite().'"/>
<meta name="Content-language" content="French"/>
<meta http-equiv="Content-Language" content="fr"/>
<meta name="rating" content="general"/>
<link  rel = "image_src"  href = "//'.$_SERVER['HTTP_HOST'].'/retail.php?src='.$image.'&h=140&w=115" />
<link rel="alternate" type="application/rss+xml" title="Flux rss films series mangas documentaire ebook logiciel jeux video | '.$_SERVER['HTTP_HOST'].'" href="//'.$_SERVER['HTTP_HOST'].'/rss/" />
';

	return trim($metaresul);
	}
	
	
	

		 
		  function addvues2($table,$id,$ip){
		global $cnx;
			 		$requete22 = $cnx->query("SELECT * FROM ".$table." WHERE id =".$id);
$result6 = $requete22->fetch();
 if(!preg_match('`'.$ip.'`i', $result6['ipvus2'])){
	  $newip = $result6['ipvus2'].' | '.$ip;
		  $cnx->exec("UPDATE ".$table." SET vue2 = vue2 + 1, ipvus2 = '".$newip."' WHERE id = '".$id."'");
 }
		 }
		   function addvues3($table,$id,$ip){
			   global $cnx;
				 		$requete22 = $cnx->query("SELECT * FROM ".$table." WHERE id =".$id);
$result6 = $requete22->fetch();
 if(!preg_match('`'.$ip.'`i', $result6['ipvus3'])){
	  $newip = $result6['ipvus3'].' | '.$ip;
		  $cnx->exec("UPDATE ".$table." SET vue3 = vue3 + 1, ipvus3 = '".$newip."' WHERE id = '".$id."'");
 }
		
	     }  
		 
		 function addvues4($table,$id){
			    global $cnx;
		  $cnx->exec("UPDATE ".$table." SET vue4 = vue4 + 1 WHERE id = '".$id."'");
	     }
function getvues2($table,$id){
	global $cnx;
	$query = "SELECT vue2 FROM ".$table." WHERE id = '".$id."'";
	$sql3 = mysql_query($query,$cnx) or die(mysql_error());
	$row = mysql_fetch_array($sql3);
	$nbvue = number_format($row['vue2']);
	return $nbvue;
	}		

		 
		
function getvues3($table,$id){
	global $cnx;
	$query = "SELECT vue3 FROM ".$table." WHERE id = '".$id."'";
	$sql4 = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($sql4);
	$nbvue = number_format($row['vue3']);
	return $nbvue;
	}		

function getvues4($table,$id){
	global $cnx;
	$query = "SELECT vue4 FROM ".$table." WHERE id = '".$id."'";
	$sql5 = mysql_query($query,$cnx) or die(mysql_error());
	$row = mysql_fetch_array($sql5);
	$nbvue = number_format($row['vue4']);
	return $nbvue;
	}	function gethost($host){
		 $cnx = Utils::connexionbd();
		 	$qu = $cnx->query("SELECT nom FROM hebergeurs WHERE id = '".$host."' LIMIT 1");
    $row                = $qu->fetch(PDO::FETCH_ASSOC);
	 
	 $nom = $row['nom'];
	 return trim($nom);
}		

	
    function gethost2($host){
	$qu = mysql_query("SELECT nom FROM hebergeurs WHERE id = '".$host."' LIMIT 1");
	 $row = mysql_fetch_array($qu);
	 $nom = $row['nom'];
	 return trim($nom);	 
}	
 function findchain($start,$end,$string){					
	$pattern = "'$start(.*?)$end'si";
    preg_match($pattern, $string, $matches);
    return trim(strip_tags($matches[1]));
}	
function liendirect($embed,$host){
	$host = gethost($host);
	if ($host == "Purevid"){
		$deb = "embed&id=";
		$fin = '"';
		$fid = findchain($deb,$fin,$embed);
$lien = '<center><div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">×</button>
			<i>Attention, vous devez être inscrit et connecté sur  <a href= "//purevid.com " target "_blank">Purevid</a> ou utilisez le debrideur pour un meilleur fonctionnement des vidéos</i>
</div></br>
<a href="//pure.redheb.com/v/'.$fid.'/" target="_blank">//pure.redheb.com/v/'.$fid.'/</a></br></br>
<a  class="iframe" href="//www.dailymotion.com/embed/video/k3dr83c4wDHezP5dBs1?logo=0&info=0&quality=480" ><strong><span style="color: #00ff00;">Comment débrider ?</span></strong></a></br></br>
	<A class="iframe" href="//www.fullsharez.com/deb/deblect3.php?film=//www.purevid.com/v/'.$fid.'/"><INPUT type="submit" class="btn btn-success" value="Debrider sans java"></A></br></br>
	<A class="iframe" href="//www.fullsharez.com/deb/deblect.php?url=//www.purevid.com/v/'.$fid.'/"><span class="btn btn-success">DEBRIDER</span></A>
                 </center></p>';
		return $lien;
		}	
elseif ($host == "Videoweed"){
		$deb = 'v=';
	    $fin = '"' or  $fin = '&';
		$fid = findchain($deb,$fin,$embed);
		$fid = str_replace("'","",$fid);
$lien = '<a href="//www.videoweed.es/file/'.$fid.'" target="_blank">//www.videoweed.es/file/'.$fid.'</a></br></br>
<center><a  class="iframe" href="//www.dailymotion.com/embed/video/k3dr83c4wDHezP5dBs1?logo=0&info=0&quality=480" ><strong><span style="color: #00ff00;">Comment débrider ?</span></strong></a></br></br>
<A class="iframe" href="//www.fullsharez.com/deb/deblect.php?url=//www.videoweed.es/file/'.$fid.'"><span class="btn btn-success">DEBRIDER</span></A>
                 </center></p>';
		return $lien;	
		}
		elseif ($host == "Uploadbb"){
		$deb = 'u=';
	    $fin = 'w=' ;
		$embed = str_replace("/v/","u=",$embed);
		$embed = str_replace('"',"w=",$embed);
		$fid = findchain($deb,$fin,$embed);
		$fid = str_replace("'","",$fid);
		$fid = str_replace("&","",$fid);
$lien = '<a href="//ubb.redheb.com/'.$fid.'" target="_blank">//ubb.redheb.com/'.$fid.'</a>';
		return $lien;	
		}	elseif ($host == "Netflux"){
		$deb = 'u=';
	    $fin = 'w=' ;
		$embed = str_replace("/v/","u=",$embed);
		$embed = str_replace('"',"w=",$embed);
		$fid = findchain($deb,$fin,$embed);
		$fid = str_replace("'","",$fid);
		$fid = str_replace("&","",$fid);
$lien = '<a href="//nfx.redheb.com/'.$fid.'" target="_blank">//nfx.redheb.com/'.$fid.'</a>';
		return $lien;	
		}
		elseif ($host == "Stagevu"){
		$deb = 'uid=';
	    $fin = "scrolling=";
		$fid = findchain($deb,$fin,$embed);
		$fid = str_replace("'","",$fid);
		$lien = '<a  href="//stagevu.com/video/'.$fid.'" target="_blank">//stagevu.com/video/'.$fid.'</a></br></br>
		<center><a  class="iframe" href="//www.dailymotion.com/embed/video/k3dr83c4wDHezP5dBs1?logo=0&info=0&quality=480" ><strong><span style="color: #00ff00;">Comment débrider ?</span></strong></a></br></br>
		<A class="iframe" href="//www.fullsharez.com/deb/deblect.php?url=//stagevu.com/video/'.$fid.'/"><span class="btn btn-success">DEBRIDER</span></A>
                 </center>';
	    return $lien;
		}	elseif ($host == "Veoh"){
		$deb = 'permalinkId=';
	    $fin = "&player=";
		$fid = findchain($deb,$fin,$embed);
		$fid = str_replace("'","",$fid);
		$lien = '<a  href="//www.veoh.com/watch/'.$fid.'" target="_blank">//www.veoh.com/watch/'.$fid.'</a></br></br>
		<center><a  class="iframe" href="//www.dailymotion.com/embed/video/k3dr83c4wDHezP5dBs1?logo=0&info=0&quality=480" ><strong><span style="color: #00ff00;">Comment débrider ?</span></strong></a></br></br><A class="iframe" href="//www.fullsharez.com/deb/deblect.php?url=//www.veoh.com/watch/'.$fid.'/"><span class="btn btn-success">DEBRIDER</span></A>
                 </center></p>';
return $lien;
		} elseif($host == "youwatch"){
		$deb = 'youwatch.org/embed-';
	    $fin = "-";
		$fid = findchain($deb,$fin,$embed);
		$fid = str_replace("'","",$fid);
		$lien = '<a href="//youw.redheb.com/'.$fid.'" target="_blank">//youw.redheb.com/'.$fid.'</a></br></br>
		<center><a  class="iframe" href="//www.dailymotion.com/embed/video/k3dr83c4wDHezP5dBs1?logo=0&info=0&quality=480" ><strong><span style="color: #00ff00;">Comment débrider ?</span></strong></a></br></br>
		<A class="iframe" href="//www.fullsharez.com/deb/deblect.php?url=//youwatch.org/'.$fid.'/" ><span class="btn btn-success">DEBRIDER</span></A></br>
 </center></p>';
	    return $lien;
		}	
		elseif($host == "Vk"){
$deb = 'oid=';
	                            $fin = '"';
		$fid = findchain($deb,$fin,$embed);
		
						   	$fid=str_replace("'","",$fid);
						 	$fid=str_replace('"',"",$fid);
						 	$fid=str_replace('hd=2',"hd=1",$fid);
		$lien = '';
	    return $lien;
		}	
		elseif($host == "Modovideo"){
		$deb = "v=";
	    $fin = "'";
		$fin = mysql_real_escape_string($fin) ;
		$fid = findchain($deb,$fin,$embed);
		$lien = '<center><div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">×</button>
			<i>Attention connection hotspot, crous, étudiante passer par ce proxy Extension chrome <a href="https://chrome.google.com/webstore/detail/zenmate-for-google-chrome/fdcgdnkidjaadafnichfpabhfomcebme" target="_blank">Extension chrome</a></i></div>
			</br></p><a href="//modovideo.com/'.$fid.'" target="_blank">//modovideo.com/'.$fid.'</a></br></br>
		<center><a  class="iframe" href="//www.dailymotion.com/embed/video/k3dr83c4wDHezP5dBs1?logo=0&info=0&quality=480" ><strong><span style="color: #00ff00;">Comment débrider ?</span></strong></a></br></br>
<A class="iframe" href="//www.fullsharez.com/deb/deblect.php?url=//modovideo.com/'.$fid.'"  ><span class="btn btn-success">DEBRIDER la video</span></a>';
 return $lien; } elseif($host == "Exashare"){
		$deb = 'exashare.com/embed-';
	    $fin = "-";
		$fid = findchain($deb,$fin,$embed);
		$fid = str_replace("'","",$fid);
		$lien = '</br></p><a href="//exa.redheb.com/'.$fid.'" target="_blank">//exa.redheb.com/'.$fid.'</a></br></br>
		<center><a  class="iframe" href="//www.dailymotion.com/embed/video/k3dr83c4wDHezP5dBs1?logo=0&info=0&quality=480" ><strong><span style="color: #00ff00;">Comment débrider ?</span></strong></a></br></br>
		<A class="iframe" href="//www.fullsharez.com/deb/deblect.php?url=//www.exashare.com/'.$fid.'" ><span class="btn btn-success">DEBRIDER</span></A></br>';
	    return $lien;
		}
		elseif($host == "Firedrive"){
		$deb = 'embed/';
	    $fin = '>';
		$fid = findchain($deb,$fin,$embed);
		$fid = str_replace("'","",$fid);
		$fid = str_replace('"',"",$fid);
				$fid = str_replace(' width=600 height=360 frameborder=0 scrolling=no',"",$fid);
		$lien = '<a  href="//www.firedrive.com/file/'.$fid.'" target="_blank">//www.firedrive.com/file/'.$fid.'</a></br></br>
		<center><a  class="iframe" href="//www.dailymotion.com/embed/video/k3dr83c4wDHezP5dBs1?logo=0&info=0&quality=480" ><strong><span style="color: #00ff00;">Comment débrider ?</span></strong></a></br></br>
				</br><A class="iframe" href="//www.fullsharez.com/deb/deblect.php?url=//www.firedrive.com/file/'.$fid.'"><span class="btn btn-success">DEBRIDER</span></A>
                 </center></p>';
	    return $lien;
		}else{ return ''; } } 
 $badmots= array('` bite ', ' cul ', ' suce ', 'alldebride', 'blogspot.fr', 'blogspot', 'plus-belle-la-vie-video.blogspot.fr', 'Nouvelpub', 'nouvelpub', 'nouvellespubs',  'Jehaismesvoisins',  'jehaismesvoisins', 'Nouvellespubs', 'DpStream ', 'planet', 'alldebrid', 'dpstream', 'Dpstream', 'allstream', ' enculer', ' salope', ' gueulle', 'guelle', ' adblock', ' pute ', ' sex ', ' promotion ', 'lwigscelebrity', 'coolnorthfacejackets.', 'North', 'ourbelstaff', 'allredwing', 'allpandorarings`i');
$tagurl  = array('');


function strposa($haystack, $needle) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $what) {
        if(($pos = strpos($haystack, $what))!==false) 
		
		return true;
    }
    return false;
} 

function suiprec($idseries,$ep,$saisons,$table,$lang) {
	$cnx = Utils::connexionbd();
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	if($ep){
		
		$sql0 = $cnx->query('SELECT * FROM '.$table.' where id = "'.$ep.'"');
$recupep  = $sql0->fetch(PDO::FETCH_ASSOC);	
$recupepff = $recupep['titre'];	
$Langue = $recupep['Langue'];	
	//$ep=str_replace("0","",$ep);
	//$numsuv = $ep + 1;
	
	//$numpre = $ep - 1;
$sql = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` > "'.$recupepff.'" AND  `saisons` ="'.$saisons.'" AND `Langue` = "'.$Langue.'" AND `correspondance` = '.$idseries.' ORDER BY titre asc limit 1');
$suivant  = $sql->fetch(PDO::FETCH_ASSOC);

$titresuiv = $suivant['titre'];	
$sql2 = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` < "'.$recupepff.'" AND  `saisons` ="'.$saisons.'"  AND `Langue` = "'.$Langue.'" AND `correspondance` = '.$idseries.'  ORDER BY titre desc limit 1');
$precedent  = $sql2->fetch(PDO::FETCH_ASSOC);
$titrepreced = $precedent['titre'];
$epiurlsuiv = serierewrite($suivant['id'], $suivant['titre_fiche'], $suivant['saisons'] ,$suivant['titre'], $suivant['correspondance'],$suivant['Langue']);	
$epiurlprec= serierewrite($precedent['id'], $precedent['titre_fiche'], $precedent['saisons'] ,$precedent['titre'], $precedent['correspondance_series'],$precedent['Langue']);								  

if($titrepreced !=''){ $preced = '<li class="previous"><a href="'.$epiurlprec.'">Précedent</a></li>';}else{ $preced = '<li class="previous disabled"><a href="#">Précedent</a></li>';}
if($titresuiv !=''){ $suiv = '<li class="next"><a href="'.$epiurlsuiv.'">Suivant</a></li>';}else{ $suiv = '<li class="next disabled"><a href="#">Suivant</a></li>';}
$pager ='<ul class="pager">'.$preced.''.$suiv.'</ul>';
	}
	 return $pager;
}
//SERIESunique
function serierewriteunique($idserie, $titre ,$saison, $episode,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );
$serieurl = "//".$nom_de_domaine."/series-".$idserie."-".$stitre."-saison-".$saison."-episode-".$episode."-".$langue.".html";
return $serieurl;
} else {
return  "//".$nom_de_domaine."/series_infos_streaming2.php?idseries=".$id."&saison=".$saison."&episode=".$episode."&langue=".$langue."";
}
}
//mangaepunique
function mangarewriteunique($idserie, $titre ,$saison, $episode,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );
$serieurl = "//".$nom_de_domaine."/mangas-".$idserie."-".$stitre."-saison-".$saison."-episode-".$episode."-".$langue.".html";
return $serieurl;
} else {
return  "//".$nom_de_domaine."/mangas_infos_streaming2.php?idmangas=".$id."&saison=".$saison."&episode=".$episode."&langue=".$langue."";
}
}
//docuepunique
function docurewriteunique($idserie, $titre ,$saison, $episode,$langue) {
global $nom_de_domaine,$Search,$Replace;
$mode_rewrite = '1';
if ($mode_rewrite == '1') {
$titre =removeaccents($titre);
$stitre= str_replace($Search, $Replace, utf8_encode($titre));
$stitre = strip_tags( $stitre );
$stitre = preg_replace( "/[^A-Za-z0-9]+/", "-", $stitre );
$stitre = trim( $stitre, "-" );
$saison = trim( $saison, "-" );
$episode = trim( $episode, "-" );
$langue = trim( strtolower($langue), "-" );
$serieurl = "//".$nom_de_domaine."/documentaires-".$idserie."-".$stitre."-saison-".$saison."-episode-".$episode."-".$langue.".html";
return $serieurl;
} else {
return  "//".$nom_de_domaine."/documentaire_infos_streaming2.php?iddocu=".$id."&saison=".$saison."&episode=".$episode."&langue=".$langue."";
}
}

function suiprecunique($idserie,$titre,$ep,$saisons,$table,$lang,$type) {
	$cnx = Utils::connexionbd();
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	$titreprec = '';
$epiurlprec = '';
$titresuivant = '';
$epiurlsuiv = '';
	if($ep){
		
	$ep=str_replace('_','-',$ep);
$sql = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` > "'.$ep.'" AND  `saisons` ="'.$saisons.'" AND `Langue` = "'.$lang.'" AND `titre_fiche` like "'.$titre.'"  ORDER BY titre asc limit 1');
$suivant  = $sql->fetch(PDO::FETCH_ASSOC);

$titresuiv = $suivant['titre'];	
$titresuiv=str_replace('-','_',$titresuiv);
$sql2 = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` < "'.$ep.'" AND  `saisons` ="'.$saisons.'"  AND `Langue` = "'.$lang.'" AND `titre_fiche` like "'.$titre.'"  ORDER BY titre desc limit 1');
$precedent  = $sql2->fetch(PDO::FETCH_ASSOC);
$titrepreced = $precedent['titre'];
	$titresuiv=str_replace('-','_',$titresuiv);
	$suivant['titre']=str_replace('-','_',$suivant['titre']);
										$precedent['titre']=str_replace('-','_',$precedent['titre']);
$funrew = '';
if($type =='serie'){
	$funrew = 'serierewriteunique';
}
if($type =='manga'){
	$funrew = 'mangarewriteunique';
}

if($precedent['titre'] !=''){ 
$titreprec = ''.$titre.' saison '.$saisons.' episode '.$precedent['titre'].' '.$lang.'';
$epiurlprec= $funrew($idserie, $titre ,$saisons, $precedent['titre'],$lang);
}else{ 
$titreprec = '';
$epiurlprec = '';
}
if($suivant['titre'] !=''){ 
$titresuivant = ''.$titre.' saison '.$saisons.' episode '.$suivant['titre'].' '.$lang.'';
$epiurlsuiv = $funrew($idserie, $titre ,$saisons, $suivant['titre'],$lang);	
}else{ 
$titresuivant = '';
$epiurlsuiv = '';
}

	}
	// $tableau = [''.$variableprecedent.'
	// '.$variablesuivant.''];
	
$tableau =	array('titreprec' => $titreprec,
		'urlprecedent' =>	$epiurlprec,
		'titresuiv' => $titresuivant,
		'urlsuiv' =>	$epiurlsuiv);
	 return $tableau;
}

function suiprecunique2($idserie,$titre,$ep,$saisons,$table,$lang) {
	$cnx = Utils::connexionbd();
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	if($ep){
		
	
	//$ep=str_replace("0","",$ep);
	//$numsuv = $ep + 1;
	
	//$numpre = $ep - 1;
$sql = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` > "'.$ep.'" AND  `saisons` ="'.$saisons.'" AND `Langue` = "'.$lang.'" AND `titre_serie` like "'.$titre.'"  ORDER BY titre asc limit 1');
$suivant  = $sql->fetch(PDO::FETCH_ASSOC);

$titresuiv = $suivant['titre'];	

$sql2 = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` < "'.$ep.'" AND  `saisons` ="'.$saisons.'"  AND `Langue` = "'.$lang.'" AND `titre_serie` like "'.$titre.'"  ORDER BY titre desc limit 1');
$precedent  = $sql2->fetch(PDO::FETCH_ASSOC);
$titrepreced = $precedent['titre'];
$titrepreced=str_replace('-','_',$titrepreced);
$titresuiv=str_replace('-','_',$titresuiv);
$epiurlsuiv = docurewriteunique($idserie, $titre ,$suivant['saisons'], $titresuiv,$suivant['Langue']);	
$epiurlprec= docurewriteunique($idserie, $titre ,$precedent['saisons'], $titrepreced,$precedent['Langue']);
							  

if($titrepreced !=''){ 
$preced = '<li class="previous"><a  rel="prev" href="'.$epiurlprec.'#lecteurs" title="'.$titre.' Saison '.$precedent['saisons'].' episode '.$precedent['titre'].' '.$precedent['Langue'].'">Précedent</a></li>';
}else{ 
$preced = '<li class="previous disabled"><a  rel="prev" href="#">Précedent</a></li>';}
if($titresuiv !=''){ 
$suiv = '<li class="next"><a  rel="next" href="'.$epiurlsuiv.'#lecteurs" title="'.$titre.' Saison '.$suivant['saisons'].' episode '.$suivant['titre'].' '.$suivant['Langue'].'">Suivant</a></li>';
}else{ 
$suiv = '<li class="next disabled"><a  rel="next" href="#">Suivant</a></li>';
}
$pager ='<ul class="pager">'.$preced.''.$suiv.'</ul>';
	}
	 return $pager;
}

function suiprecdocuunique($idseries,$ep,$saisons,$table,$lang) {
	$cnx = Utils::connexionbd();
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	if($ep){
		
	
	//$ep=str_replace("0","",$ep);
	//$numsuv = $ep + 1;
	
	//$numpre = $ep - 1;
$sql = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` > "'.$ep.'" AND  `saisons` ="'.$saisons.'" AND `Langue` = "'.$lang.'" AND `titre_serie` = '.$idseries.' ORDER BY titre asc limit 1');
$suivant  = $sql->fetch(PDO::FETCH_ASSOC);

$titresuiv = $suivant['titre'];	
$sql2 = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` < "'.$ep.'" AND  `saisons` ="'.$saisons.'"  AND `Langue` = "'.$lang.'" AND `titre_serie` = '.$idseries.'  ORDER BY titre desc limit 1');
$precedent  = $sql2->fetch(PDO::FETCH_ASSOC);
$titrepreced = $precedent['titre'];
$titrepreced=str_replace('-','_',$titrepreced);
$titresuiv=str_replace('-','_',$titresuiv);
$epiurlsuiv = docurewriteunique($suivant['correspondance_series'], $suivant['titre_serie'] ,$suivant['saisons'], $titresuiv,$suivant['Langue']);	
$epiurlprec= docurewriteunique($precedent['correspondance_series'], $precedent['titre_serie'] ,$precedent['saisons'], $titrepreced,$precedent['Langue']);
							  

if($titrepreced !=''){ $preced = '<li class="previous"><a  rel="prev" href="'.$epiurlprec.'#lecteurs">Précedent</a></li>';
}else{ 
$preced = '<li class="previous disabled"><a rel="prev" href="#">Précedent</a></li>';}
if($titresuiv !=''){ $suiv = '<li class="next"><a  rel="next" href="'.$epiurlsuiv.'#lecteurs">Suivant</a></li>';
}else{ $suiv = '<li class="next disabled"><a  rel="next" href="#">Suivant</a></li>';}
$pager ='<ul class="pager">'.$preced.''.$suiv.'</ul>';
	}
	 return $pager;
}

function suiprecmaunique($idseries,$titremanga,$ep,$saisons,$table,$lang) {
	$ep=str_replace('_','-',$ep);
	$cnx = Utils::connexionbd();
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	if($ep){

$sql = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` != "'.$ep.'" and  `titre` > "'.$ep.'" AND  `saisons` ="'.$saisons.'" AND `Langue` = "'.$lang.'" AND `titre_mangas` LIKE "'.$titremanga.'" group by titre ORDER BY titre asc limit 1');
$suivant  = $sql->fetch(PDO::FETCH_ASSOC);

$titresuiv = $suivant['titre'];	
$titresuiv=str_replace('-','_',$titresuiv);
$sql2 = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` < "'.$ep.'" AND  `saisons` ="'.$saisons.'"  AND `Langue` = "'.$lang.'" AND `titre_mangas` LIKE "'.$titremanga.'"  ORDER BY titre desc limit 1');
$precedent  = $sql2->fetch(PDO::FETCH_ASSOC);
$titrepreced = $precedent['titre'];
$suivant['titre']=str_replace('-','_',$suivant['titre']);
										$precedent['titre']=str_replace('-','_',$precedent['titre']);
$epiurlsuiv = mangarewriteunique($idseries, $titremanga ,$suivant['saisons'], $suivant['titre'],$lang);	
$epiurlprec= mangarewriteunique($idseries, $titremanga ,$precedent['saisons'],  $precedent['titre'],$lang);
							  

if($titrepreced !=''){ 
$preced = '<li class="previous"><a  rel="prev" href="'.$epiurlprec.'#lecteurs">Précedent</a></li>';
}else{ 
$preced = '<li class="previous disabled"><a  rel="prev" href="#">Précedent</a></li>';}
if($titresuiv !=''){ 
$suiv = '<li class="next"><a  rel="next" href="'.$epiurlsuiv.'#lecteurs">Suivant</a></li>';}else{ 
$suiv = '<li class="next disabled"><a  rel="next" href="#">Suivant </a></li>';}
$pager ='<ul class="pager">'.$preced.''.$suiv.'</ul>';
	}
	 return $pager;
}
function suiprecmanga($idseries,$ep,$saisons,$table,$lang) {
	$cnx = Utils::connexionbd();
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
	if($ep){
		
		$sql0 = $cnx->query('SELECT * FROM '.$table.' where id = "'.$ep.'"');
$recupep  = $sql0->fetch(PDO::FETCH_ASSOC);	
$recupepff = $recupep['titre'];	
	//$ep=str_replace("0","",$ep);
	//$numsuv = $ep + 1;
	
	//$numpre = $ep - 1;
$sql = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` > "'.$recupepff.'" AND  `saisons` ="'.$saisons.'" AND `Langue` = "'.$lang.'" AND `correspondance_mangas` = '.$idseries.' ORDER BY titre asc limit 1');
$suivant  = $sql->fetch(PDO::FETCH_ASSOC);

$titresuiv = $suivant['titre'];	
$sql2 = $cnx->query('SELECT * FROM `'.$table.'` WHERE `titre` < "'.$recupepff.'" AND  `saisons` ="'.$saisons.'"  AND `Langue` = "'.$lang.'" AND `correspondance_mangas` = '.$idseries.'  ORDER BY titre desc limit 1');
$precedent  = $sql2->fetch(PDO::FETCH_ASSOC);
$titrepreced = $precedent['titre'];
$epiurlsuiv = mangarewrite($suivant['id'], $suivant['titre_mangas'], $suivant['saisons'] ,$suivant['titre'], $suivant['correspondance_mangas'],$suivant['Langue']);	
$epiurlprec= mangarewrite($precedent['id'], $precedent['titre_mangas'], $precedent['saisons'] ,$precedent['titre'], $precedent['correspondance_mangas'],$precedent['Langue']);								  

if($titrepreced !=''){ $preced = '<li class="previous"><a  rel="prev" href="'.$epiurlprec.'">Précedent</a></li>';
}else{ $preced = '<li class="previous disabled"><a  rel="prev" href="#">Précedent</a></li>';}
if($titresuiv !=''){ $suiv = '<li class="next"><a  rel="next" href="'.$epiurlsuiv.'">Suivant</a></li>';}else{ $suiv = '<li class="next disabled"><a  rel="next" href="#">Suivant</a></li>';}
$pager ='<ul class="pager">'.$preced.''.$suiv.'</ul>';
	}
	 return $pager;
}
	
	//error 404
	function erreur404($uris,$type) {
	$cnx = Utils::connexionbd();
$uris = str_replace('1080P',' ',$uris);
$uris = str_replace('R5',' ',$uris);
$uris = str_replace('720P',' ',$uris);
$uris = str_replace('#[0-9]#',' ',$uris);	
$uris = str_replace('0',' ',$uris);	
$uris = str_replace('1',' ',$uris);	
$uris = str_replace('2',' ',$uris);	
$uris = str_replace('3',' ',$uris);	
$uris = str_replace('4',' ',$uris);	
$uris = str_replace('5',' ',$uris);	
$uris = str_replace('6',' ',$uris);	
$uris = str_replace('7',' ',$uris);	
$uris = str_replace('8',' ',$uris);	
$uris = str_replace('9',' ',$uris);	
$uris = str_replace('/',' ',$uris);
$uris = str_replace('-',' ',$uris);
$uris = str_replace('.',' ',$uris);
$uris = str_replace('html',' ',$uris);
$uris = str_replace('films',' ',$uris);
$uris = str_replace('film',' ',$uris);
$uris = str_replace('series',' ',$uris);
$uris = str_replace('serie',' ',$uris);
$uris = str_replace('streaming',' ',$uris);
$uris = str_replace('téléchargement',' ',$uris);
$uris = str_replace('telechargement',' ',$uris);
$uris = str_replace('saison',' ',$uris);
$uris = str_replace('episode',' ',$uris);
$uris = str_replace('fr',' ',$uris);
$uris = str_replace('FR',' ',$uris);
$uris = str_replace('VOSTFR',' ',$uris);
$uris = str_replace('VO',' ',$uris);
$uris = str_replace('DVDRIP',' ',$uris);
$uris = str_replace('BDRIP',' ',$uris);
$uris = str_replace('TS',' ',$uris);
$uris = str_replace('CAM',' ',$uris);
$uris = str_replace('mangas',' ',$uris);
$uris = str_replace('documentaire',' ',$uris);
$uris = str_replace('ebook',' ',$uris);
$uris = str_replace('logiciel',' ',$uris);
$uris = str_replace('manga',' ',$uris);
$uris = str_replace('vost ',' ',$uris);
$uris = str_replace('  ',' ',$uris);	
$uris = trim($uris);
$uris2 = couperChaine($uris, 1) ;
$uris2 = mb_strtolower($uris2);
$keyworduris = trim($uris);
$keyworduris = str_replace(' ',',',$keyworduris);
$uriskeyword0 = explode(',',$keyworduris);
foreach($uriskeyword0 as $keywordsu){ 
$keywords = ''.$keywordsu.'';
	 if (strlen($keywordsu) >= '10') {
		 $keywordsu= str_replace("...", "", $keywordsu);
$urislike .=  ''.ltrim($keywordsu).' ';
	 }}
$urislike= mysql_real_escape_string($urislike);
$variabledeb ="<div class=\"container\">
<div class=\"jumbotron\">
<h1 style=\"font-size: 15px;\">Il semblerait que nous ne soyons pas en mesure de trouver  <a href=\"//".$_SERVER['HTTP_HOST']."/search.php?q=".$urislike."\">".$urislike."</a> . Essayez en lançant une recherche, ou à l’aide de l’un des lien ci-dessous.</h1>";
if($type == 'film'){
	$reponse = $cnx->query("SELECT * FROM fiche WHERE titre LIKE '%".trim($urislike)."%' and categorie ='film' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
$idfilm = stripslashes($result1['id']);
$titrefilm = stripslashes($result1['titre']);
$lienfilm = filmrewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire Films <a href=\"".$lienfilm."\" >".$result1['titre']." </a></p>";					
	}
}		  
		if($type == 'serie'){
	$reponse = $cnx->query("SELECT * FROM fiche WHERE categorie='serie' and titre LIKE '%".trim($urislike)."%' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
$idfilm = stripslashes($result1['id']);
$titrefilm = stripslashes($result1['titre']);
$lienfilm = seriesrewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire series <a href=\"".$lienfilm."\" >".$result1['titre']." </a></p>";						
}
 }		
 if($type == 'documentaire'){
	$reponse = $cnx->query("SELECT * FROM fiche WHERE categorie='documentaire' and titre LIKE '%".trim($urislike)."%' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
$idfilm = stripslashes($result1['id']);
$titrefilm = stripslashes($result1['titre']);
$lienfilm = docu2rewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire series <a href=\"".$lienfilm."\" >".$result1['titre']." </a></p>";						
}
 }	
 if($type == 'mangas'){
	$reponse = $cnx->query("SELECT * FROM fiche WHERE categorie='mangas' and titre LIKE '%".trim($urislike)."%' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
$idfilm = stripslashes($result1['id']);
$titrefilm = stripslashes($result1['titre']);
$lienfilm = manga2rewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire series <a href=\"".$lienfilm."\" >".$result1['titre']." </a></p>";						
}
 }	 
 if($type == 'ebook'){
	$reponse = $cnx->query("SELECT * FROM fiche WHERE categorie='ebook' and titre LIKE '%".trim($urislike)."%' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
$idfilm = stripslashes($result1['id']);
$titrefilm = stripslashes($result1['titre']);
$lienfilm = ebookdl($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire series <a href=\"".$lienfilm."\" >".$result1['titre']." </a></p>";						
}
 }	
 if($type == 'logiciel'){
	$reponse = $cnx->query("SELECT * FROM fiche WHERE categorie='logiciel' and titre LIKE '%".trim($urislike)."%' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
$idfilm = stripslashes($result1['id']);
$titrefilm = stripslashes($result1['titre']);
$lienfilm = logicieldl($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire series <a href=\"".$lienfilm."\" >".$result1['titre']." </a></p>";						
}
 }	 if($type == 'jeux'){
	$reponse = $cnx->query("SELECT * FROM fiche WHERE categorie='jeux' and titre LIKE '%".trim($urislike)."%' group by titre ORDER BY post_date_gmt desc limit 20");
   while($result1 = $reponse->fetch()) {	
$idfilm = stripslashes($result1['id']);
$titrefilm = stripslashes($result1['titre']);
$lienfilm = jeuxdl($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire series <a href=\"".$lienfilm."\" >".$result1['titre']." </a></p>";						
}
 }	
 $variablefinal = ''.$variabledeb.''.$variabletext.'</div></div>';
	return $variablefinal;
	}
	
	function erreur404v2($uris3,$type) {
	$cnx = Utils::connexionbd();
$uris = $uris3;
$uris = str_replace('1080P',' ',$uris3);
$uris = str_replace('R5',' ',$uris);
$uris = str_replace('720P',' ',$uris);
$uris = str_replace('#[0-9]#',' ',$uris);	
$uris = str_replace('0',' ',$uris);	
$uris = str_replace('1',' ',$uris);	
$uris = str_replace('2',' ',$uris);	
$uris = str_replace('3',' ',$uris);	
$uris = str_replace('4',' ',$uris);	
$uris = str_replace('5',' ',$uris);	
$uris = str_replace('6',' ',$uris);	
$uris = str_replace('7',' ',$uris);	
$uris = str_replace('8',' ',$uris);	
$uris = str_replace('9',' ',$uris);	
$uris = str_replace('/',' ',$uris);
$uris = str_replace('-',' ',$uris);
$uris = str_replace('.',' ',$uris);
$uris = str_replace('html',' ',$uris);
$uris3 = str_replace('/',' ',$uris3);
$uris3 = str_replace('-',' ',$uris3);
$uris3 = str_replace('.',' ',$uris3);
$uris3 = str_replace('html',' ',$uris3);
$uris = str_replace('films',' ',$uris);
$uris = str_replace('film',' ',$uris);
$uris = str_replace('series',' ',$uris);
$uris = str_replace('serie',' ',$uris);
$uris3 = str_replace('series',' ',$uris3);
$uris3 = str_replace('serie',' ',$uris3);
$uris = str_replace('streaming',' ',$uris);
$uris = str_replace('téléchargement',' ',$uris);
$uris = str_replace('telechargement',' ',$uris);
$uris = str_replace('saison',' ',$uris);
$uris = str_replace('episode',' ',$uris);
$uris = str_replace('fr',' ',$uris);
$uris = str_replace('FR',' ',$uris);
$uris = str_replace('VOSTFR',' ',$uris);
$uris = str_replace('VO',' ',$uris);
$uris = str_replace('DVDRIP',' ',$uris);
$uris = str_replace('BDRIP',' ',$uris);
$uris = str_replace('TS',' ',$uris);
$uris = str_replace('CAM',' ',$uris);
$uris = str_replace('mangas',' ',$uris);
$uris = str_replace('documentaire',' ',$uris);
$uris = str_replace('ebook',' ',$uris);
$uris = str_replace('logiciel',' ',$uris);
$uris = str_replace('manga',' ',$uris);
$uris = str_replace('dl ',' ',$uris);
$uris = str_replace('vost ',' ',$uris);
$uris = str_replace('  ',' ',$uris);	
$uris = trim($uris);
$uris2 = couperChaine($uris, 1) ;
$uris2 = mb_strtolower($uris2);
$keyworduris = trim($uris);
$keyworduris = str_replace(' ',',',$keyworduris);
$uriskeyword0 = explode(',',$keyworduris);
foreach($uriskeyword0 as $keywordsu){ 
$keywords = ''.$keywordsu.'';
	 if (strlen($keywordsu) >= '10') {
		 $keywordsu= str_replace("...", "", $keywordsu);
$urislike .=  ''.ltrim($keywordsu).' ';
	 }}
$urislike= mysql_real_escape_string($urislike);
$variabledeb ="<div class=\"container\">
<div class=\"jumbotron\">
<h1 style=\"font-size: 15px;\">Il semblerait que nous ne soyons pas en mesure de trouver  <a href=\"//".$_SERVER['HTTP_HOST']."/search.php?q=".$uris."\">".$uris."</a> . Essayez en lançant une recherche, ou à l’aide de l’un des lien ci-dessous.</h1>";
if($type == 'film'){
	$min_word_lenght = 1; /* taille mini des mots a compter dans la recherche */
$search = explode(' ',$uris);

$request='';
$last_key = end(array_keys($search));

foreach($search as $wordkey => $word){
	if($wordkey === $last_key){
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='film' and titre LIKE '%".trim($word)."%' ";
       }
	}else{
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='film' and titre LIKE '%".trim($word)."%'  || ";
       }		
	}
}
$sql               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='film'");
$result  = $sql->fetch(PDO::FETCH_ASSOC) ;
if ($result['id'] > 0) {
    $sql2               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='film'");
		  while($row = $sql2->fetch()) {
      $idfilm = stripslashes($row['id']);
$titrefilm = stripslashes($row['titre']);
$lienfilm = filmrewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire Films <a href=\"".$lienfilm."\" >".$row['titre']." </a></p>";	
    }

}
}		  
		if($type == 'serie'){
		$sql               = $cnx->query("SELECT * FROM fiche WHERE  `titre` LIKE  '%".trim(addslashes($uris3))."%' and categorie='serie'");
$result  = $sql->fetch(PDO::FETCH_ASSOC) ;
if ($result['id'] > 0) {
	      $idfilm = stripslashes($result['id']);
$titrefilm = stripslashes($result['titre']);
$lienfilm = seriesrewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire series <a href=\"".$lienfilm."\" >".$result['titre']." </a></p>";	
    }else{
	$min_word_lenght = 4; /* taille mini des mots a compter dans la recherche */
$search = explode(' ',trim(addslashes($uris3)));
//echo $uris3;
$request='';
$last_key = end(array_keys($search));

foreach($search as $wordkey => $word){
	if($wordkey === $last_key){
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='serie' and  titre LIKE '%".trim($word)."%' ";
       }
	}else{
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='serie' and titre LIKE '%".trim($word)."%'  || ";
       }		
	}
}
$sql               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='serie'");
$result  = $sql->fetch(PDO::FETCH_ASSOC) ;
if ($result['id'] > 0) {
    $sql2               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='serie'");
		  while($row = $sql2->fetch()) {
      $idfilm = stripslashes($row['id']);
$titrefilm = stripslashes($row['titre']);
$lienfilm = seriesrewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire series <a href=\"".$lienfilm."\" >".$row['titre']." </a></p>";	
    }
}
 }	 }		
 if($type == 'documentaire'){
	 	$min_word_lenght = 1; /* taille mini des mots a compter dans la recherche */
$search = explode(' ',$uris);

$request='';
$last_key = end(array_keys($search));

foreach($search as $wordkey => $word){
	if($wordkey === $last_key){
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='documentaire' and titre LIKE '%".trim($word)."%' ";
       }
	}else{
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='documentaire' and titre LIKE '%".trim($word)."%'  || ";
       }		
	}
}
$sql               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='documentaire'");
$result  = $sql->fetch(PDO::FETCH_ASSOC) ;
if ($result['id'] > 0) {
    $sql2               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='documentaire'");
		  while($row = $sql2->fetch()) {
      $idfilm = stripslashes($row['id']);
$titrefilm = stripslashes($row['titre']);
$lienfilm = docu2rewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire Documentaire <a href=\"".$lienfilm."\" >".$row['titre']." </a></p>";	
    }
}

 }	
 if($type == 'mangas'){
	 	$min_word_lenght = 1; /* taille mini des mots a compter dans la recherche */
$search = explode(' ',$uris);

$request='';
$last_key = end(array_keys($search));

foreach($search as $wordkey => $word){
	if($wordkey === $last_key){
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='mangas' and titre LIKE '%".trim($word)."%' ";
       }
	}else{
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='mangas' and titre LIKE '%".trim($word)."%'  || ";
       }		
	}
}
$sql               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='mangas'");
$result  = $sql->fetch(PDO::FETCH_ASSOC) ;
if ($result['id'] > 0) {
    $sql2               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='mangas'");
		  while($row = $sql2->fetch()) {
      $idfilm = stripslashes($row['id']);
$titrefilm = stripslashes($row['titre']);
$lienfilm = manga2rewriteunique($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire Mangas <a href=\"".$lienfilm."\" >".$row['titre']." </a></p>";	
    }
}
 }	 
 if($type == 'ebook'){
	 	 	$min_word_lenght = 1; /* taille mini des mots a compter dans la recherche */
$search = explode(' ',$uris);

$request='';
$last_key = end(array_keys($search));

foreach($search as $wordkey => $word){
	if($wordkey === $last_key){
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='ebook' and titre LIKE '%".trim($word)."%' ";
       }
	}else{
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='ebook' and titre LIKE '%".trim($word)."%'  || ";
       }		
	}
}
$sql               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='ebook'");
$result  = $sql->fetch(PDO::FETCH_ASSOC) ;
if ($result['id'] > 0) {
    $sql2               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='ebook'");
		  while($row = $sql2->fetch()) {
      $idfilm = stripslashes($row['id']);
$titrefilm = stripslashes($row['titre']);
$lienfilm = ebookdl($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire Ebook <a href=\"".$lienfilm."\" >".$row['titre']." </a></p>";	
    }
}
 }	
 if($type == 'logiciel'){
	 	 	$min_word_lenght = 1; /* taille mini des mots a compter dans la recherche */
$search = explode(' ',$uris);

$request='';
$last_key = end(array_keys($search));

foreach($search as $wordkey => $word){
	if($wordkey === $last_key){
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='logiciel' and titre LIKE '%".trim($word)."%' ";
       }
	}else{
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='logiciel' and titre LIKE '%".trim($word)."%'  || ";
       }		
	}
}
$sql               = $cnx->query("SELECT * FROM fiche WHERE ".$request."");
$result  = $sql->fetch(PDO::FETCH_ASSOC) ;
if ($result['id'] > 0) {
    $sql2               = $cnx->query("SELECT * FROM fiche WHERE ".$request."");
		  while($row = $sql2->fetch()) {
      $idfilm = stripslashes($row['id']);
$titrefilm = stripslashes($row['titre']);
$lienfilm = logicieldl($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire Logiciel <a href=\"".$lienfilm."\" >".$row['titre']." </a></p>";	
    }
}
 }	 if($type == 'jeux'){
		 	 	$min_word_lenght = 4; /* taille mini des mots a compter dans la recherche */
$search = explode(' ',$uris);

$request='';
$last_key = end(array_keys($search));

foreach($search as $wordkey => $word){
	if($wordkey === $last_key){
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='jeux' and titre LIKE '%".trim($word)."%' ";
       }
	}else{
if(strlen($word) >= $min_word_lenght){
$request .= "categorie='jeux' and titre LIKE '%".trim($word)."%'  || ";
       }		
	}
}
$sql               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='jeux'");
$result  = $sql->fetch(PDO::FETCH_ASSOC) ;
if ($result['id'] > 0) {
    $sql2               = $cnx->query("SELECT * FROM fiche WHERE ".$request." and categorie='jeux'");
		  while($row = $sql2->fetch()) {
      $idfilm = stripslashes($row['id']);
$titrefilm = stripslashes($row['titre']);
$lienfilm = jeuxdl($idfilm,$titrefilm);
$variabletext .= "Peut-être sa que tu désire Jeux <a href=\"".$lienfilm."\" >".$row['titre']." </a></p>";	
    }
}
 }	
 $variablefinal = ''.$variabledeb.''.$variabletext.'</div></div>';
	return $variablefinal;
	}
//drapeau langue episode
function flag($lang){
	global $monsite;
			 if ($lang == 'FR'){
				$flag = '<span class="flagfr" alt="'.$lang.'" title="'.$lang.'"></span>';
				} elseif ($lang == 'VOSTFR'){
					$flag = '<span class="flagvostfr" alt="'.$lang.'" title="'.$lang.'"></span>';
				} elseif ($lang == 'VO'){
					$flag = '<span class="flagvo" alt="'.$lang.'" title="'.$lang.'"></span>';
				} elseif ($lang == ''){
						$flag = '<span class="flagnl" alt="'.$lang.'" title="'.$lang.'"></span>';
				} else {
				$flag = '<span class="flagnl" alt="'.$lang.'" title="'.$lang.'"></span>';
			   }
			   return $flag;
}

function insertfavorie($titre,$section,$lien,$niv) {
		if($niv >= '0')
{
	$pseudo =$_SESSION['fullpseudo'];
		$cnx = Utils::connexionbd();
		$fav = $cnx->query("SELECT * FROM favorie2 WHERE section='".$section."' and liens='".$lien."' and pseudo_du_membre= '".$pseudo."'");
$rec  = $fav->fetch(PDO::FETCH_ASSOC);	
	
		$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';
		if($rec['id'] == '')
{	
$favorie =  '<a href="scripts/inserer-membre.php?id=favoris&titre='.$titre.'&cat='.$section.'&link='.$lien.'" ><img height="26" width="26" src="'.$nom_de_domaine.'/images/share/favo2.png"> Ajouter à mes favoris</a>';		
}else{
	$favorie = '<a href="scripts/deletem.php?id='.$rec['id'].'&type=favo" ><img height="26" width="26" src="'.$nom_de_domaine.'/images/share/favo2.png"> Déja en favori retirer de mes favoris</a>';	
	}}
	 return $favorie;
	}
		
	function favorie($pseudo) {
	$cnx = Utils::connexionbd();
	$nom_de_domaine = '//'.$_SERVER['HTTP_HOST'].'';

	//filmstreaming
	$sql = $cnx->query('SELECT * FROM favorie2 where section = "film" and  pseudo_du_membre="'.$pseudo.'" ORDER BY titre asc');
$if  = $sql->fetch(PDO::FETCH_ASSOC);
if($if){
	$titre = $if['titre'];
echo'<li class="dropdown-submenu">
                  <a tabindex="-1" href="#"><i></i>Films</a>
                  <ul class="dropdown-menu">';
	$titre = $if['titre'];
	$liens = $if['liens'];
		
	$pseudo_du_membre = $if['pseudo_du_membre'];
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
		
while($result = $sql->fetch()) {
		$titre = $result['titre'];
		$liens = $result['liens'];	
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$result['titre']."</a></li>";
}
echo ' </ul>';	
}	


	//series streaming
$sql = $cnx->query('SELECT * FROM favorie2 where pseudo_du_membre="'.$pseudo.'" and section="series" ORDER BY titre asc');
$if  = $sql->fetch(PDO::FETCH_ASSOC);
if($if){
echo'<li class="dropdown-submenu">
                  <a tabindex="-1" href="#"><i></i>Series</a>
                  <ul class="dropdown-menu">';
				  	$titre = $if['titre'];
	$liens = $if['liens'];
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
while($result = $sql->fetch()) {
	$titre = $result['titre'];
	$liens = $result['liens'];
echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
		
}
echo ' </ul>';
}


//ep series 
$sql = $cnx->query('SELECT * FROM favorie2 where section="Ep_series"  and  pseudo_du_membre="'.$pseudo.'" ORDER BY titre asc');
$if  = $sql->fetch(PDO::FETCH_ASSOC);
if($if){
echo'<li class="dropdown-submenu">
                  <a tabindex="-1" href="#"><i></i>EP Series</a>
                  <ul class="dropdown-menu">';
				  	$titre = $if['titre'];
	$liens = $if['liens'];
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
while($result = $sql->fetch()) {
	$titre = $result['titre'];
	$liens = $result['liens'];
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
		
	
	
}
echo ' </ul>';
}


	

//mangas
$sql = $cnx->query('SELECT * FROM favorie2 where section="mangas" and  pseudo_du_membre="'.$pseudo.'" ORDER BY titre asc');

$if  = $sql->fetch(PDO::FETCH_ASSOC);
if($if){
echo'<li class="dropdown-submenu">
                  <a tabindex="-1" href="#"><i></i>Mangas</a>
                  <ul class="dropdown-menu">';
				  	$titre = $if['titre'];
	$liens = $if['liens'];
	
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
while($result = $sql->fetch()) {
	$titre = $result['titre'];
	$liens = $result['liens'];
echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
}
echo ' </ul>';
}
//Ep_mangas
$sql = $cnx->query('SELECT * FROM favorie2 where section="Ep_mangas" and  pseudo_du_membre="'.$pseudo.'" ORDER BY titre asc');
$if  = $sql->fetch(PDO::FETCH_ASSOC);
if($if){
echo'<li class="dropdown-submenu">
                  <a tabindex="-1" href="#"><i></i>EP Mangas stream</a>
                  <ul class="dropdown-menu">';
				  	$titre = $if['titre'];
	$liens = $if['liens'];
	
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
while($result = $sql->fetch()) {
	$titre = $result['titre'];
	$liens = $result['liens'];
echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
		
}
echo ' </ul>';
}
//documentaire 
$sql = $cnx->query('SELECT * FROM favorie2 where section="docu" and  pseudo_du_membre="'.$pseudo.'" ORDER BY titre asc');

$if  = $sql->fetch(PDO::FETCH_ASSOC);
if($if){
echo'<li class="dropdown-submenu">
                  <a tabindex="-1" href="#"><i></i>Documentaires</a>
                  <ul class="dropdown-menu">';
				  	$titre = $if['titre'];
	$liens = $if['liens'];
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
while($result = $sql->fetch()) {
	$titre = $result['titre'];
	$liens = $result['liens'];
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
		
	
	
}
echo ' </ul>';
}//ep_documentaire
$sql = $cnx->query('SELECT * FROM favorie2 where section="ep_docu" and  pseudo_du_membre="'.$pseudo.'" ORDER BY titre asc');

$if  = $sql->fetch(PDO::FETCH_ASSOC);
if($if){
echo'<li class="dropdown-submenu">
                  <a tabindex="-1" href="#"><i></i>Ep docu</a>
                  <ul class="dropdown-menu">';
				  	$titre = $if['titre'];
	$liens = $if['liens'];
		echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
while($result = $sql->fetch()) {
	$titre = $result['titre'];
	$liens = $result['liens'];
echo "<li><a href='".$nom_de_domaine."".$liens."'>".$titre."</a></li>";
		
	
	
}
echo ' </ul>';
}
}

function namesite(){
	$recupername = $_SERVER['HTTP_HOST'];
	$namewebsite1=  extstres22($recupername, ".", ".");
	if(!empty($namewebsite1)&& preg_match('#www.#U', $recupername) or preg_match('#ww1.#U', $recupername)){
	$namewebsite = strtoupper($namewebsite1);
	}else{
		$recupernamez = '@'.$recupername;
		$namewebsite00=  extstres22($recupernamez, "@", ".");
		$namewebsite = strtoupper($namewebsite00);
	}
return $namewebsite;
}
function modifcategorie($id,$niv,$pseudo){
	global $cnx,$nom_de_domaine;
	if($niv >= '3'){
			$stmt = $cnx->query('SELECT * FROM  `forum_categories` WHERE  `id` ='.(int)$id.'');
    $resultopic = $stmt->fetch(PDO::FETCH_ASSOC);
	
				if($resultopic['niveau'] == '0'){ 
				$niveauxx ='<option value="0">Tout le monde</option>';
				}elseif($resultopic['niveau'] == '2'){
				$niveauxx ='<option value="2">Pour ajouteur</option>';
				}elseif($resultopic['niveau'] == '3'){
			$niveauxx ='<option value="2">Pour Modérateur</option>';
			}elseif($resultopic['niveau'] == '4'){
			$niveauxx ='<option value="4">Pour Administrateur</option>';
			}
			
$button = '<a data-toggle="modal" data-target="#modifcategorie'.$id.'" href="#"  ><span class="label label-info">Editer</span></a>


<!-- Modal -->
<div class="modal fade" id="modifcategorie'.$id.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Edition du categorie</h4>
      </div>
      <div class="modal-body">
	   <form class="form-horizontal" name="forum" id="forum"  action="modif.php?type=categorie&id='.$id.'" method="post" >
  
<div class="form-group">
                    <label for="sujet" class="col-lg-2 control-label">Titre categorie</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du categorie" value="'.$resultopic['titre'].'">
                      <input type="hidden" class="form-control" id="id" name="id"  value="'.$resultopic['id'].'">
                    </div>
                  </div>
</br></br>
<div class="form-group">
                    <label for="description" class="col-lg-2 control-label">Description</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="3" id="description" name="description">'.$resultopic['description'].'</textarea>
                    </div>
                  </div>   
				  
				  <div class="form-group">
				        <label for="niveau" class="col-lg-2 control-label">Niveau Autorisé accés</label>
				    <div class="col-lg-10">
 <select class="form-control" name="niveau">
									'.$niveauxx.'
									<option value="0">Membre</option>
										<option value="2">Ajouteur</option>
										<option value="3">Modérateur</option>
										<option value="4">Administrateur</option>
					
										</select>
                      
                    </div>
                  </div> 
				  	   <div class="form-group">
				        <label for="verrou" class="col-lg-2 control-label">Verrouillage catégorie</label>				  
				  <div class="col-lg-10">
 <select class="form-control" name="verrou">
									<option value="0">déverouiller</option>
									<option value="1">vérrouiller</option>
							
										</select>
                      
                    </div>
                  </div> 
</br><div class="form-group">
<div class="col-lg-10"> 
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-primary">Modifier</button>
                    </div> </div></br></br>
 </form></br></br></br>
															
				  </div> </div> </div></div>';
				  
	
	}else{
		$button ='';
	}
				  
	 return $button;
		}
		
		function modiftopic($id,$niv,$pseudo){
	global $cnx,$nom_de_domaine;

			$stmt = $cnx->query('SELECT * FROM  `forum_topic` WHERE  `id` ='.(int)$id.'');
    $resultopic = $stmt->fetch(PDO::FETCH_ASSOC);
		if($pseudo == $resultopic['auteur'] or $niv >= '3'){
				if($resultopic['niveaux'] == '0'){ 
				$niveauxx ='<option value="0">Tout le monde</option>';
				}elseif($resultopic['niveaux'] == '2'){
				$niveauxx ='<option value="2">Pour ajouteur</option>';
				}elseif($resultopic['niveaux'] == '3'){
			$niveauxx ='<option value="2">Pour Modérateur</option>';
			}elseif($resultopic['niveaux'] == '4'){
			$niveauxx ='<option value="4">Pour Administrateur</option>';
			}
			 $bbcodetitre =' <a href="#" class="label label-success"  onclick="insertTag22(\'[b]\',\'[/b]\',\'titre\');">Gras</a>
<a href="#" class="label label-success" onclick="insertTag22(\'[center]\',\'[/center]\',\'titre\');">centrer</a>
<a href="#" class="label label-success" onclick="insertTag22(\'[i]\',\'[/i]\',\'titre\');">Italique</a>
<a href="#" class="label label-success" onclick="insertTag22(\'[img]\',\'[/img]\',\'titre\');">Image</a>
<a href="#" class="label label-success" onclick="insertTag22(\'[u]\',\'[/u]\',\'titre\');">Souligner</a>
<a href="javascript:displaycolor(\'Color_palette\');"  class="label label-success">Couleur police</A>
<a href="javascript:displaycolor(\'taille_police\');"  class="label label-success">Police</A>
<div id="taille_police"style="display:none" >
    <div class="col-lg-3"><select class="form-control" onchange="insertTag22(\'[size=\' + this.options[this.selectedIndex].value + \']\', \'[/size]\', \'titre\');">
                <option value="45">Très petit</option>
                <option value="85">Petit</option>
                <option value="120">Gros</option>
                <option value="160">Très gros</option>
                <option value="200">Très très gros</option>

</select></div></div>
<div id="Color_palette"style="display:none" >
</br></br>
<a href="#"  onclick="insertTag22(\'[color=red]\',\'[/color]\',\'titre\');">
<span style="background-color:red">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="rouge" title="rouge">
</span></a>
<a href="#"  onclick="insertTag22(\'[color=blue]\',\'[/color]\',\'titre\');">
<span style="background-color:blue">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="bleu" title="bleu">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=orange]\',\'[/color]\',\'titre\');">
<span style="background-color:orange">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="orange" title="orange">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=yellow]\',\'[/color]\',\'titre\');">
<span style="background-color:yellow">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Jaune" title="Jaune">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=green]\',\'[/color]\',\'titre\');">
<span style="background-color:green">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Vert" title="Vert">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=pink]\',\'[/color]\',\'titre\');">
<span style="background-color:pink">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="rose" title="rose">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=#BD8D46]\',\'[/color]\',\'titre\');">
<span style="background-color:#BD8D46">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Maron" title="Maron">
</span></a>
<a href="#"  onclick="insertTag22(\'[color=#9E40A4]\',\'[/color]\',\'titre\');">
<span style="background-color:#9E40A4">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="violet" title="violet">
</span></a>
</div>
</p> '; 

$bbcodetextera =' <a href="#" class="label label-success"  onclick="insertTag(\'[b]\',\'[/b]\',\'description\');">Gras</a>
<a href="#" class="label label-success" onclick="insertTag(\'[center]\',\'[/center]\',\'description\');">centrer</a>
<a href="#" class="label label-success" onclick="insertTag(\'[i]\',\'[/i]\',\'description\');">Italique</a>
<a href="#" class="label label-success" onclick="insertTag(\'[img]\',\'[/img]\',\'description\');">Image</a>
<a href="#" class="label label-success" onclick="insertTag(\'[u]\',\'[/u]\',\'description\');">Souligner</a>
<a href="javascript:displaycolor(\'Color_palette2\');"  class="label label-success">Couleur police</A>
<a href="javascript:displaycolor(\'taille_police2\');"  class="label label-success">Police</A>
<div id="taille_police2" style="display:none" >
    <div class="col-lg-3"><select class="form-control" onchange="insertTag(\'[size=\' + this.options[this.selectedIndex].value + \']\', \'[/size]\', \'description\');">
                <option value="45">Très petit</option>
                <option value="85">Petit</option>
                <option value="120">Gros</option>
                <option value="160">Très gros</option>
                <option value="200">Très très gros</option>

</select></div></div>
<div id="Color_palette2"style="display:none" >
</br></br>
<a href="#"  onclick="insertTag(\'[color=red]\',\'[/color]\',\'description\');">
<span style="background-color:red">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="rouge" title="rouge">
</span></a>
<a href="#"  onclick="insertTag(\'[color=blue]\',\'[/color]\',\'description\');">
<span style="background-color:blue">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="bleu" title="bleu">
</span></a>

<a href="#"  onclick="insertTag(\'[color=orange]\',\'[/color]\',\'description\');">
<span style="background-color:orange">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="orange" title="orange">
</span></a>

<a href="#"  onclick="insertTag(\'[color=yellow]\',\'[/color]\',\'description\');">
<span style="background-color:yellow">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Jaune" title="Jaune">
</span></a>

<a href="#"  onclick="insertTag(\'[color=green]\',\'[/color]\',\'description\');">
<span style="background-color:green">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Vert" title="Vert">
</span></a>

<a href="#"  onclick="insertTag(\'[color=pink]\',\'[/color]\',\'description\');">
<span style="background-color:pink">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="rose" title="rose">
</span></a>

<a href="#"  onclick="insertTag(\'[color=#BD8D46]\',\'[/color]\',\'description\');">
<span style="background-color:#BD8D46">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Maron" title="Maron">
</span></a>
<a href="#"  onclick="insertTag(\'[color=#9E40A4]\',\'[/color]\',\'description\');">
<span style="background-color:#9E40A4">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="violet" title="violet">
</span></a>
</div>
</p> ';
$button = '<a data-toggle="modal" data-target="#modiftopic'.$id.'" href="#"  ><span class="label label-info">Editer</span></a>


<!-- Modal -->
<div class="modal fade" id="modiftopic'.$id.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Edition du topic</h4>
      </div>
      <div class="modal-body">
	   <form class="form-horizontal" name="forum" id="forum"  action="modif.php?type=topic&id='.$id.'" method="post" >
  
<div class="form-group">
                    <label for="sujet" class="col-lg-2 control-label">Titre topic</label>
                    <div class="col-lg-10">
                     '. $bbcodetitre.'
                      <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du topic" value="'.$resultopic['titre'].'">
                      <input type="hidden" class="form-control" id="id" name="id"  value="'.$resultopic['id'].'">
                    </div>
                  </div>
</br></br>
<div class="form-group">
                    <label for="description" class="col-lg-2 control-label">Description</label>
                    <div class="col-lg-10">
					'. $bbcodetextera.'
                      <textarea class="form-control" rows="3" id="description" name="description">'.$resultopic['text'].'</textarea>
                    </div>
                  </div>   
				  
				  <div class="form-group">
				        <label for="niveau" class="col-lg-2 control-label">Niveau Autorisé accés</label>
				    <div class="col-lg-10">
 <select class="form-control" name="niveau">
									'.$niveauxx.'
									<option value="0">Membre</option>
										<option value="2">Ajouteur</option>
										<option value="3">Modérateur</option>
										<option value="4">Administrateur</option>
					
										</select>
                      
                    </div>
                  </div> 
				  	   <div class="form-group">
				        <label for="verrou" class="col-lg-2 control-label">Verrouillage du topic</label>				  
				  <div class="col-lg-10">
 <select class="form-control" name="verrou">
									<option value="0">déverouiller</option>
									<option value="1">vérrouiller</option>
							
										</select>
                      
                    </div>
                  </div> 
</br><div class="form-group">
<div class="col-lg-10"> 
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-primary">Modifier</button>
                    </div> </div></br></br>
 </form></br></br></br>
															
				  </div> </div> </div></div>';
				  
	}else{
		$button ='';
	}
	
				  
	 return $button;
		}
		function modifsujet($id,$niv,$pseudo){
	global $cnx,$nom_de_domaine;

			$stmt = $cnx->query('SELECT * FROM  `forum_sujets` WHERE  `id` ='.(int)$id.'');
    $resultopic = $stmt->fetch(PDO::FETCH_ASSOC);
		if($pseudo == $resultopic['auteur'] or $niv >= '3' ){
				if($resultopic['niveau'] == '0'){ 
				$niveauxx ='<option value="0">Tout le monde</option>';
				}elseif($resultopic['niveau'] == '2'){
				$niveauxx ='<option value="2">Pour ajouteur</option>';
				}elseif($resultopic['niveau'] == '3'){
			$niveauxx ='<option value="2">Pour Modérateur</option>';
			}elseif($resultopic['niveau'] == '4'){
			$niveauxx ='<option value="4">Pour Administrateur</option>';
			}
			 $bbcodetitre =' <a href="#" class="label label-success"  onclick="insertTag22(\'[b]\',\'[/b]\',\'titre\');">Gras</a>
<a href="#" class="label label-success" onclick="insertTag22(\'[center]\',\'[/center]\',\'titre\');">centrer</a>
<a href="#" class="label label-success" onclick="insertTag22(\'[i]\',\'[/i]\',\'titre\');">Italique</a>
<a href="#" class="label label-success" onclick="insertTag22(\'[img]\',\'[/img]\',\'titre\');">Image</a>
<a href="#" class="label label-success" onclick="insertTag22(\'[u]\',\'[/u]\',\'titre\');">Souligner</a>
<a href="javascript:displaycolor(\'Color_palette\');"  class="label label-success">Couleur police</A>
<a href="javascript:displaycolor(\'taille_police\');"  class="label label-success">Police</A>
<div id="taille_police"style="display:none" >
    <div class="col-lg-3"><select class="form-control" onchange="insertTag22(\'[size=\' + this.options[this.selectedIndex].value + \']\', \'[/size]\', \'titre\');">
                <option value="45">Très petit</option>
                <option value="85">Petit</option>
                <option value="120">Gros</option>
                <option value="160">Très gros</option>
                <option value="200">Très très gros</option>

</select></div></div>
<div id="Color_palette"style="display:none" >
</br></br>
<a href="#"  onclick="insertTag22(\'[color=red]\',\'[/color]\',\'titre\');">
<span style="background-color:red">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="rouge" title="rouge">
</span></a>
<a href="#"  onclick="insertTag22(\'[color=blue]\',\'[/color]\',\'titre\');">
<span style="background-color:blue">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="bleu" title="bleu">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=orange]\',\'[/color]\',\'titre\');">
<span style="background-color:orange">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="orange" title="orange">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=yellow]\',\'[/color]\',\'titre\');">
<span style="background-color:yellow">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Jaune" title="Jaune">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=green]\',\'[/color]\',\'titre\');">
<span style="background-color:green">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Vert" title="Vert">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=pink]\',\'[/color]\',\'titre\');">
<span style="background-color:pink">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="rose" title="rose">
</span></a>

<a href="#"  onclick="insertTag22(\'[color=#BD8D46]\',\'[/color]\',\'titre\');">
<span style="background-color:#BD8D46">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Maron" title="Maron">
</span></a>
<a href="#"  onclick="insertTag22(\'[color=#9E40A4]\',\'[/color]\',\'titre\');">
<span style="background-color:#9E40A4">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="violet" title="violet">
</span></a>
</div>
</p> '; 

$bbcodetextera =' <a href="#" class="label label-success"  onclick="insertTag(\'[b]\',\'[/b]\',\'description\');">Gras</a>
<a href="#" class="label label-success" onclick="insertTag(\'[center]\',\'[/center]\',\'description\');">centrer</a>
<a href="#" class="label label-success" onclick="insertTag(\'[i]\',\'[/i]\',\'description\');">Italique</a>
<a href="#" class="label label-success" onclick="insertTag(\'[img]\',\'[/img]\',\'description\');">Image</a>
<a href="#" class="label label-success" onclick="insertTag(\'[u]\',\'[/u]\',\'description\');">Souligner</a>
<a href="javascript:displaycolor(\'Color_palette2\');"  class="label label-success">Couleur police</A>
<a href="javascript:displaycolor(\'taille_police2\');"  class="label label-success">Police</A>
<div id="taille_police2" style="display:none" >
    <div class="col-lg-3"><select class="form-control" onchange="insertTag(\'[size=\' + this.options[this.selectedIndex].value + \']\', \'[/size]\', \'description\');">
                <option value="45">Très petit</option>
                <option value="85">Petit</option>
                <option value="120">Gros</option>
                <option value="160">Très gros</option>
                <option value="200">Très très gros</option>

</select></div></div>
<div id="Color_palette2"style="display:none" >
</br></br>
<a href="#"  onclick="insertTag(\'[color=red]\',\'[/color]\',\'description\');">
<span style="background-color:red">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="rouge" title="rouge">
</span></a>
<a href="#"  onclick="insertTag(\'[color=blue]\',\'[/color]\',\'description\');">
<span style="background-color:blue">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="bleu" title="bleu">
</span></a>

<a href="#"  onclick="insertTag(\'[color=orange]\',\'[/color]\',\'description\');">
<span style="background-color:orange">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="orange" title="orange">
</span></a>

<a href="#"  onclick="insertTag(\'[color=yellow]\',\'[/color]\',\'description\');">
<span style="background-color:yellow">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Jaune" title="Jaune">
</span></a>

<a href="#"  onclick="insertTag(\'[color=green]\',\'[/color]\',\'description\');">
<span style="background-color:green">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Vert" title="Vert">
</span></a>

<a href="#"  onclick="insertTag(\'[color=pink]\',\'[/color]\',\'description\');">
<span style="background-color:pink">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="rose" title="rose">
</span></a>

<a href="#"  onclick="insertTag(\'[color=#BD8D46]\',\'[/color]\',\'description\');">
<span style="background-color:#BD8D46">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="Maron" title="Maron">
</span></a>
<a href="#"  onclick="insertTag(\'[color=#9E40A4]\',\'[/color]\',\'description\');">
<span style="background-color:#9E40A4">
<img src="//'.$nom_de_domaine.'/forum/images/spacer.gif" width="15" height="10" alt="violet" title="violet">
</span></a>
</div>
</p> ';
$button = '<a data-toggle="modal" data-target="#modifsujet'.$id.'" href="#"  ><span class="label label-info">Editer</span></a>


<!-- Modal -->
<div class="modal fade" id="modifsujet'.$id.'" tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Edition du sujet</h4>
      </div>
      <div class="modal-body">
	   <form class="form-horizontal" name="forum" id="forum"  action="modif.php?type=sujet&id='.$id.'" method="post" >
  
<div class="form-group">
                    <label for="sujet" class="col-lg-2 control-label">Titre sujet</label>
                    <div class="col-lg-10">
                     '. $bbcodetitre.'
                      <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du sujet" value="'.$resultopic['titre'].'">
                      <input type="hidden" class="form-control" id="id" name="id"  value="'.$resultopic['id'].'">
                    </div>
                  </div>
</br></br>
<div class="form-group">
                    <label for="description" class="col-lg-2 control-label">Description</label>
                    <div class="col-lg-10">
					'. $bbcodetextera.'
                      <textarea class="form-control" rows="3" id="description" name="description">'.$resultopic['description'].'</textarea>
                    </div>
                  </div>   
				  
				  <div class="form-group">
				        <label for="niveau" class="col-lg-2 control-label">Niveau Autorisé accés</label>
				    <div class="col-lg-10">
 <select class="form-control" name="niveau">
									'.$niveauxx.'
									<option value="0">Membre</option>
										<option value="2">Ajouteur</option>
										<option value="3">Modérateur</option>
										<option value="4">Administrateur</option>
					
										</select>
                      
                    </div>
                  </div> 
				  	   <div class="form-group">
				        <label for="verrou" class="col-lg-2 control-label">Verrouillage du sujet</label>				  
				  <div class="col-lg-10">
 <select class="form-control" name="verrou">
									<option value="0">déverouiller</option>
									<option value="1">vérrouiller</option>
							
										</select>
                      
                    </div>
                  </div> 
</br><div class="form-group">
<div class="col-lg-10"> 
                      <button type="reset" class="btn btn-default">Annuler</button>
                      <button type="submit" class="btn btn-primary">Modifier</button>
                    </div> </div></br></br>
 </form></br></br></br>
															
				  </div> </div> </div></div>';
				  
	
	}else{
		$button ='';
	}
				  
	 return $button;
		}
		
	function gethostphone($host){
			$cnx = Utils::connexionbd();
		 	$qu = $cnx->query("SELECT nom FROM hebergeurs WHERE nom like '".$host."' LIMIT 1");
    $row                = $qu->fetch(PDO::FETCH_ASSOC);
if(!detectmobile()){
	 $nom = $row['nom'];
	 }else{
		 $namee = $row['nom'];
		$nom = "<img   src='//www.google.com/s2/favicons?domain=' style='text-align:left;' width='28px' height='28px' title='".$namee."'  alt='".$namee."'>";
	 }
	 return trim($nom);
}			

function gethostphone2($host,$lien){
			global $cnx;
				$recupername = $host;
	$namewebsite1=  extstres22($recupername, ".", ".");
	$namewebsite = strtoupper($namewebsite1);
		 	$qu = $cnx->query("SELECT nom FROM hebergeurs WHERE nom like '".$host."' LIMIT 1");
    $row                = $qu->fetch(PDO::FETCH_ASSOC);
if(!detectmobile()){
	 $nom = $row['nom'];
	 }else{
		 $namee = $row['nom'];
		 $host =	parse_url($lien,PHP_URL_HOST );
		$nom = "<img   src='//www.google.com/s2/favicons?domain=".$host."' style='text-align:left;' width='20px' height='20px' title='".$namee."'  alt='".$namee."'>";
	 }
	 return trim($nom);
}		
function gethostphone3($host,$lien){
			global $cnx;
				$recupername = $lien;
				

	//$namewebsite = strtoupper($namewebsite1);
			 $namewebsite1 = str_replace('hqq','netu',$lien);
		 $namewebsite =	parse_url($namewebsite1,PHP_URL_HOST );
		 	$qu = $cnx->query("SELECT nom FROM hebergeurs WHERE domaine like '".$namewebsite."' LIMIT 1");
    $row                = $qu->fetch(PDO::FETCH_ASSOC);
if(!detectmobile()){
	 $nom = $row['nom'];
	 }else{
		 $namee = $row['nom'];
		 $host =	parse_url($lien,PHP_URL_HOST );
		$nom = "<img   src='//www.google.com/s2/favicons?domain=".$host."' style='text-align:left;' width='20px' height='20px' title='".$namee."'  alt='".$namee."'>";
	 }
	  $host =	parse_url($lien,PHP_URL_HOST );
	 // echo $nom;
	 return trim($nom);
}		
function online($valeur){
	if($valeur == '0'){
		$vpic = '<img src="//'.$_SERVER['HTTP_HOST'].'/img/en-ligne.png" width="17" height="17">';
	}else{
		
		$vpic = '<img src="//'.$_SERVER['HTTP_HOST'].'/img/hors-ligne.png" width="17" height="17">';
	}
	
	return $vpic;
}

 function ban($ip){
	 global $cnx;
	$requete               = $cnx->query("SELECT * FROM ban WHERE ip LIKE '".md5($ip)."'");
$result3  = $requete->fetch(PDO::FETCH_ASSOC);
if(!empty($result3['id'])){
	
 $vpic= header("Location: ../500.php"); 
}
	
	return $vpic;
} 
	 function embrouille($long_pass)
{
$consonnes = "bcdfghjklmnpqrstvwxz";
$voyelles = "aeiouy123456789";
$mdp='';
for ($i=0; $i < $long_pass; $i++)
{
if (($i % 2) == 0)
{
$mdp = $mdp.substr ($voyelles, rand(0,strlen($voyelles)-1), 1);
}
else
{
$mdp = $mdp.substr ($consonnes, rand(0,strlen($consonnes)-1), 1);
}
}

return $mdp;
}

 function alphabet($getlettre,$getpage,$categorie,$getorder){
	global $nom_de_domaine;
 $cpt=65;
while($cpt<91){
	if(isset($getpage)){
$page = addslashes($getpage);

} 
else {
$page = "1";
	
}
// if(!empty($getorder)){$order=

if(empty($getorder)){$getorder='asc';}

$lettrea = '<a class="btn btn-default" href="liste-'.$categorie.'-page-'.$page.'-lettre-'.chr($cpt).'-order-'.$getorder.'.html">'.chr($cpt).'</a> ';

if ($getlettre == chr($cpt)) { $lettrea = '<a class="btn btn-default active disabled" href="#">'.chr($cpt).'</a> ';}

	echo $lettrea;
 
  $cpt++;
}
}

function paginationcoms($titre,$total_pages,$getpage,$cat){
$adjacents = 5;
	$limit = 14; 								//how many items to show per page
	$page = $getpage;
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
 	/* Get data. */
//	$result = $cnx->query("SELECt * FROM media WHERE active=1 and type=2 ORDER BY id DESC LIMIT $start, $limit");

	//$result = $mysqli->query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "";
		//previous button
		if ($page > 1) 
			$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$prev\"><< Préc</a></li>";
		else
			$pagination.= "";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li class=\"active\"><a  class='page-link' href=\"#\">$counter</a></li>";
				else
					$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$counter\">$counter</a></li>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a  class='page-link' href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$counter\">$counter</a></li>";					
				}
								$pagination.= "<li class=\"disabled\"><a  class='page-link' href=\"#\">...</A></li>";
				$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$lastpage\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=1\">1</a></li>";
				$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=2\">2</a></li>";
								$pagination.= "<li class=\"disabled\"><a  class='page-link' href=\"#\">...</A></li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a  class='page-link' href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$counter\">$counter</a></li>";					
				}
							$pagination.= "<li class=\"disabled\"><a  class='page-link' href=\"#\">...</A></li>";
				$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$lastpage\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=1\">1</a></li>";
				$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=2\">2</a></li>";
						$pagination.= "<li class=\"disabled\"><a  class='page-link' href=\"#\">...</A></li>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a  class='page-link' href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a  class='page-link' href=\"commentaire.php?titre=$titre&cat=$cat&page=$counter\">$counter</a></li>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li><a  class='page-link'  href=\"commentaire.php?titre=$titre&cat=$cat&page=$next\">Suiv >></a></li>";
		else
			$pagination.= "";
		$pagination.= "";		
	}
	return $pagination;
 }
 
 function paginationliste($categorie,$total_pages,$getpage,$getorder,$argument){
$adjacents = 5;
	$limit = 14; 								//how many items to show per page
	$page = $getpage;
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
 	/* Get data. */
//	$result = $cnx->query("SELECt * FROM media WHERE active=1 and type=2 ORDER BY id DESC LIMIT $start, $limit");

	//$result = $mysqli->query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "";
		//previous button
		if ($page > 1) 
			$pagination.= "<li><a  href=\"liste-$categorie-page-$prev-$argument-order-$getorder.html\"><< Préc</a></li>";
		else
			$pagination.= "";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
				else
					$pagination.= "<li><a href=\"liste-$categorie-page-$counter-$argument-order-$getorder.html\">$counter</a></li>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"liste-$categorie-page-$counter-$argument-order-$getorder.html\">$counter</a></li>";					
				}
								$pagination.= "<li class=\"disabled\"><a href=\"#\">...</A></li>";
				$pagination.= "<li><a href=\"liste-$categorie-page-$lpm1-$argument-order-$getorder.html\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"liste-$categorie-page-$lastpage-$argument-order-$getorder.html\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li><a href=\"liste-$categorie-page-1-$argument-order-$getorder.html\">1</a></li>";
				$pagination.= "<li><a href=\"liste-$categorie-page-2-$argument-order-$getorder.html\">2</a></li>";
								$pagination.= "<li class=\"disabled\"><a href=\"#\">...</A></li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"liste-$categorie-page-$counter-$argument-order-$getorder.html\">$counter</a></li>";					
				}
							$pagination.= "<li class=\"disabled\"><a href=\"#\">...</A></li>";
				$pagination.= "<li><a href=\"liste-$categorie-page-$lpm1-$argument-order-$getorder.html\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"liste-$categorie-page-$lastpage-$argument-order-$getorder.html\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li><a href=\"liste-$categorie-page-1-$argument-order-$getorder.html\">1</a></li>";
				$pagination.= "<li><a href=\"liste-$categorie-page-2-$argument-order-$getorder.html\">2</a></li>";
						$pagination.= "<li class=\"disabled\"><a href=\"#\">...</A></li>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"liste-$categorie-page-$counter-$argument-order-$getorder.html\">$counter</a></li>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li><a href=\"liste-$categorie-page-$next-$argument-order-$getorder.html\">Suiv >></a></li>";
		else
			$pagination.= "";
		$pagination.= "";		
	}
	return $pagination;
 }
 
 
function paginationsearch($search,$categorie,$total_pages,$getpage,$getorder,$argument){
$adjacents = 5;
	$limit = 14; 								//how many items to show per page
	$page = $getpage;
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
 	/* Get data. */
//	$result = $cnx->query("SELECt * FROM media WHERE active=1 and type=2 ORDER BY id DESC LIMIT $start, $limit");

	//$result = $mysqli->query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "";
		//previous button
		if ($page > 1) 
			$pagination.= "<li><a  href=\"search-titre-$search-$categorie-page-$prev-$argument-order-$getorder.html\"><< Préc</a></li>";
		else
			$pagination.= "";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
				else
					$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$counter-$argument-order-$getorder.html\">$counter</a></li>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$counter-$argument-order-$getorder.html\">$counter</a></li>";					
				}
								$pagination.= "<li class=\"disabled\"><a href=\"#\">...</A></li>";
				$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$lpm1-$argument-order-$getorder.html\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$lastpage-$argument-order-$getorder.html\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-1-$argument-order-$getorder.html\">1</a></li>";
				$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-2-$argument-order-$getorder.html\">2</a></li>";
								$pagination.= "<li class=\"disabled\"><a href=\"#\">...</A></li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$counter-$argument-order-$getorder.html\">$counter</a></li>";					
				}
							$pagination.= "<li class=\"disabled\"><a href=\"#\">...</A></li>";
				$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$lpm1-$argument-order-$getorder.html\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$lastpage-$argument-order-$getorder.html\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-1-$argument-order-$getorder.html\">1</a></li>";
				$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-2-$argument-order-$getorder.html\">2</a></li>";
						$pagination.= "<li class=\"disabled\"><a href=\"#\">...</A></li>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$counter-$argument-order-$getorder.html\">$counter</a></li>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li><a href=\"search-titre-$search-$categorie-page-$next-$argument-order-$getorder.html\">Suiv >></a></li>";
		else
			$pagination.= "";
		$pagination.= "";		
	}
	return $pagination;
 }
 
 
function categoryrewritt($id,$titre,$category){
	$titre2 = utf8_decode($titre);
	$titre3 = removeaccents($titre2);
	$titre1 = strtolower($titre3);
	if($category =='film'){
			$lien = filmrewriteunique($id,$titre1);
	}elseif($category =='serie' or $category =='documentaire'){
	$lien = seriesrewriteunique($id,$titre1);
		}elseif($category =='mangas'){
			$lien = manga2rewriteunique($id,$titre1);
		}
		return $lien;
}	

function bande($url){
	$result1 = $url;
 $result1 = str_replace('www.dailymotion.com/video/','www.dailymotion.com/embed/video/',$result1);
 $result1 = str_replace('//www.allocine.fr/video/player_gen_cmedia=','//www.allocine.fr/_video/iblogvision.aspx?cmedia=',$result1); $result1 = str_replace('//www.allocine.fr/video/player_gen_cmedia=','//www.allocine.fr/_video/iblogvision.aspx?cmedia=',$result1); $result1 = str_replace('//www.allocine.fr/blogvision/','//www.allocine.fr/_video/iblogvision.aspx?cmedia=',$result1); $result1 = str_replace('//www.youtube.com/watch?v=','//www.youtube.com/embed/',$result1); $result1 = str_replace('https://www.youtube.com/watch?v=','//www.youtube.com/embed/',$result1);$result1 = str_replace('www.dailymotion.com/video/','www.dailymotion.com/embed/video/',$result1);$result1 = str_replace('https://www.youtube.com/watch?v=','//www.youtube.com/embed/',$result1);  
 if(!empty($url)){$result= $result1;}else{$result= '';}
 
 return $result;
}
function langage($fr,$us){
	 $langue = $_SERVER['HTTP_ACCEPT_LANGUAGE']; 
 if(preg_match('`fr`i', $langue))
     { 
	 $variable = $fr;

    } else 
     { 
	 $variable = $us;
	 }
	 return $variable;
}
function logout($valeur){
	global $cnx;
	if(!empty($valeur)&& $valeur=='1'){
		$cnx->query('DELETE FROM membres_connectes WHERE pseudo="'.$_SESSION['fullpseudo'].'"');
					 setcookie("fullsharezuser", '', time() - 31536000,"/"); 
session_unset();
session_destroy();
$retour = header('Location: ../login.html');
	}else{$retour = '';}
return $retour;
	}
	function logout2(){
 setcookie("fullsharezuser", '', time() - 31536000,"/"); 
session_unset();
session_destroy();
return $retour;
	}
	
	function resizeiframe(){
	if(!detectmobile()){
				$retour = ' width="800px" height="480"  style="width: 80%;" ';
	}else{
			$retour = ' width="320px" height="220px" ';
			}
return $retour;
	}
	function pub($type){
		global $cnx;
		$retourpub ='';
				 $stmt22 = $cnx->query('SELECT * FROM setting');
    $pubcc = $stmt22->fetch(PDO::FETCH_ASSOC);
		if($type =='468'){
				$pub = $pubcc['ban_468'];
		}elseif($type =='728'){
			$pub = $pubcc['ban_728'];
			}elseif($type =='pop'){
			$pub = $pubcc['popup'];
			}else{
				$pub ='';
			}

	if($pubcc['pubactivation']=='1' && $pubcc['pubactiveban']=='1'){
		$pubretour = '<center>'.$pub.'</center><br>';
	$retourpub = $pubretour;
	}
								return $retourpub;
	}
function emoji($valeur){
	$emoji =$valeur;
 $emoji = str_replace('<img height=24 width=26 src="http://www.fullsharez.com/forum/images/smilies/icon_e_surprised.gif">','<span class="emojione-24-people _1f62f emojis__list__e1"  alt="Choqué" title="Choqué"></span>',$emoji);
 $emoji = str_replace('<img height=24 width=26 src="http://www.fullsharez.com/forum/images/smilies/icon_e_biggrin.gif">','<span class="emojione-24-people _1f603 emojis__list__e1" alt="mdr" title="mdr"></span>',$emoji);
  $emoji = str_replace('<img height=55 width=60 src="http://www.fullsharez.com/images/smilies/pensif_28.gif">','<span class="emojione-24-people _1f914 emojis__list__e1" alt="pensif" title="pensif"></span>',$emoji);
   $emoji = str_replace('<img height=24 width=26 src="http://www.fullsharez.com/forum/images/smilies/icon_e_sad.gif">','<span class="emojione-24-people _2639 emojis__list__e1" alt="triste" title="triste"></span>',$emoji);
   $emoji = str_replace('<img height=37 width=39 src="http://www.fullsharez.com/images/smilies/Colere_1.gif">','<span class="emojione-24-people _1f621 emojis__list__e1" alt="en colère" title="en colère"></span>',$emoji);
   $emoji = str_replace('<img height=55 width=60 src="http://www.fullsharez.com/images/smilies/Colere_53.gif">','<span class="emojione-24-people _1f92c emojis__list__e1" alt="grosse colère" title="grosse colère"></span>',$emoji);
   $emoji = str_replace('<img height=30 width=30 src="http://www.fullsharez.com/forum/images/smilies/icon_lol.gif">','<span class="emojione-24-people _1f602 emojis__list__e1" alt="lol" title="lol"></span>',$emoji);
   $emoji = str_replace('<img height=55 width=60 src="http://www.fullsharez.com/images/smilies/Content_3.gif">','<span class="emojione-24-people _1f604 emojis__list__e1"></span>',$emoji); 
   $emoji = str_replace('<img height=17 width=19 src="http://www.fullsharez.com/forum/images/smilies/icon_cry.gif">','<span class="emojione-24-people _1f622 emojis__list__e1"></span>',$emoji); 
   $emoji = str_replace('<img height=17 width=19 src="http://www.fullsharez.com/forum/images/smilies/icon_e_wink.gif">','<span class="emojione-24-people _1f609 emojis__list__e1"></span>',$emoji);
   $emoji = str_replace('<img height=24 width=26 src="http://www.fullsharez.com/forum/images/smilies/icon_e_confused.gif">','<span class="emojione-24-people _1f615 emojis__list__e1"  alt="confu" title="confu"></span>',$emoji);
   $emoji = str_replace('<img height=50 width=50 src="http://www.fullsharez.com/images/smilies/Content_26.gif">','<span class="emojione-24-people _1f44d emojis__list__e1" alt="very good" title="very good"></span>',$emoji);
   $emoji = str_replace('<img height=24 width=26 src="http://www.fullsharez.com/forum/images/smilies/icon_redface.gif">','<span class="emojione-24-people _1f92d emojis__list__e1"  alt="rougie" title="rougie"></span>',$emoji);
 return $emoji;
}

 function bytitre($titrexy) {
global $cnx;
$titrez = utf8allocine($titrexy);
// echo ($titrez);
if(!empty($titrez)){
	$valeur='1';
	$result1 = '';
	//$sqlfiche = "SELECT * FROM  `fiche`  WHERE titre like '".addslashes($titrez)."'";
	$sqlfiche = "SELECT * FROM `fiche` WHERE 1 and `titre` like '".addslashes($titrez)."'";
	// echo $sqlfiche;
	$stmtvv = $cnx->prepare($sqlfiche);
$stmtvv->execute();
// $stmtvv               = $cnx->query($sqlfiche);$stmtvv               = $cnx->query($sqlfiche);
if ($result1 = $stmtvv->fetch()) {
	$valeur='0';}else{$valeur='1';}
}
 return $valeur;
  }        
 function bytitre2($titrexy,$type) {
global $cnx;
$titrez = utf8allocine($titrexy);
// echo ($titrez);
if(!empty($titrez)){
	$valeur='1';
	$result1 = '';
	//$sqlfiche = "SELECT * FROM  `fiche`  WHERE titre like '".addslashes($titrez)."'";
	$sqlfiche = "SELECT * FROM `fiche` WHERE 1 and `titre` like '".addslashes($titrez)."' and categorie like '".$type."'";
	// echo $sqlfiche;
	$stmtvv = $cnx->prepare($sqlfiche);
$stmtvv->execute();
// $stmtvv               = $cnx->query($sqlfiche);$stmtvv               = $cnx->query($sqlfiche);
if ($result1 = $stmtvv->fetch()) {
	$valeur='0';}else{$valeur='1';}
}
 return $valeur;
  }
function error($code,$msg1){
		$error='';	$msg='';
		if(!empty($msg1)){$msg = urldecode(strip_tags(($msg1)));}
	if($code == '1'){
		
		//red erreur
$error = '<div class="alert alert-error">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Error!</strong>'.($msg).'.
	</div>';
	}	
	
	if($code == '2'){
		//warning orange
$error = '<div class="alert">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Warning!</strong> '.($msg).'.
	</div>';
	}
	
	if($code == '3'){
		//info bleu
$error = '<div class="alert alert-info">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Info!</strong> '.($msg).'.
	</div>';
	}

	if($code == '4'){
$error = '<div class="alert alert-success">
										<button class="close" data-dismiss="alert">×</button>
										<strong>Success!</strong> Requête éffectué avec succés.
									</div>';
	}
	
	return $error;
}
function url_exists($url_a_tester)
{
$F=@fopen($url_a_tester,"r");

if($F)
{
 fclose($F);
 return true;
}
else return false;

} // fin de la fonction
			
 ?>