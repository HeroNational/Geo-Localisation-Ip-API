<script async defer src="https://buttons.github.io/buttons.js"></script>
<center style="color:gray;margin-top:150px;">
<?php
// Daniel Uokof

//Adresse de l'api https://extreme-ip-lookup.com/

//04 fevrier 2020, Yaounde

function get_ip()
{

	//-- Fonction de récupération de l'adresse IP du visiteur
    if ( isset ( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    elseif ( isset ( $_SERVER['HTTP_CLIENT_IP'] ) )
    {
        $ip  = $_SERVER['HTTP_CLIENT_IP'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
	}
	
	//Testeur d'adresse ip.

	$tabIp=explode(".",$ip);
	for($i=0;$i<4;$i++){
		isset($tabIp[$i])?$ipTrue=true:$ipTrue=false;
	}


	//Generateur d'adresse ip si la votre est incorrecte, si vous testez en local par exemple.

	$ipDefault=mt_rand(0,100).".".mt_rand(0,240).".".mt_rand(0,100).".".mt_rand(0,240);
	if($ipTrue==false){
		return array("ip"=>$ipDefault,"state"=>$ipTrue);
	}else{
		return array("ip"=>$ip,"state"=>$ipTrue);
	}
	
}

function apiGeoLocalisation()
{
	$ipResponse=get_ip();
	$message="Votre addresse ip n'est pas valide mais mon petit generateur d'ip a cree une adresse pour vous, la voici: ".$ipResponse['ip'];
	$geo = @json_decode(file_get_contents("http://extreme-ip-lookup.com/json/".$ipResponse['ip']/*?callback=getIP*/));
	if(isset($geo->status)){
		if($geo->status=="success"){
			$pays = $geo->country;
			$ville = $geo->city;
			$ipType = $geo->ipType;
			$codePays=$geo->countryCode;
			if($ipResponse["state"]==false){
				echo "<br>".$message."<br><br>";
			}
			echo "Le visiteur ayant l'adresse ip ".$ipResponse['ip']." est dans le pays: <span style='color:teal'>$pays ($codePays)</span>, dans la ville de <span style='color:lightcoral'>$ville.</span>, inutile de preciser que ce pays est dans le continent <span style='color:rgb(110, 6, 55)'>$geo->continent.</span>\n";
			echo "	<br><br>
				  	Ses coordonnes geographiques sont: <br>
						<ul>
							<li>Longitude:<span style='color:orange'> $geo->lon</span></li>
							<li>Latitude:<span style='color:orange'> $geo->lat</span></li>
						</ul>
						<br>
					Pour un plus, son fournisseur d'acces internet est <span style='color:rgb(0, 121, 26)'>$geo->isp.</span>";
			
		}else{
			echo "Un probleme est survenu avec l'API.";	
		}
		echo "<h4>Si vous voulez consulter de vous meme la reponse originale de l'api cliquez <a style='color:lightblue' href='http://extreme-ip-lookup.com/json/".$ipResponse['ip']."' target='_blank'>sur moi.</a></h4>";
	}else{
		echo "Cette ApI n'est plus fonctionnelle. Vous avez peut-etre une mauvaise connexion internet.";	
	}
	
}


//Appel de la fonction 

apiGeoLocalisation();

?>
<!-- Place this tag where you want the button to render. -->
<a class="github-button" href="https://github.com/Kratos237/Geo-Localisation-Ip-API" data-color-scheme="no-preference: dark; light: light; dark: light;" data-size="large" data-show-count="true" aria-label="Fork ntkme/github-buttons on GitHub">Fork me on github</a>
<style>#forkongithub a{background:black;color:darkorange;text-decoration:none;font-family:arial,sans-serif;text-align:center;font-weight:bold;padding:5px 40px;font-size:1rem;line-height:2rem;position:relative;transition:0.5s;}#forkongithub a:hover{background:black;color:teal;}#forkongithub a::before,#forkongithub a::after{content:"";width:100%;display:block;position:absolute;top:1px;left:0;height:1px;background:purple;}#forkongithub a::after{bottom:1px;top:auto;}@media screen and (min-width:800px){#forkongithub{position:absolute;display:block;top:0;right:0;width:200px;overflow:hidden;height:200px;z-index:9999;}#forkongithub a{width:200px;position:absolute;top:60px;right:-60px;transform:rotate(45deg);-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);box-shadow:4px 4px 10px rgba(0,0,0,0.8);}}</style><span id="forkongithub"><a href="https://github.com/Kratos237/Geo-Localisation-Ip-API">Fork me on GitHub</a></span>
<footer style="position:fixed; bottom:0; height:50px; width:105%; color:white;left:-5px; background:black;">
	<span style="position:relative; top:20px">Fait avec amour par <a href="http://github.com/kratos237" target="_blank" style="color:darkorange">Daniel Uokof</a></span>
</footer>
</center>