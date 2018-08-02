<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 11/07/2018
 * Time: 23:29
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

$tipo = $_GET["tipo"];

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

    if($tipo == 1) //clienti
        $query = "call spFD_clienti_list();";
    else if ($tipo == 2) //articoli
        $query = "call spFD_listino_list('a');";
    else if ($tipo == 3) //preventivi
        $query = "call spFD_preventivi_list();";
    else //sessioni
        $query = "call spFD_session_list();";

    $log->lwrite('[INFO] - query - '.$query);
    $result = $sql->exportJSON($query);

    if(strlen($sql->lastError) > 0)
    {
        $log->lwrite('[ERRORE] - '.$sql->lastError);
        if($sql->connected)
        {
            $sql->closeConnection();
        }
        return;
    }

    echo $result;
    $log->lwrite('[INFO] - Export file tipo:'.$tipo);

    $sql->closeConnection();

}
catch (Exception $e)
{
    $log->lwrite('[ERRORE] - '.$e->getMessage());
}