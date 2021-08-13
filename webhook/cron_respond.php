<?php

require_once "../db/conexion.php";

$query = "SELECT * FROM usuarios WHERE username != 'admin'";
$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
$tags = array();
while ($registro = mysqli_fetch_array($consulta)) {
    $tags[$registro['id']] = $registro['tag'];
}

$url = "https://app.respond.io/api/v1/contact/by_custom_field?name=asignado&value=";
$accesstoken = "1bc636f151a3909b02451ebd431f0ca424bc744f9ef03090892e4acd100b74f0f9827eef184095d00cec29a56ba966ac5340e3dc217ea02152111eef5a18c50b";

foreach ($tags as $id=>$tag) {

    $ch = curl_init($url . $tag);
    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: Bearer ' . $accesstoken;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    $clients = countContacts($response);
    $sql = "UPDATE usuarios SET clients=$clients WHERE id=$id";
    $act = $conexion->query($sql);
}


function countContacts($response)
{
    global $accesstoken;

    if (!$response->links->next) {
        return count($response->data);
    }

    $ch = curl_init($response->links->next);
    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: Bearer ' . $accesstoken;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $newResponse = json_decode(curl_exec($ch));

    return count($response->data) + countContacts($newResponse);
}
