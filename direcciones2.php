<html>
<!DOCTYPE html> <html class="ltr" dir="ltr" lang="es-ES" xml:lang="es-ES"> <head> <title>CartoCiudad</title> <meta name="author" content="Instituto Geográfico Nacional" />

<body onload="enviar(); recibir()">


<form action="http://www.cartociudad.es/wps/WebProcessingService" method="post" name="form" style="width-max: 20em !important;"> 
<textarea class="area" cols="123" name="request" rows="25" style="resize:none;">
</textarea> 

<script language="JavaScript">



function enviar(){

document.form.request.value = "\u003C?xml\n\n version=\u00221.0\u0022 encoding=\u0022UTF-8\u0022 standalone=\u0022yes\u0022?\u003E\n\u003CExecute service=\u0022WPS\u0022 version=\u00220.4.0\u0022 store=\u0022false\u0022 status=\u0022false\u0022\nxmlns=\u0022http://www.opengeospatial.net/wps\u0022\nxmlns:pak=\u0022http://www.opengis.net/examples/packet\u0022\nxmlns:ows=\u0022http://www.opengeospatial.net/ows\u0022\nxmlns:xlink=\u0022http://www.w3.org/1999/xlink\u0022\nxmlns:xsi=\u0022http://www.w3.org/2001/XMLSchema-instance\u0022\nxsi:schemaLocation=\u0022http://www.opengeospatial.net/wps..\\wpsExecute.xsd\u0022 xmlns:om=\u0022http://www.opengis.net/om\u0022\nxmlns:gml=\u0022http://www.opengis.net/gml\u0022>" + "\n\n\u003Cows:Identifier\u003Ecom.ign.process.geometry.ClosestMultiplePointFinder\u003C/ows:Identifier\u003E"
+ "\n\n	<DataInputs>\n	<Input>\n		<ows:Identifier>data</ows:Identifier>\n		<ows:Title>Punto</ows:Title>\n		<ComplexValue\n"
+ "defaultSchema=\u0022http://www.idee.es/parser/ArrayList.xsd\u0022>\n			<gml:Point>\n				<gml:coord>\n					<gml:X>-0.39</gml:X>\n					<gml:Y>39.47</gml:Y>\n				</gml:coord>\n			</gml:Point>\n		</ComplexValue>\n"
+"	</Input>\n	</DataInputs>\n	<OutputDefinitions>\n	<Output>\n		<ows:Identifier>result</ows:Identifier>\n		<ows:Title>Lista de portales</ows:Title>\n		<ows:Abstract>xml con la lista de portales y las coordenadas de\nbusqueda</ows:Abstract>"
+ "\n		<ComplexOutput defaultFormat=\u0022text/XML\u0022\ndefaultSchema=\u0022http://www.idee.es/portalList.xsd\u0022>\n			<SupportedComplexData>\n				<Schema>http://www.idee.es/portalList.xsd</Schema>\n			</SupportedComplexData>\n"
+ "		</ComplexOutput>\n	</Output>\n	</OutputDefinitions>\n</Execute>";

document.form.submit();

}

function recibir(){


var documentoXml = window.XMLHttpRequest.responseXML;
//var x = XMLHttpRequest.responseXML.getElementsByTagName('provincia')[0].firstChild.nodeValue;
//return documentoXml;
}

</script>
</body>
</html>

<?php
$nom = $_REQUEST['documentoXml'];
echo $nom;
?>