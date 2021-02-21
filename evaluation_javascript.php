<?php include('header.php');?>

    <!--

        content



    -->   



    <div class="container-fluid">



   

    <div class="row">

    <!-- 

        colonne central

    -->

      <div class="col-12 col-sm-3 border-right border-light ">
   <aside>
         

         <h2>Evaluation javascript</h2>

         <p>Mes Evaluations javascript</p>

          <?php

         

    function listing($repertoire){

    $fichier = array();

    if (is_dir($repertoire)){

    $dir = opendir($repertoire); //ouvre le repertoire courant d?sign? par la variable

    while(false!==($file = readdir($dir))){ //on lit tout et on r?cupere tout les fichiers dans $file

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

    if(!is_file($file)) { $file = '/'.$file; } //on rajoute un "/" devant les dossier pour qu'ils soient tri?s au d?but

    $ext_fichier = '';

    }

    if($ext_fichier == 'html' ) { //utile pour exclure certains types de fichiers ? ne pas lister

    array_push($fichier, $file);

    }

    }

    }

    }

    natcasesort($fichier); //la fonction natcasesort( ) est la fonction de tri standard sauf qu'elle ignore la casse

    foreach($fichier as  $lineNumber => $value) {

    $lineNumber1 = $lineNumber + 1;

    $name = str_replace ('/', '', $value);  $name = str_replace ('.html', '', $name);
		$name = str_replace ('-', ' ', $name);

      echo '<p><a class="link" href="http://'.$_SERVER['HTTP_HOST'].'/'.rawurlencode($repertoire).'/'.rawurlencode(str_replace ('/', '', $value)).'" target="Player"  id="play'.$lineNumber1.'" >'.$name.'</a></p><br />';

    }

    }

    //exemple d'utilisation :

    listing('evaluation_javascript'); //chemin du dossier

   

   ?>

 </aside>
    </div>
	
	
<div class="col-12 col-sm-9 contenu" id="iframe">
  <article>
<h2>Vu du contenu appelé</h2>
<div class="ardoise">
<iframe name="Player" id="Player" scrolling="yes" frameborder="0" height="450px" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"  width="100%"></iframe>
 </div>
 <br>
<h2>Evaluation Javascript pour les groupes TB</h2>
<blockquote>
<p>Vous devez réaliser cette évaluation seul, sans l'aide de vos collègues ni celle de vos formateurs. Vous pouvez vous aider d'internet et de vos cours.</p>
<p>Bien lire les énoncés et respecter les consignes.</p>
<p>Durée de l'évaluation : 2 jours.</p>
<p>Ne bloquez pas trop longuement sur un exercice, passez au suivant et revenez dessus plus tard. </p>
</blockquote>
<h2 id="3">Exercice 1 - Calcul du nombre de jeunes, de moyens et de vieux</h2>
<p>Il s'agit de dénombrer les personnes d'âge strictement inférieur à 20 ans, les personnes d'âge strictement supérieur à 40 ans et celles dont l'âge est compris entre 20 ans et 40 ans (20 ans et 40 ans y compris).</p>
<p>Le programme doit demander les âges successifs.</p>
<p>Le comptage est arrêté dès la saisie d'un centenaire. Le centenaire est compté.</p>
<p>Donnez le programme Javascript correspondant qui affiche les résultats.</p>
<h2>Exercice 2 : Table de multiplication</h2>
<p>Ecrivez une fonction qui affiche une table de multiplication.</p>
<p>Votre fonction doit prendre un paramètre qui permet d'indiquer quelle table afficher.</p>
<p>Par exemple, <code>TableMultiplication(7)</code> doit afficher : </p>
<p>1 x 7 = 7</p>
<p>2 x 7 = 14</p>
<p>3 x 7 = 21
...</p>
<h2>Exercice 3 : recherche d'un prénom</h2>
<p>Un prénom est saisi au clavier. On le recherche dans le tableau <code>tab</code> donné ci-après. </p>
<p>Si le prénom est trouvé, on l'élimine du tableau en décalant les cases qui le suivent, et en mettant à blanc la dernière case.
Si le prénom n'est pas trouvé un message d'erreur apparait et aucun prénom ne se supprime.</p>
<pre><code> <span class="hljs-keyword">var</span> <span class="hljs-var">tab</span> = [<span class="hljs-string">"Audrey"</span>, <span class="hljs-string">"Aurélien"</span>, <span class="hljs-string">"Flavien"</span>, <span class="hljs-string">"Jérémy"</span>, <span class="hljs-string">"Laurent"</span>, <span class="hljs-string">"Melik"</span>, <span class="hljs-string">"Nouara"</span>, <span class="hljs-string">"Salem"</span>, <span class="hljs-string">"Samuel"</span>, <span class="hljs-string">"Stéphane"</span>];</code></pre>
<p>( exemple : ["Audrey", "Aurélien", "Flavien", "Jérémy", "Laurent", "Melik", "Nouara", "Salem", "Samuel", " "]; )</p>
<h2>Exercice 4 : total d'une commande</h2>
<p>A partir de la saisie du prix unitaire noté PU d'un produit et de la quantité commandée QTECOM, afficher
le prix à payer PAP, en détaillant la remise REM et le port PORT, sachant que :</p>
<ul>
<li>TOT = ( PU * QTECOM )</li>
<li>la remise est de 5% si TOT est compris entre 100 et 200 € et de 10% au-delà</li>
<li>le port est gratuit si le prix des produits ( le total remisé ) est supérieur à 500 €. Dans le cas contraire, le
port est de 2%</li>
<li>la valeur minimale du port à payer est de 6 €</li>
</ul>
<p>Testez tous les cas possibles afin de vous assurez que votre script fonctionne.</p>
<p><strong>Ci-dessous, un jeu de tests</strong> :</p>
<ul>
<li>Saisir 600 € et quantité = 1 : remise 10% (-60 €) soit 540,00 et frais port = 0; à payer : 540 € </li>
<li>Saisir 501 € et quantité = 1 : remise 10% (-50,1 €) soit 450,90 et frais port 2% (de 450,90 €) soit +9,01 € ; à payer : 450,90+9.01 = 459,91 €. </li>
<li>Saisir 100 € et quantité = 2 : 200 € donc remise 5% soit 190 € et frais de port 2% soit 3,8 € mini 6 €; à payer : 190+6 = 196 €  </li>
<li>Saisir 3 € et quantité = 1 : remise 0, frais de port 2% soit 0.06 € donc le minimum de 6 € s'applique; à payer : 3+6 = 9 €</li>
</ul>
<h2 id="26">Exercice  5 : vérification d'un formulaire</h2>
<p>Effectuez le contrôle de saisie de votre formulaire Jarditou en Javascript. </p>
<p>Lorsqu'une erreur est détectée, l'utilisateur doit en être informé grâce à l'affichage d'un message sous le champ concerné. </p>
<p>Le formulaire ne peut être envoyé que lorsque tout est bon.</p>
 </article>
</div>
 

   

    </div>

</div> 

    <!--

        Footer

    -->

<?php include('footer.php');?>