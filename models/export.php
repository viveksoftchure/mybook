<?php
/**
 * Export model
 */

require_once __DIR__.'/../models/user.php';
require_once __DIR__.'/../models/category.php';

class ExportModel extends Model
{
    /**
     * Export file directory
     *
     * @var string
     */
    const EXPORT_FILE_DIR = 'data/export/';

    /**
     * User model
     *
     * @var UserModel
     */
    protected $user;

    /**
     * Category model
     *
     * @var CategoryModel
     */
    protected $category;

    /**
     * Export model constructor
     *
     * @param mysqli $db
     * @param array $config
     */
    public function __construct($db, $config = [])
    {
        $this->user = new UserModel($db, $config);
        $this->category = new CategoryModel($db, $config);

        parent::__construct($db, $config);
    }

    /**
     * Get all exports list
     *
     * @return array
     */
    public function getAllExports()
    {   
        $result = $this->db->query("
            select `me`.*
            from `md_export` as `me` 
            order by `me`.`created_at` desc"
        );

        $data = $this->_fetch($result);

        if ($data) {
            foreach ($data as $key => $item) {
                $data[$key]['link_csv'] = sprintf('%s%s%s', $this->config['url'], self::EXPORT_FILE_DIR, $item['file']);
            }
        }

        return $data;
    }

    /**
     * Get export data by name
     *
     * @param string $name
     * @return array
     */
    public function getExportByName($name)
    {   
        $result = $this->db->query("
            select `me`.*
            from `md_export` as `me` 
            where `me`.`name` = " . $this->_escape($name)
        );

        $data = $this->_fetch($result, false);

        return $data;
    }

    /**
     * Generate new export
     *
     * @param $post
     * @return int|bool
     */
    public function generate_new($post)
    {
        $file = isset($post['file']) ? $post['file'] : '';

        $date = date('d.m.Y');

        $result = true;
        $export_data = [];

        switch ($file) {
            case 'passbook':
                $fields = $this->paymentAttributes();
                $export_data = $this->getPaymentHistory();
                break;
        }              

        foreach (['csv'] as $file_key) {
            $filename = sprintf('%s_%s.%s', $file, $date, $file_key);
            if ($file_key == 'csv') {
                $result = $this->export_csv($file, array_keys($fields), $export_data, $filename);
            }
        }

        return $this->db->query("
            insert into `md_export` (`file`,`created_at`)
            values (
                ".$this->_escape($filename).",
                ".$this->_escape(date('Y-m-d H:i:s'))."
            )"
        );
    }

    /**
     * Return directory of export files, create if not exists
     *
     * @return string
     */
    public function getExportDir()
    {
        $dir = realpath(dirname(dirname(__FILE__))) . '/' . self::EXPORT_FILE_DIR;

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    /**
     * Download export file
     *
     * @param string $file
     * @return void
     */
    public function downloadReviewFile($file)
    {
        $url = $this->getExportDir() . '/' . $file;
        $this->downloadFile($url);
    }

    /**
     * Auditor attributes list for export
     *
     * @return array
     */
    public function paymentAttributes()
    {
        return [
            'id_payment' => 'ID',
            'category' => 'Category',
            'payment_method' => 'Payment method',
            'status' => 'Status',
            'date' => 'Date',
            'title' => 'Title',
            // 'description' => 'Description',
            'amount' => 'Amount',
        ];
    }

    /**
     * Get all payments by date
     *
     * @return array
     */
    public function getPaymentHistory()
    {
        $result = $this->db->query("
            select `mp`.*
            from `md_payment` as `mp` 
            order by `mp`.`date` desc"
        );

        $data = $this->_fetch($result);

        if ($data) {
            foreach ($data as $key => $item) {
                $category_data = $this->category->getCategory($item['id_category']);
                $payment_method_data = $this->category->getCategory($item['payment_method']);

                $data[$key]['date'] = date('Y-m-d H:i', strtotime($item['date']));
                $data[$key]['category'] = $category_data['title'];
                $data[$key]['payment_method'] = $payment_method_data['title'];
            }
        }            

        return $data;
    }

    /**
     * Export data to CSV
     *
     * @param string $file
     * @param array $fields
     * @param array $data
     * @param string $filename
     * @return bool
     */
    public function export_csv($file, $fields, $data, $filename = 'export.csv')
    {
        $headers = [];
        
        if($file=='passbook'):
            $attributes = $this->paymentAttributes();
        endif;

        foreach ($fields as $fk => $field) {
            $headers[] = $attributes[$field];
        }

        $fp = fopen($this->getExportDir().$filename, 'w+');
        fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, $headers,';');

        // echo '<pre>'; print_r($headers); echo '</pre>'; exit;

        foreach ($data as $line) {
            $row = [];
            foreach ($fields as $fk => $field) {
                $row[] = $line[$field];
            }
            fputcsv($fp, $row,';');
        }

        // echo '<pre>'; print_r($row); echo '</pre>'; exit;

        fclose($fp);

        return true;
    }
}