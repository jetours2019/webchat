<?php
//esto incluye la librería
include_once "somosioticos_dialogflow.php";
//credenciales('empanadasbot','123456789');

// me conecto a db
$mysqli = mysqli_connect("localhost", "jetours1_disponibilidad", "(!mEkkfrKuS9", "jetours1_bloqueos");

if (!$mysqli) {
      echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
      die();
}



if (intent_recibido("disponibilidad")) {

      $origen = obtener_variables()['origen'];
      $destino = obtener_variables()['destino'];
      $fecha = obtener_variables()['mes'];
      $fecha_form = formatDate($fecha);
      $mes = $fecha_form['mes'];
      $ano = $fecha_form['anio'];

      $ciudad1 = asignarNombreCiudad($origen);
      $ciudad2 = asignarNombreCiudad($destino);


      global $mysqli;
      $consulta = mysqli_query($mysqli, "select * from productos  where desde1 = '$ciudad1' AND hasta1 = '$ciudad2' AND mes='$mes' AND ano='$ano'  ORDER BY dia ASC") or die(mysqli_error($mysqli));
      $registro = mysqli_fetch_array($consulta);
      $existe = false;
      while ($registro = mysqli_fetch_array($consulta)) {
            $existe = true;
            $desde1 = $registro['desde1'];
            $hasta1 = $registro['hasta1'];
            $libre = $registro['libre'];
            $dia = $registro['dia'];
            $dia2 = $registro['dia2'];
            $aero = $registro['aerolineas1'];
            $amo = $registro['ano'];
            if ($libre > 0) {
                  $mensaje .= "salida del $dia - $dia2 ($aero) | Sillas($libre)  \n ";
            }
      }

      if(!$existe){
            $mensaje = "No se encuentran registros";
      }

      enviar_texto("Sillas disponibles en el mes($mes) año($amo)\n para $origen > $destino son:\n ____________________________________\n $mensaje  ____________________________________\n Para otra consulta o chatear con un asesor escribe MENU \n ____________________________________");
}






























//***************************
//**** FUNCIONES ************
//***************************




function formatDate($date)
{
      if (!is_array($date)) {
            $mes = date("n", strtotime($date));
            $anio = date("Y", strtotime($date));
            return array("mes" => $mes, "anio" => $anio);
      }

      $posibles = ["startDate", "startTime", "date_time", "startDateTime"];

      foreach ($posibles as $key) {
            if (array_key_exists($key, $date)) {
                  $mes = date("n", strtotime($date[$key]));
                  $anio = date("Y", strtotime($date[$key]));
                  return array("mes" => $mes, "anio" => $anio);
            }
      }
}











function asignarNombreCiudad($codigo)
{
      $ciudad = "";
      switch ($codigo) {
            case "Cali":
                  $ciudad = "CLO";
                  break;
            case "Bogotá":
                  $ciudad = "BOG";
                  break;
            case "Santa Marta":
                  $ciudad = "SMR";
                  break;
            case "Cartagena":
                  $ciudad = "CTG";
                  break;
            case "San Andrés":
                  $ciudad = "ADZ";
                  break;
            case "Barranquilla":
                  $ciudad = "BAQ";
                  break;
            case "Santo Domingo":
                  $ciudad = "SDQ";
                  break;
            case "Panamá":
                  $ciudad = "PTY";
                  break;
            case "Aruba":
                  $ciudad = "AUA";
                  break;
            case "Curazao":
                  $ciudad = "CUR";
                  break;
            case "Punta Cana":
                  $ciudad = "PUJ";
                  break;
            case "Cancún":
                  $ciudad = "CUN";
                  break;
            case "Pasto":
                  $ciudad = "PSO";
                  break;
            case "Pereira":
                  $ciudad = "PEI";
                  break;
      }

      return $ciudad;
}





function consulta_disponibilidad($origen, $destino, $mes)
{
      // return $cantidad;
}
