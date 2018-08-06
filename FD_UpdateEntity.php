<?php
/**
 * Created by VSCode.
 * User: simon
 * Date: 06/08/2018
 * Time: 16:01
 */

//Imposto qualsiasi orgine da cui arriva la richiesta come abilitata e la metto in cache per un giorno
if (isset($_SERVER['HTTP_ORIGIN']))
{
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

//Imposto tutti i metodi come abilitati
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}

//remove the notice
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//require("Config/FD_Define.php");
require("DB/FD_DB.php");
require("DB/FD_Mysql.php");
require("WebTools/FD_Logger.php");

//istanzio logger
$log = new FD_Logger(null);
$id_to = $_GET["id_to"];
$query = $_GET["query"];

if(strlen($id_to) == 0 || strlen($query) == 0)
{
    $log->lwrite('[ERRORE] - Parametri non validi !');
    echo '{"error": "Parametri non validi !"}';
    return;
}

try
{
    $log->lwrite('[INFO] - [UPDATE] - query - '.$query);

    $sql = new FD_Mysql($id_to);

    //Controllo che la connessione al DB sia andata a buon fine
    if(strlen($sql->lastError) > 0)
    {
        echo '{"error" : "'.$sql->lastError.'"}';
        $log->lwrite('[ERRORE] - [UPDATE] - '.$sql->lastError);
        if($sql->connected)
        {
            $sql->closeConnection();
        }
        return;
    }

    $sql->executeSQL($query);

    if(strlen($sql->lastError) > 0)
    {
        echo '{"error" : "'.$sql->lastError.'"}';
        $log->lwrite('[ERRORE] - [UPDATE] - '.$sql->lastError);
        if($sql->connected)
        {
            $sql->closeConnection();
        }
        return;
    }

    $sql->closeConnection();

    echo '{"result" : "ok"}';

}
catch (Exception $e)
{
    echo '{"error" : "'.$e->getMessage().'"}';
    $log->lwrite('[ERRORE] - '.$e->getMessage());
}