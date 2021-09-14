<?php
//esto incluye la librería
include_once "somosioticos_dialogflow.php";
date_default_timezone_set("America/Bogota");
debug();
//credenciales('empanadasbot','123456789');


if (intent_recibido("disponibilidad")) {
      // me conecto a db
      $mysqli = mysqli_connect("localhost", "jetours1_disponibilidad", "(!mEkkfrKuS9", "jetours1_bloqueos");

      if (!$mysqli) {
            echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
            die();
      }
      $origen = obtener_variables()['origen'];
      $destino = obtener_variables()['destino'];
      $fecha = obtener_variables()['mes'];
      $fecha_form = formatDate($fecha);
      $mes = $fecha_form['mes'];
      $ano = $fecha_form['anio'];

      $ciudad1 = asignarNombreCiudad($origen);
      $ciudad2 = asignarNombreCiudad($destino);


      $consulta = mysqli_query($mysqli, "select * from productos  where desde1 = '$ciudad1' AND hasta1 = '$ciudad2' AND mes='$mes' AND ano='$ano'  ORDER BY dia ASC") or die(mysqli_error($mysqli));
      $registro = mysqli_fetch_array($consulta);
      $existe = false;
      while ($registro = mysqli_fetch_array($consulta)) {
            $desde1 = $registro['desde1'];
            $hasta1 = $registro['hasta1'];
            $libre = $registro['libre'];
            $dia = $registro['dia'];
            $dia2 = $registro['dia2'];
            $aero = $registro['aerolineas1'];
            $amo = $registro['ano'];
            if ($libre > 0) {
                  $existe = true;
                  $mensaje .= "Salida del $dia - $dia2 ($aero) | Sillas($libre)  \n ";
            }
      }

      if (!$existe) {
            $mensaje = "No se encuentran registros";
      }

      $mes_nombre = asignarNombreMes($mes);
      enviar_texto("Sillas disponibles en el mes($mes_nombre) año($ano)\n para $origen > $destino son:\n ____________________________________\n $mensaje  ____________________________________\n Para otra consulta o chatear con un asesor escribe MENU \n ____________________________________");
}


if (intent_recibido('conectar') || intent_recibido('conectar2')) {

      $hora = date('H');
      if($hora < 8 || $hora >= 18) {
            $mensaje = "El horario de atención es de 8am-6pm";
            enviar_texto($mensaje);
      }else{
            $asesores = consulta_disponibilidad_asesores();

            if (!$asesores['botones']) {
                  $mensaje = "No se encuentran asesores en linea";
                  enviar_texto($mensaje);
            }else{
                  enviar_respuestas_rapidas($asesores, "facebook");
            }
      }
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


function asignarNombreMes($numero)
{
      $mes = "";
      switch ($numero) {
            case 1:
                  $mes = "Enero";
                  break;
            case 2:
                  $mes = "Febrero";
                  break;
            case 3:
                  $mes = "Marzo";
                  break;
            case 4:
                  $mes = "Abril";
                  break;
            case 5:
                  $mes = "Mayo";
                  break;
            case 6:
                  $mes = "Junio";
                  break;
            case 7:
                  $mes = "Julio";
                  break;
            case 8:
                  $mes = "Agosto";
                  break;
            case 9:
                  $mes = "Septiembre";
                  break;
            case 10:
                  $mes = "Octubre";
                  break;
            case 11:
                  $mes = "Noviembre";
                  break;
            case 12:
                  $mes = "Diciembre";
                  break;
      }
      return $mes;
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





function consulta_disponibilidad_asesores()
{
      // me conecto a db
      $mysqli = mysqli_connect("localhost", "jetours1_disponibilidad", "(!mEkkfrKuS9", "jetours1_webchat");

      if (!$mysqli) {
            echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
            die();
      }

      $query = "SELECT * FROM usuarios WHERE online=true AND username != 'admin'";
      $consulta = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

      $tarjetas = array(
            "titulo" => "Los siguientes asesores se encuentran en linea:",
            "botones" => array()
      );

      while ($registro = mysqli_fetch_array($consulta)) {
            $fullname = $registro['fullname'];
            $clients = $registro['clients'];
            $asesor = "$fullname ($clients)";
            array_push($tarjetas['botones'], $asesor);
      }

      return $tarjetas;
}
