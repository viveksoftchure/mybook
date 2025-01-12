<?php
/**
 * CRON: Creates dump of MySQL database, import it to DEV database, and sends as file to output
 */
session_name('mysaas');
session_start();

require __DIR__.'/../config/config.php';
require_once __DIR__.'/../models/model.php';
require_once __DIR__.'/../models/cron.php';

$db = new mysqli($config['host'], $config['login'], $config['password'], $config['database']);
$db->set_charset('utf8');

$cron_model = new CronModel($db, $config);

$id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0 ;

if (isset($_SESSION['user']['id_user']) && $_SESSION['user']['id_user'] == 1) 
{
    $dir = sprintf('../data/backup/');
    if (!file_exists($dir)) 
    {
        mkdir($dir, 0777, true);
    }
    // return $dir;

    // echo '<pre>'; print_r(getcwd()); echo '</pre>'; exit;
          
    $fileName = $dir.'backup-'.date('Y-m-d').'.sql';

    if (strncasecmp(PHP_OS, 'WIN', 3) == 0) 
    {
        //Direct paths to mysqldump and mysql should be defined when server is running on Windows
        $mysqlDumpPath = $config['mysqlDumpPath'];
        $mysqlPath = $config['mysqlPath'];
    } 
    else 
    {
        $mysqlDumpPath = 'mysqldump';
        $mysqlPath = 'mysql';
    }

    //Create dump
    $password = ($config['password']) ? '-p' . $config['password'] : '';
    $port = ($config['port']) ? '--port='.$config['port'] : '';
    $command = sprintf('%s -h %s %s -u %s %s %s > %s', $mysqlDumpPath, $config['host'], $port, $config['login'], $password, $config['database'], $fileName);
    exec($command);

    //Load dump
    $password = ($config['dev_password']) ? '-p' . $config['dev_password'] : '';
    $port = ($config['dev_port']) ? '--port='.$config['dev_port'] : '';
    $command = sprintf('%s -h %s %s -u %s %s %s < %s', $mysqlPath, $config['dev_host'], $port, $config['dev_login'], $password, $config['dev_database'], $fileName);
    exec($command);

    $cron_data = $cron_model->addBackupCron($id_user);

    if ($cron_data) 
    {
        echo "Success!";
    }
    else
    {
        echo "Error!";
    }

    //Send and remove file
    // header('Content-Encoding: UTF-8');
    // header('Content-type: text/plain; charset=UTF-8');
    // header('Content-Disposition: attachment; filename='.basename($fileName));
    // ob_clean();
    // flush();
    // if (readfile($fileName)) 
    // {
    //     // unlink($fileName);
    // }
}