<?php include('header.php');?>
 

    <!--

        content



    -->   



    <div class="container-fluid">



   

    <div class="row">

    <!-- 

        colonne central

    -->

      <div class="col-12 col-sm-3  border-right border-light ">
<aside>
          

         <h2>Exercice javascript</h2>

         <p>mes divers exercices javascript</p>

          <?php
 function listing($repertoire){

    $fichier = array();

    if (is_dir($repertoire)){

    $dir = opendir($repertoire); //ouvre le repertoire courant d�sign� par la variable

    while(false!==($file = readdir($dir))){ //on lit tout et on r�cupere tout les fichiers dans $file

    if(!in_array($file, array('.','..'))){ //on eleve le parent et le courant '. et ..'

    $page = $file; //sort l'extension du fichier

    $page = explode('.', $page);

    $nb = count($page);

    $nom_fichier = $page[0];

    for ($i = 1; $i < $nb-1; $i++){

    $nom_fichier .= '.'.$page[$i];

    }

    if(isset($page[1])){

    $ext_fichier = $page[$nb-1];

    if(!is_file($file)) { $file = '/'.$file; }

    }

    else {

    if(!is_file($file)) { $file = '/'.$file; } //on rajoute un "/" devant les dossier pour qu'ils soient tri�s au d�but

    $ext_fichier = '';

    }

    if($ext_fichier == 'html' ) { //utile pour exclure certains types de fichiers � ne pas lister

    array_push($fichier, $file);

    }

    }

    }

    }

    natcasesort($fichier); //la fonction natcasesort( ) est la fonction de tri standard sauf qu'elle ignore la casse

    foreach($fichier as  $lineNumber => $value) {

    $lineNumber1 = $lineNumber + 1;

    $name = str_replace ('/', '', $value);  
	$name = str_replace ('.html', '', $name);
	$name = str_replace ('-', ' ', $name);

      echo '<p><a href="http://'.$_SERVER['HTTP_HOST'].'/'.rawurlencode($repertoire).'/'.rawurlencode(str_replace ('/', '', $value)).'" target="Player"  id="play'.$lineNumber1.'" ">'.$name.'</a></p><br />';

    }

    }

    //exemple d'utilisation :

    listing('exercice'); //chemin du dossier

   

   ?>
   </aside> </div>
	

<div class="col-12 col-sm-9 contenu" id="iframe">
  <article>
<h2>Vu du contenu appelé</h2>
<div class="ardoise">
<iframe name="Player" id="Player" scrolling="yes" frameborder="0" height="450px" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"  width="100%"></iframe>
 </div>
 </article>
 </div>
 

    </div>

</div> 

    <!--

        Footer

    -->
<?php include('footer.php');?>