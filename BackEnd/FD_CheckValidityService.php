<?php
/**
 * Created by VSCode.
 * User: simon
 * Date: 20/07/2018
 * Time: 11:40
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

require("DB/FD_DB.php");
require("DB/FD_Mysql.php");
require("WebTools/FD_Logger.php");

//istanzio logger
$log = new FD_Logger(null);


$service = $_POST["service"];
if(strlen($service) == 0)
{
    $log->lwrite('[ERRORE] - Parametro vuoto !');
    echo '{"error": "Parametro vuoto !"}';
    return;
}

try
{
    //Inizializzo componente SQL
    $sql = new FD_Mysql();

    //Controllo che la connessione al DB sia andata a buon fine
    if(strlen($sql->lastError) > 0)
    {
        $log->lwrite('[ERRORE] - '.$sql->lastError);
        if($sql->connected)
        {
            $sql->closeConnection();
        }
        return;
    }

    $query = "call sys_check_validity_service($service);";
    $log->lwrite('[INFO] - query - '.$query);
    $result = $sql->exportJSON($query);

    if(strlen($sql->lastError) > 0)
    {
        $log->lwrite('[ERRORE] - '.$sql->lastError) ;
        if($sql->connected)
        {
            $sql->closeConnection();
        }
        return;
    }

    $sql->closeConnection();

    $log->lwrite('[INFO] - responce - '.$result);
    echo $result;
}
catch (Exception $e)
{
    $log->lwrite('[ERRORE] - '.$e->getMessage());
}