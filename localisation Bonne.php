

<script src='https://api.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.css' rel='stylesheet' />

<center style="color:gray;margin-top:20px;">
	<center style="color:gray;margin-top:20px;">

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
	isset($_GET['ip'])?$ip=$_GET['ip']:$ip;
	//Testeur d'adresse ip.

	$tabIp=explode(".",$ip);
	for($i=0;$i<4;$i++){
		isset($tabIp[$i])?$ipTrue=true:$ipTrue=false;
	}


	//Generateur d'adresse ip si la votre est incorrecte, si vous testez en local par exemple.

	$ipDefault=mt_rand(0,192).".".mt_rand(0,240).".".mt_rand(0,100).".".mt_rand(0,240);
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
			echo "<div id='map' style='width: 400px; height: 300px;'></div>

<div id='menu'>
	<h4>Style de carte.</h4><br><br>
<input
id='streets-v11'
type='radio'
name='rtoggle'
value='streets'
checked='checked'
/>
<label for='streets'>Rues</label>
<input id='light-v10' type='radio' name='rtoggle' value='light' />
<label for='light'>Claire</label>
<input id='dark-v10' type='radio' name='rtoggle' value='dark' />
<label for='dark'>Sombre</label>
<input id='outdoors-v11' type='radio' name='rtoggle' value='outdoors' />
<label for='outdoors'>Simple</label>
<input id='satellite-v9' type='radio' name='rtoggle' value='satellite' />
<label for='satellite'>satellite</label>
</div>
<br><br>";
			echo "Le visiteur ayant l'adresse ip ".$ipResponse['ip']." est dans le pays: <span style='color:teal'>$pays ($codePays)</span>, dans la ville de <span style='color:lightcoral'>$ville.</span>, inutile de preciser que ce pays est dans le continent <span style='color:rgb(110, 6, 55)'>$geo->continent.</span>\n";
			echo "	<br><br>
				  	Ses coordonnes geographiques sont: <br>
						<ul>
							<li>Longitude:<span style='color:orange'> $geo->lon</span></li>
							<li>Latitude:<span style='color:orange'> $geo->lat</span></li>
						</ul>
						<br>
						<style>
#menu {
position: relative;
background: #fff;
padding: 10px;
font-family: 'Open Sans', sans-serif;
}
</style>
 
<script>
	mapboxgl.accessToken = 'pk.eyJ1Ijoid29vdGFya292c2tpIiwiYSI6ImNrNmMzYTN2MDB4NTEza21ncjIwdHIxczEifQ.vIsFKXlEkPMKclA4KZQhLw';
var map = new mapboxgl.Map({
container: 'map',
style: 'mapbox://styles/mapbox/streets-v11',
zoom: 13,
center: [4.899, 52.372]
});
 
var layerList = document.getElementById('menu');
var inputs = layerList.getElementsByTagName('input');
 
function switchLayer(layer) {
var layerId = layer.target.id;
map.setStyle('mapbox://styles/mapbox/' + layerId);
}
 
for (var i = 0; i < inputs.length; i++) {
inputs[i].onclick = switchLayer;
}
</script><script>
	mapboxgl.accessToken = 'pk.eyJ1Ijoid29vdGFya292c2tpIiwiYSI6ImNrNmMzYTN2MDB4NTEza21ncjIwdHIxczEifQ.vIsFKXlEkPMKclA4KZQhLw';
    var map = new mapboxgl.Map({
        container: 'map',
        zoom: 16,
        style: 'mapbox://styles/mapbox/streets-v11',
		center: [".$geo->lon.",".$geo->lat.", -0.47],
    });

    var size = 200;

    // implementation of CustomLayerInterface to draw a pulsing dot icon on the map
    // see https://docs.mapbox.com/mapbox-gl-js/api/#customlayerinterface for more info
    var pulsingDot = {
        width: size,
        height: size,
        data: new Uint8Array(size * size * 4),

        // get rendering context for the map canvas when layer is added to the map
        onAdd: function() {
            var canvas = document.createElement('canvas');
            canvas.width = this.width;
            canvas.height = this.height;
            this.context = canvas.getContext('2d');
        },

        // called once before every frame where the icon will be used
        render: function() {
            var duration = 1000;
            var t = (performance.now() % duration) / duration;

            var radius = (size / 2) * 0.3;
            var outerRadius = (size / 2) * 0.7 * t + radius;
            var context = this.context;

            // draw outer circle
            context.clearRect(0, 0, this.width, this.height);
            context.beginPath();
            context.arc(
                this.width / 2,
                this.height / 2,
                outerRadius,
                0,
                Math.PI * 2
            );
            context.fillStyle = 'rgba(255, 200, 200,' + (1 - t) + ')';
            context.fill();

            // draw inner circle
            context.beginPath();
            context.arc(
                this.width / 2,
                this.height / 2,
                radius,
                0,
                Math.PI * 2
            );
            context.fillStyle = 'rgba(255, 100, 100, 1)';
            context.strokeStyle = 'white';
            context.lineWidth = 2 + 4 * (1 - t);
            context.fill();
            context.stroke();

            // update this image's data with data from the canvas
            this.data = context.getImageData(
                0,
                0,
                this.width,
                this.height
            ).data;

            // continuously repaint the map, resulting in the smooth animation of the dot
            map.triggerRepaint();

            // return `true` to let the map know that the image was updated
            return true;
        }
    };

    map.on('load', function() {
        map.addImage('pulsing-dot', pulsingDot, { pixelRatio: 2 });

        map.addSource('points', {
            'type': 'geojson',
            'data': {
                'type': 'FeatureCollection',
                'features': [
                    {
                        'type': 'Feature',
                        'geometry': {
                            'type': 'Point',
                            'coordinates': [".$geo->lon.", ".$geo->lat."],
                        }
                    }
                ]
            }
        });
        map.addLayer({
            'id': 'points',
            'type': 'symbol',
            'source': 'points',
            'layout': {
                'icon-image': 'pulsing-dot'
            }
        });
    });
</script>

					Pour un plus, son fournisseur d'acces internet est <span style='color:rgb(0, 121, 26)'>$geo->isp.</span>
					<br>
					<h4 style='position:fixed;top:20px; right:20'>Rechercher une adresse ip: <form method='get'><input type='text' name='ip'><br><br><input type='submit' style='background:darkorange; color:white;border:1px solid black; box-shadow: 0px 0px 2px gray; padding:5px 10px;' value='Valider'></form></h4>";
			
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

<footer style="position:fixed; bottom:0; height:50px; width:105%; color:white;left:-5px; background:black;">
	<span style="position:relative; top:20px">Fait avec amour par <a href="http://github.com/kratos237" target="_blank" style="color:darkorange">Daniel Uokof</a></span>
</footer>
</center>

