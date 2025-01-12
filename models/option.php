<?php
/**
 * Option model
 */
class OptionModel extends Model
{

    /**
     * Export file directory
     *
     * @var string
     */
    const EXPORT_FILE_DIR = 'data/export';

    /**
     * Return Kind options
     *
     * @return array
     */
    public function export()
    {
        $output = null;
        $retval = null;

        $dir = $this->getExportDir();
        $fileName = sprintf('%s-database-bu-%s.sql', $this->config['database'], date('d-m-Y'));
        $fileUrl = sprintf('%s/%s', $dir, $fileName);

        $mysqlDumpPath = 'mysqldump';
        $mysqlPath = 'mysql';
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            //Direct paths to mysqldump and mysql should be defined when server is running on Windows
            $mysqlDumpPath = 'D:\xampp-74\mysql\bin\mysqldump.exe';
            $mysqlPath = 'D:\xampp-74\mysql\bin\mysql.exe';
        }

        //Create dump
        $password = ($this->config['password']) ? '-p' . $this->config['password'] : '';
        $port = ($this->config['port']) ? '--port='.$this->config['port'] : '';
        $command = sprintf('%s -h %s %s -u %s %s %s > %s', $mysqlDumpPath, $this->config['host'], $port, $this->config['login'], $password, $this->config['database'], $fileUrl);
        exec($command, $output, $retval);

        $this->add($fileName);

        return $retval;
    }

    /**
     * Return directory of export files, create if not exists
     *
     * @return string
     */
    public function getExportDir()
    {
        $dir = getcwd() . '/' . self::EXPORT_FILE_DIR . '/';

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    /**
     * Add new export
     *
     * @param int $id_user
     * @param string $file
     * @return array
     */
    public function add($file)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : '';

        $result = $this->db->query("
            insert into `md_export` (`id_user`,`file`)
            values (
                ".$this->_escape($id_user).",
                ".$this->_escape($file)."
            )"
        );

        $id_export = $this->db->insert_id;

        return [
            'id_export' => $id_export,
            'file' => $file
        ];
    }

    /**
     * Get all exports
     *
     * @return array
     */
    public function getExportHistory()
    {
        $result = $this->db->query("
            select `me`.*
            from `md_export` as `me` 
            order by `me`.`created_at` desc"
        );

        $data = $this->_fetch($result);

        return $data;
    }
}