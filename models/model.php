<?php

require_once __DIR__.'/../lib/MailProxy.php';

/**
 * Abstract model
 */
abstract class Model
{
    /**
     * Profile picture directory
     *
     * @var string
     */
    const PROFILE_PICTURE_DIR = 'data/user/profile-picture';

    /**
     * Current DB connection
     *
     * @var mysqli
     */
    protected $db;

    /**
     * Configuration data
     *
     * @var array
     */
    protected $config;

    /**
     * PHPMailer instance
     *
     * @var PHPMailer
     */
    protected $mail;

    /**
     * Layout service
     *
     * @var Layout
     */
    protected $layout;

    /**
     * Abstract model constructor
     *
     * @param mysqli $db
     * @param mysqli $sdb
     * @param array $config
     */
    public function __construct($db, $config = [])
    {
        $this->config = $config;
        $this->db = $db;
        $this->mail = new MailProxy($db, $config);
        $this->mail->CharSet = 'UTF-8';
    }

    /**
     * Escape and quote data before using in MySQL queries
     *
     * @param mixed $data
     * @param bool $quote
     * @return string
     */
    protected function _escape($data, $quote = true)
    {
        $escaped = $this->db->real_escape_string($data);
        if ($quote && $data !== 'NULL') {
            $escaped = "'" . $escaped . "'";
        }
        return $escaped;
    }

    /**
     * Fetch data and convert special characters to HTML entities
     *
     * Retrieve all available rows or limit result by first row depends on second param
     *
     * @param mysqli_result $result
     * @param bool $all - fetch all or only the first row
     * @param bool $skip - skip converting special characters
     * @return array
     */
    protected function _fetch($result, $all = true, $skip = false)
    {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if (!$skip) 
        {
            foreach ($data as $i => $row) 
            {
                foreach ($row as $k => $v) 
                {
                    $data[$i][$k] = htmlspecialchars($v, ENT_QUOTES);
                }
            }
        }

        if ($all == false && isset($data[0])) 
        {
            return $data[0];
        }
        return $data;
    }

    /**
     * Add new file to destination directory
     *
     * @param string $destination
     * @param string $field
     * @return string
     */
    protected function processFile($destination, $field = 'file')
    {
        $filename = '';
        if ($this->fileIsUploaded($field)) 
        {
            $filename = $_FILES[$field]['name'];
            if ($filename && file_exists($destination . $filename)) 
            {
                $parts = pathinfo($filename);
                $filename = $parts['filename'] . '-' .date('YmdHis') . '.' . $parts['extension'];
            }
            copy($_FILES[$field]['tmp_name'], $destination . $filename);
        }
        return $filename;
    }

    /**
     * Add new files to destination directory
     *
     * @param string $destination
     * @param string $field
     * @return array
     */
    protected function processFiles($destination, $field = 'file')
    {
        $filenames = [];
        foreach ($_FILES[$field]['name'] as $key => $filename) 
        {
            if ($this->fileIsUploaded($field, $key)) 
            {
                if ($filename && file_exists($destination . $filename)) 
                {
                    $parts = pathinfo($filename);
                    $filename = $parts['filename'] . '-' .date('YmdHis') . '.' . $parts['extension'];
                }
                copy($_FILES[$field]['tmp_name'][$key], $destination . $filename);
                $filenames[] = $filename;
            }
        }
        return $filenames;
    }

    /**
     * Check if file is uploaded
     *
     * @param string $field
     * @param int $key
     * @return bool
     */
    protected function fileIsUploaded($field = 'file', $key = null)
    {
        if (is_null($key)) 
        {
            return !empty($_FILES[$field]['name']) && is_uploaded_file($_FILES[$field]['tmp_name']);
        }
        return !empty($_FILES[$field]['name'][$key]) && is_uploaded_file($_FILES[$field]['tmp_name'][$key]);
    }

    /**
     * Download file
     *
     * @param string $url
     * @param string $file
     * @return void
     */
    protected function downloadFile($url, $file)
    {
        $ext = pathinfo($file)['extension'];
        ob_clean();
        header('Content-Type: ' . $this->getMimeType($ext));
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($url));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        flush();
        readfile($url);
        exit;
    }

    /**
     * Return known mime type by file extension
     *
     * @param string $ext
     * @return string
     */
    protected function getMimeType($ext)
    {
        $types = [
            'htm' => 'text/html',
            'exe' => 'application/octet-stream',
            'zip' => 'application/zip',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'php' => 'text/plain',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'html'=> 'text/html',
            'png' => 'image/png',
            'avi' => 'video/x-msvideo',
            'mp3' => 'audio/mpeg',
            'mpeg' => 'video/mpeg',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        if (isset($types[$ext])) {
            return $types[$ext];
        }
        return 'application/force-download';
    }

    /**
     * Recursive directory delete
     *
     * @param $src
     * @return bool
     */
    protected function removeDir($src)
    {
        if (!file_exists($src)) 
        {
            return false;
        }
        $dir = opendir($src);
        while (false !== ( $file = readdir($dir))) 
        {
            if (($file != '.' ) && ( $file != '..' )) 
            {
                $full = $src . '/' . $file;
                if (is_dir($full)) 
                {
                    $this->removeDir($full);
                } 
                else 
                {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        return rmdir($src);
    }

    /**
     * Get current session user name of admin, auditor or client
     *
     * @return string
     */
    protected function getUserName()
    {
        return $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'];
    }

    /**
     * Get current session user category, it can be admin, auditor or client
     *
     * @return string
     */
    protected function getUserCategory()
    {
        return $_SESSION['user']['id_category'];
    }

    /**
     * Format address data to one line
     *
     * @param array $data
     * @return string
     */
    public function formatAddress($data)
    {
        $address = sprintf('%s, %s %s, %s, %s', $data['address_street'], $data['address_zip'], $data['address_town'], $data['address_dpt'], $data['address_country']);

        if (trim(str_replace(',', '', $address))) {
            return $address;
        }
        return '';
    }

    /**
     * Get layout service
     *
     * @return Layout
     */
    public function getLayout()
    {
        if (!$this->layout) {
            $this->layout = new Layout($this->config);
        }
        return $this->layout;
    }

    /**
     * Retrieve user connects
     *
     * @param int $id_user
     * @param int $from
     * @return array
     */
    public function getConnects($id_user, $from = 0)
    {
        $result = $this->db->query("
            select `muc`.*
            from `md_user_connect` as `muc`
            where `muc`.`id_user` = " . $this->_escape($id_user) . "
            order by `muc`.`date_connect` desc
            limit " . $from . ", 15"
        );

        $data = $this->_fetch($result);
        foreach ($data as $k=>$v) {
            $data[$k]['date_value'] = strtotime($v['date_connect']);
            $data[$k]['date'] = format_datetime_st($v['date_connect']);
        }
        return $data;
    }

    /**
     * Build application URL
     *
     * @param string $t1
     * @param string $t2
     * @param string $id
     * @param string $ext
     * @return string
     */
    protected function getUrl($t1, $t2, $id = '', $ext = '')
    {         
        $location = 'index.php?t1=' . $t1 . '&t2=' . $t2;
        if ($id) 
        {
            $location .= '&' . $id;
        }
        if ($ext) 
        {
            $location .= '&' . $ext;
        }
        return $location;
    }

    /**
     * Sanitize value
     *
     * @param string $value
     * @return string
     */
    protected function getSanitized($value)
    {
        return sanitize($value);
    }
}