<?php
/**
 * Passbook model
 */

require_once __DIR__.'/../models/category.php';
require_once __DIR__.'/../models/user.php';

class PassbookModel extends Model
{
    /**
     * Category model
     *
     * @var CategoryModel
     */
    protected $category;
    /**
     * User model
     *
     * @var UserModel
     */
    protected $user;

    /**
     * Passbook model constructor
     *
     * @param mysqli $db
     * @param array $config
     */
    public function __construct($db, $config)
    {
        $this->category = new CategoryModel($db, $config);
        $this->user = new UserModel($db, $config);
        parent::__construct($db, $config);
    }

    /**
     * Return Payment status options
     *
     * @return array
     */
    public function getPaymentStatusOptions()
    {
        $options = [
            'pending' => [
                'title' => 'Pending',
                'color' => 'default',
                'icon' => 'fa fa-file'
            ],
            'completed' => [
                'title' => 'Completed',
                'color' => 'primary',
                'icon' => 'fa fa-file'
            ],
            'failed' => [
                'title' => 'Failed',
                'color' => 'danger',
                'icon' => 'fa fa-file'
            ],
            'refund' => [
                'title' => 'Refund',
                'color' => 'primary',
                'icon' => 'fa fa-file'
            ]
        ];

        return $options;
    }

    /**
     * Return Payment status data
     *
     * @param string $type
     * @return string
     */
    public function getPaymentStatusData($type)
    {
        $options = $this->getPaymentStatusOptions();
        return isset($options[$type]) ? $options[$type] : [];
    }

    /**
     * Return Payment Type options
     *
     * @return array
     */
    public function getPaymentTypeOptions()
    {
        $options = [
            'dr' => [
                'title' => 'Debit',
                'color' => 'danger',
                'icon' => 'fa fa-file'
            ],
            'cr' => [
                'title' => 'Credit',
                'color' => 'primary',
                'icon' => 'fa fa-file'
            ]
        ];

        return $options;
    }

    /**
     * Return Payment Type data
     *
     * @param string $type
     * @return string
     */
    public function getPaymentTypeData($type)
    {
        $options = $this->getPaymentTypeOptions();
        return isset($options[$type]) ? $options[$type] : [];
    }

    /**
     * Add new payment
     *
     * @param array $post
     * @return int|bool
     */
    public function add($post)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;
        $kind   = isset($post['kind']) ? $post['kind'] : '';

        $date = date('Y-m-d', strtotime($post['date'])).' '.$post['time'];
        $items = isset($post['item']) ? serialize($post['item']) : '';

        $result = $this->db->query("
            insert into `md_payment` (`id_user`, `id_category`,`payment_method`,`payment_type`,`paid_for`,`status`,`date`,`paid_to`,`description`,`amount`, `items`)
            values (
                ".$this->_escape($id_user).",
                ".$this->_escape($post['id_category']).",
                ".$this->_escape($post['payment_method']).",
                ".$this->_escape($post['payment_type']).",
                ".$this->_escape($post['paid_for']).",
                ".$this->_escape($post['status']).",
                ".$this->_escape($date).",
                ".$this->_escape($post['paid_to']).",
                ".$this->_escape($post['description']).",
                ".$this->_escape($post['amount']).",
                ".$this->_escape($items)."
            )"
        );

        $id_payment = $this->db->insert_id;

        return [
            'id' => $id_payment,
            'status' => $post['status'],
            'id_category' => $post['id_category'],
            'payment_method' => $post['payment_method'],
            'payment_type' => $post['payment_type'],
            'paid_for' => $post['paid_for'],
            'paid_for_name' => $this->user->getName($post['paid_for']),
            'date' => date('d M Y h:i', strtotime($date)),
            'year' => date('Y', strtotime($date)),
            'paid_to' => $post['paid_to'],
            'description' => $post['description'],
            'debit' => ($post['payment_type']=='dr'?$post['amount']:''),
            'credit' => ($post['payment_type']=='cr'?$post['amount']:''),
            'items' => $items,
            'status_data' => $this->getPaymentStatusData($post['status']),
            'category_data' => $this->category->getCategory($post['id_category']),
            'payment_method_data' => $this->category->getCategory($post['payment_method']),
            'payment_type_data' => $this->getPaymentTypeData($post['payment_type']),
            'remove_link' => get_url('passbook', 'default', 'remove_payment=1&id_payment=' . $id_payment),
        ];
    }

    /**
     * Update payment
     *
     * @param int $id_payment
     * @param array $post
     * @return bool
     */
    public function updatePayment($id_payment, $post)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;
        $date = date('Y-m-d', strtotime($post['date'])).' '.$post['time'];
        $items = isset($post['item']) ? serialize($post['item']) : '';

        $result = $this->db->query("
            update `md_payment` set
            `status` = " . $this->_escape($post['status']) . ",
            `id_category` = " . $post['id_category'] . ",
            `payment_method` = " . $post['payment_method'] . ",
            `payment_type` = " . $this->_escape($post['payment_type']) . ",
            `paid_for` = " . $this->_escape($post['paid_for']) . ",
            `date` = " . $this->_escape($date) . ",
            `paid_to` = " . $this->_escape($post['paid_to']) . ",
            `description` = " . $this->_escape($post['description']) . ",
            `amount` = " . $this->_escape($post['amount']) . ",
            `items` = " . $this->_escape($items) . ",
            `updated_at` = " . $this->_escape(date('Y-m-d H:i:s')) . "
            where `id_payment`= " . $id_payment . "
            and `id_user` = " .$id_user
        );

        return [
            'id' => $id_payment,
            'status' => $post['status'],
            'id_category' => $post['id_category'],
            'payment_method' => $post['payment_method'],
            'payment_type' => $post['payment_type'],
            'paid_for' => $post['paid_for'],
            'paid_for_name' => $this->user->getName($post['paid_for']),
            'date' => date('d M Y h:i', strtotime($date)),
            'year' => date('Y', strtotime($date)),
            'paid_to' => $post['paid_to'],
            'description' => $post['description'],
            'debit' => ($post['payment_type']=='dr'?$post['amount']:''),
            'credit' => ($post['payment_type']=='cr'?$post['amount']:''),
            'items' => $items,
            'status_data' => $this->getPaymentStatusData($post['status']),
            'category_data' => $this->category->getCategory($post['id_category']),
            'payment_method_data' => $this->category->getCategory($post['payment_method']),
            'payment_type_data' => $this->getPaymentTypeData($post['payment_type']),
            'remove_link' => get_url('passbook', 'default', 'remove_payment=1&id_payment=' . $id_payment),
        ];
    }

    /**
     * Remove event
     *
     * @param null|int $id_payment
     * @return bool
     */
    public function removePayment($id_payment)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $result = $this->db->query("
            delete from `md_payment` 
            where `id_payment` = ".$id_payment . "
            and `id_user` = " .$id_user
        );

        return $result;
    }

    /**
     * Get all payments
     *
     * @return array
     */
    public function getPaymentHistory($filter = [])
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $limit = '';
        $parts = [];
        $searchQuery = '1=1';
        $userCategory = isset($_SESSION['user']['id_category']) ? $_SESSION['user']['id_category'] : '';

        $keywords = isset($filter['keywords']) ? $filter['keywords'] : '';
        $id_category = isset($filter['id_category']) ? $filter['id_category'] : '';
        $date_start = isset($filter['date_start']) ? $filter['date_start'] : '';
        $date_end = isset($filter['date_end']) ? $filter['date_end'] : '';
        $paid_for = isset($filter['paid_for']) ? $filter['paid_for'] : '';
        $payment_method = isset($filter['payment_method']) ? $filter['payment_method'] : '';

        //search by keywords on searchable fields
        if (!empty($keywords)) {
            $searchable_fields = [
                'mp' => ['title', 'description']
            ];
            $chunks = [];
            foreach ($searchable_fields as $alias => $fields) {
                foreach ($fields as $field) {
                    $chunks[] = "`" . $alias . "`.`" . $field . "` LIKE '%" . trim($keywords) . "%'";
                }
            }
            $parts[] = '(' . implode(' OR ', $chunks) . ')';
        }

        //search by id_category
        if (!empty($id_category)) {
            $parts[] = '`mp`.`id_category` = ' . $id_category;
        }

        //search by payment_method
        if (!empty($payment_method)) {
            $parts[] = '`mp`.`payment_method` = ' . $this->_escape($payment_method);
        }

        //search by date_start
        if (!empty($date_start)) {
            $parts[] = '`mp`.`date` >= ' . $this->_escape($date_start);
        }

        //search by date_start
        if (!empty($date_end)) {
            $parts[] = '`mp`.`date` <= ' . $this->_escape($date_end);
        }

        //search by paid_for
        if (!empty($paid_for)) {
            $parts[] = '`mp`.`paid_for` = ' . $this->_escape($paid_for);
        }

        if (count($parts)) {
            $searchQuery = '(' . implode(' AND ', $parts) . ')';
        }

        $result = $this->db->query("
            select `mp`.*
            from `md_payment` as `mp` 
            where " . $searchQuery . "
            and `mp`.`id_user` = " .$id_user. "
            order by `mp`.`date` desc"
        );

        $data = $this->_fetch($result);

        $history = [];
        if ($data) {
            foreach ($data as $key => $item) {
                $date = $item['date'] ? date('Y-m-d', strtotime($item['date'])) : '';
                $time = $item['date'] ? date('H:i', strtotime($item['date'])) : '';

                $history_key = $date ? date('Y', strtotime($date)) : '';

                $item_data = $item;
                $item_data['paid_for_name'] = $this->user->getName($item['paid_for']);
                $item_data['status_data'] = $this->getPaymentStatusData($item['status']);
                $item_data['category_data'] = $this->category->getCategory($item['id_category']);
                $item_data['payment_method_data'] = $this->category->getCategory($item['payment_method']);
                $item_data['payment_type_data'] = $this->getPaymentTypeData($item['payment_type']);
                $item_data['remove_link'] = get_url('passbook', 'default', 'remove_payment=1&id_payment=' . $item['id_payment']);

                $history[$history_key][] = $item_data;
            }
        }

        return $history;
    }

    /**
     * Get all payments by date
     *
     * @return array
     */
    public function getPaymentHistoryArray()
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $result = $this->db->query("
            select `mp`.*
            from `md_payment` as `mp` 
            where `mp`.`id_user` = " .$id_user. "
            order by `mp`.`date` desc"
        );

        $data = $this->_fetch($result);

        $history = [];
        if ($data) {
            foreach ($data as $key => $item) {
                $date = $item['date'] ? date('Y-m-d', strtotime($item['date'])) : '';
                $time = $item['date'] ? date('H:i', strtotime($item['date'])) : '';

                $history_key = $date ? date('Y-m', strtotime($date)) : '';

                $item_data = $item;
                $item_data['paid_for_name'] = $this->user->getName($item['paid_for']);
                $item_data['category_data'] = $this->category->getCategory($item['id_category']);
                $item_data['payment_method_data'] = $this->category->getCategory($item['payment_method']);
                $item_data['payment_type_data'] = $this->getPaymentTypeData($item['payment_type']);

                $history[$history_key][] = $item_data;
            }
        }

        // echo '<pre>'; print_r($history); echo '</pre>'; exit;              

        return $history;
    }

    /**
     * Get event
     *
     * @param null|int $id_payment
     * @return array
     */
    public function getPayment($id_payment)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $result = $this->db->query("
            select `mp`.*
            from `md_payment` as `mp` 
            where `mp`.`id_payment` = ".$id_payment . "
            and `mp`.`id_user` = " .$id_user
        );

        $data = $this->_fetch($result, false);

        if ($data) {
            $date = $data['date'];

            $data['paid_for_name'] = $this->user->getName($data['paid_for']);
            $data['paid_to'] = html_entity_decode($data['paid_to'], ENT_QUOTES);
            $data['title'] = html_entity_decode($data['title'], ENT_QUOTES);
            $data['date'] = date('Y-m-d', strtotime($date));
            $data['time'] = date('H:i', strtotime($date));
            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES);
            $data['remove_url'] = get_url('passbook', 'default', 'remove_payment=1&id_payment=' . $id_payment);
            $data['items'] = unserialize(str_replace('&quot;', '"', $data['items']));
        }

        return $data;
    }

    /**
     * Get events by category
     *
     * @param null|int $id_category
     * @return array
     */
    public function getPaymentsByCategory($id_category)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $result = $this->db->query("
            select `mp`.*
            from `md_payment` as `mp` 
            where `mp`.`id_category` = ".$id_category . "
            and `mp`.`id_user` = " .$id_user . "
            order by position asc"
        );

        $data = $this->_fetch($result);

        if ($data) {
            foreach ($data as $key => $value) {
                $data[$key]['paid_for_name'] = $this->user->getName($value['paid_for']);
                $data[$key]['title'] = html_entity_decode($value['title'], ENT_QUOTES);
                $data[$key]['description'] = html_entity_decode($value['description'], ENT_QUOTES);
                $data[$key]['content'] = htmlspecialchars_decode(utf8_decode($value['content']));
                $data[$key]['remove_url'] = get_url('passbook', 'default', 'remove_payment=1&id_payment=' . $value['id_payment']);
            }
        }

        return $data;
    }

    /**
    * Date range
    *
    * @param $first
    * @param $last
    * @param string $step
    * @param string $format
    * @return array
    */
    function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d H:i:s') 
    {
        $dates = [];
        $current = strtotime( $first );
        $last = strtotime( $last );

        while( $current <= $last ) {

            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }

        return $dates;
    }

    /**
     * Do audit search
     *
     * @param string $keywords
     * @param int $id_category
     * @param int $payment_method
     * @param bool $pagination
     * @param int $activePage
     * @return array
     */
    public function getSearch($keywords, $id_category = '', $payment_method = '', $pagination = false, $activePage = 1)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $limit = '';
        $parts = [];
        $searchQuery = '1=1';
        $userCategory = isset($_SESSION['user']['id_category']) ? $_SESSION['user']['id_category'] : '';

        //search by keywords on searchable fields
        if (!empty($keywords)) {
            $searchable_fields = [
                'mp' => ['title', 'description']
            ];
            $chunks = [];
            foreach ($searchable_fields as $alias => $fields) {
                foreach ($fields as $field) {
                    $chunks[] = "`" . $alias . "`.`" . $field . "` LIKE '%" . trim($keywords) . "%'";
                }
            }
            $parts[] = '(' . implode(' OR ', $chunks) . ')';
        }

        //search by id_category
        if (!empty($id_category)) {
            $parts[] = '`mp`.`id_category` = ' . $id_category;
        }

        //search by payment_method
        if (!empty($payment_method)) {
            $parts[] = '`mp`.`payment_method` = ' . $this->_escape($payment_method);
        }

        $parts[] = "`mp`.`id_user` = " .$id_user;

        if (count($parts)) {
            $searchQuery = '(' . implode(' AND ', $parts) . ')';
        }

        if($pagination) {
             $resultCount = $this->db->query("
                select `mp`.*
                from `md_payment` as `mp`
                where " . $searchQuery . " 
                order by `mp`.`date` desc "
            );
            $data = $this->_fetch($resultCount);
            $countVal = count($data);
            if($countVal > $pagination) {
                if ($activePage < 1) {
                   $activePage = 1;
                }
                $offset = ($activePage - 1) * $pagination;
                $limit = "limit $offset,".$pagination;
            }
        }  

        $result = $this->db->query("
            select `mp`.*
            from `md_payment` as `mp`
            where " . $searchQuery . " 
            order by `mp`.`date` desc ".$limit
        );

        $data = $this->_fetch($result);

        $history = [];
        if ($data) {
            foreach ($data as $key => $item) {
                $date = $item['date'] ? date('Y-m-d', strtotime($item['date'])) : '';
                $time = $item['date'] ? date('H:i', strtotime($item['date'])) : '';

                $history_key = $date ? date('Y-m', strtotime($date)) : '';

                $item_data = $item;
                $item_data['paid_for_name'] = $this->user->getName($item['paid_for']);
                $item_data['category_data'] = $this->category->getCategory($item['id_category']);
                $item_data['payment_method_data'] = $this->category->getCategory($item['payment_method']);
                $item_data['payment_type_data'] = $this->getPaymentTypeData($item['payment_type']);

                $history[$history_key][] = $item_data;
            }
        }

        if($pagination && $limit!="") {
            $history = ['totalRows'=> $countVal, 'data'=> $history];
        }  
        
        return $history;
    }

    /**
     * Get all payments by date
     *
     * @return array
     */
    public function getPayments()
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $result = $this->db->query("
            select `mp`.*
            from `md_payment` as `mp` 
            where `mp`.`id_user` = " .$id_user. "
            order by `mp`.`date` desc"
        );

        $data = $this->_fetch($result);

        if ($data) {
            foreach ($data as $key => $item) {
                $data[$key]['paid_for_name'] = $this->user->getName($item['paid_for']);
                $data[$key]['category_data'] = $this->category->getCategory($item['id_category']);
                $data[$key]['payment_method_data'] = $this->category->getCategory($item['payment_method']);
                $data[$key]['payment_type_data'] = $this->getPaymentTypeData($item['payment_type']);
            }
        }          

        return $data;
    }

    /**
     * Update payment created_at
     *
     * @param int $id_payment
     * @param string $date
     * @return bool
     */
    public function updatePaymentCreatedDate($id_payment, $date)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;
        
        $result = $this->db->query("
            update `md_payment` set
            `created_at` = " . $this->_escape($date) . "
            where `id_payment`=" . $id_payment."
            and `id_user`=" . $id_user
        );

        return $result;
    }

    /**
     * Update payment description
     *
     * @param int $id_payment
     * @param string $description
     * @return bool
     */
    public function addPaymentTitleToDescription($id_payment, $description)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;
        
        $result = $this->db->query("
            update `md_payment` set
            `description` = " . $this->_escape($description) . ",
            `updated_at` = " . $this->_escape(date('Y-m-d H:i:s')) . "
            where `id_payment`=" . $id_payment."
            and `id_user`=" . $id_user
        );

        return $result;
    }

    /**
     * Get all income by duration
     *
     * @param string $duration
     * @return int
     */
    public function getIncomeCount($duration = '')
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $ext = "";
        switch ($duration) {
            case 'today':
                $ext = " AND DATE(`mp`.`date`) = CURDATE()";
                break;
            case 'yesterday':
                $ext = " AND DATE(`mp`.`date`) = CURDATE() - INTERVAL 1 DAY";
                break;
            case 'week': // Current week: Monday to Sunday
                $ext = " AND WEEK(`mp`.`date`, 1) = WEEK(CURDATE(), 1) AND YEAR(`mp`.`date`) = YEAR(CURDATE())";
                break;
            case 'month': // Current month
                $ext = " AND MONTH(`mp`.`date`) = MONTH(CURDATE()) AND YEAR(`mp`.`date`) = YEAR(CURDATE())";
                break;
            case 'last_month': // last month
                $ext = " AND MONTH(`mp`.`date`) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(`mp`.`date`) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
                break;
            case 'year': // Current year
                $ext = " AND YEAR(`mp`.`date`) = YEAR(CURDATE())";
                break;
            case 'last_year': // last year
                $ext = " AND YEAR(`mp`.`date`) = YEAR(CURDATE() - INTERVAL 1 YEAR)";
                break;
            default:
                $ext = "";
        }

        $result = $this->db->query("
            select SUM(`mp`.`amount`) as `total_amount`
            from `md_payment` as `mp`
            where `mp`.`payment_type` = 'cr'
            and `mp`.`id_user`=" . $id_user. "
            ".$ext."
            order by `mp`.`date` desc"
        );

        $data = $this->_fetch($result, false);        

        return isset($data['total_amount']) ? $data['total_amount'] : 0;
    }

    /**
     * Get all expenses by duration
     *
     * @param string $duration
     * @return int
     */
    public function getExpenseCount($duration = '')
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        $ext = "";
        switch ($duration) {
            case 'today':
                $ext = " AND DATE(`mp`.`date`) = CURDATE()";
                break;
            case 'yesterday':
                $ext = " AND DATE(`mp`.`date`) = CURDATE() - INTERVAL 1 DAY";
                break;
            case 'week': // Current week: Monday to Sunday
                $ext = " AND WEEK(`mp`.`date`, 1) = WEEK(CURDATE(), 1) AND YEAR(`mp`.`date`) = YEAR(CURDATE())";
                break;
            case 'month': // Current month
                $ext = " AND MONTH(`mp`.`date`) = MONTH(CURDATE()) AND YEAR(`mp`.`date`) = YEAR(CURDATE())";
                break;
            case 'last_month': // last month
                $ext = " AND MONTH(`mp`.`date`) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(`mp`.`date`) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
                break;
            case 'year': // Current year
                $ext = " AND YEAR(`mp`.`date`) = YEAR(CURDATE())";
                break;
            case 'last_year': // last year
                $ext = " AND YEAR(`mp`.`date`) = YEAR(CURDATE() - INTERVAL 1 YEAR)";
                break;
            default:
                $ext = "";
        }
        
        $result = $this->db->query("
            select SUM(`mp`.`amount`) as `total_amount`
            from `md_payment` as `mp`
            where `mp`.`payment_type` = 'dr'
            and `mp`.`id_user`=" . $id_user. "
            ".$ext."
            order by `mp`.`date` desc"
        );

        $data = $this->_fetch($result, false);        

        return isset($data['total_amount']) ? $data['total_amount'] : 0;
    }

    public function getSavingsCount($duration = '')
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        switch ($duration) {
            case 'month': // Current month
                $income = $this->getIncomeCount('month');
                $expense = $this->getExpenseCount('month');
                break;
            case 'last_month': // Current month
                $income = $this->getIncomeCount('last_month');
                $expense = $this->getExpenseCount('last_month');
                break;
            case 'year': // Current year
                $income = $this->getIncomeCount('year');
                $expense = $this->getExpenseCount('year');
                break;
            case 'year': // Current year
                $income = $this->getIncomeCount('last_year');
                $expense = $this->getExpenseCount('last_year');
                break;
            default:
                $income = 0;
                $expense = 0;
        }

        $savings = ($income - $expense);

        return round($savings, 2);
    }

    public function getIncomeIncrement($duration = '')
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        switch ($duration) {
            case 'month': // Current month
                $currentIncome = $this->getIncomeCount('month');
                $lastIncome = $this->getIncomeCount('last_month');
                break;
            case 'year': // Current year
                $currentIncome = $this->getIncomeCount('year');
                $lastIncome = $this->getIncomeCount('last_year');
                break;
            default:
                $currentIncome = 0;
                $lastIncome = 0;
        }

        if ($lastIncome == 0) {
            return $currentIncome > 0 ? 100 : 0;
        }

        $increment = (($currentIncome - $lastIncome) / $lastIncome) * 100;

        return round($increment, 2);
    }

    public function getExpenseIncrement($duration = '')
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        switch ($duration) {
            case 'month': // Current month
                $currentIncome = $this->getExpenseCount('month');
                $lastIncome = $this->getExpenseCount('last_month');
                break;
            case 'year': // Current year
                $currentIncome = $this->getExpenseCount('year');
                $lastIncome = $this->getExpenseCount('last_year');
                break;
            default:
                $currentIncome = 0;
                $lastIncome = 0;
        }

        if ($lastIncome == 0) {
            return $currentIncome > 0 ? 100 : 0;
        }

        $increment = (($currentIncome - $lastIncome) / $lastIncome) * 100;

        return round($increment, 2);
    }

    public function getSavingIncrement($duration = '')
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        switch ($duration) {
            case 'month': // Current month
                $currentSaving = $this->getSavingsCount('month');
                $lastSaving = $this->getSavingsCount('last_month');
                break;
            case 'year': // Current year
                $currentSaving = $this->getSavingsCount('year');
                $lastSaving = $this->getSavingsCount('last_year');
                break;
            default:
                $currentSaving = 0;
                $lastSaving = 0;
        }

        if ($lastSaving == 0) {
            return $currentSaving > 0 ? 100 : 0;
        }

        $increment = (($currentSaving - $lastSaving) / $lastSaving) * 100;

        return round($increment, 2);
    }
}