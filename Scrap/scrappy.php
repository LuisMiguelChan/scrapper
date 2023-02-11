<?php
echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta http-equiv='Content-type' content='text/html;charset=utf-8'/>";
echo "<title>RESULTADO</title>";
echo "<link rel='stylesheet' href='estilo.css'>";
echo "</head>";
echo "<body class='body1'>";

include('Conexion.php');
include('simple_html_dom.php');
//Extraccion de la informacion del sitio
$html = file_get_html('https://valladolid.tecnm.mx/site/noticiero');
$html->save('itsva.html');

//Extraccion de la informacion necesaria
$i=1;
echo "</br></br>";
echo "<h1>ULTIMA NOTICIA ANEXADA AL SITIO</h1>";
foreach($html->find('h4.media-heading') as $e){
    $e1 = strip_tags($e);
    echo $e;
    if($i==1)
        break;
    $i++;
}
foreach($html->find('div.text-justify p') as $re){
    $e2 = strip_tags($re);
    echo $re."</br>";
    if($i==1)
        break;
    $i++;
}
foreach($html->find('a.btn') as $se){
    if ($se->href) {
        $res=$se->href;
        $anex = 'https://valladolid.tecnm.mx';
        $resulta = $anex . $res;
        echo "</br>".$resulta."</br>";
      }
    if($i==1)
        break;
    $i++;
}
foreach($html->find('div.media-left img') as $resu){
    if ($resu->src) {
        $resultado=$resu->src;
    }
    echo "</br>".$resu;
    if($i==1)
        break;
    $i++;
}

//Cargado de la informacion a la base de datos
$sql = "SELECT * FROM tbl_noticias ORDER BY id_noticiero DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
	while($rowData = mysqli_fetch_array($result)){
        if($rowData["enlace"] == $resulta){
            echo "</br><h4 class='res-red'>EL RESULTADO SE ENCUENTRA EN LA BASE DE DATOS</h4>";
        }else{
            $sql2 = "INSERT INTO tbl_noticias (id_noticiero, titular, info, enlace) VALUES (NULL, '$e1', '$e2', '$resulta')";
            if (mysqli_query($conn, $sql2)) {
                echo "</br><h4 class='res-green'>EL RESULTADO HA SIDO AGREGADO A LA BASE DE DATOS</h4>";
            } else {
                echo "</br>"."</br>"."Error: " . $sql2 . "<br>" . mysqli_error($conn);
            }
        }
	}
}

echo "<input type='submit' onclick='ver();' value='Todas las noticias almacenadas'>";

function ver(){
    $sql = "SELECT * FROM tbl_noticias";
    $result = mysqli_query($conn, $sql);

    while($rows=mysqli_fetch_array($result)){
        echo "<br>"."<br>"."<h3>".$rows[1]."</h3>";
        echo $rows[2]."<br>"."<br>";
        echo $rows[3]."<br>"."<br>";
        echo $rows[4]."<br>"."<br>"."<br>";
    } 
}
mysqli_close($conn);
echo "</body>";
echo "</html>";
?>