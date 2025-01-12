<?php
/**
 * Weight model
 */
class WeightModel extends Model
{
    /**
     * Return From months
     *
     * @return array
     */
    public function getFromMonths($from = '')
    {
        $options = [];
        $from = $from ? $from : date('Y-m-d', strtotime('-1 year'));
              
        for ($y=date('Y', strtotime($from)); $y <= date('Y') ; $y++) { 
            $start_month = $y == date('Y', strtotime($from)) ? date('m', strtotime($from)) : 1;
            $last_month = $y == date('Y', strtotime($from)) ? 12 : date('m');

            for ($m = $start_month; $m <= $last_month ; $m++) { 
                $month = $m > 9 ? $m : '0'.$m;
                $month = str_replace('00', '0', $month);
                
                $key = sprintf('%s-%s-01 00:00:00', $y, $month);
                // $options[$key] = date('F Y', strtotime($key));
                $options[$key] = date('F Y', strtotime($key));
            }
        }            

        return $options;
    }

    /**
     * Return To months
     *
     * @return array
     */
    public function getToMonths($from = '')
    {
        $options = [];
        $from = $from ? $from : date('Y-m-d', strtotime('-1 year'));
              
        for ($y=date('Y', strtotime($from)); $y <= date('Y') ; $y++) { 
            $start_month = $y == date('Y', strtotime($from)) ? date('m', strtotime($from)) : 1;
            $last_month = $y == date('Y', strtotime($from)) ? 12 : date('m');

            for ($m = $start_month; $m <= $last_month ; $m++) { 
                $month = $m > 9 ? $m : '0'.$m;
                $month = str_replace('00', '0', $month);

                $total_day = $month == date('m') ? date('d') : date('t', strtotime(sprintf('%s-%s-01', $y, $month)));
                $key = sprintf('%s-%s-%s 23:59:59', $y, $month, $total_day);
                // $options[$key] = date('F Y', strtotime($key));
                $options[$key] = date('F Y', strtotime($key));
            }
        }
        
        return $options;
    }

    /**
     * Get all data
     *
     * @return array
     */
    public function getAllData()
    {  
        $result = $this->db->query("
            select `mv2`.*
            from `md_weight` as `mv2` "
        );

        $data = $this->_fetch($result);

        if ($data) {
            foreach ($data as $key => $item) {
                $data[$key]['x_date'] = strtotime($item['date']);
            }
        }

        return $data;
    }

    /**
     * Create used ram data for graph
     *
     * @return array
     */
    public function getWeightData($from, $to)
    {
        $date = date('Y-m-d');
        $parts = [];
        $searchQuery = '1=1';

        if ($from!='') {
            $parts[] = '`mw`.`date` >= ' . $this->_escape($from);
        }
        if ($to!='') {
            $parts[] = '`mw`.`date` <= ' . $this->_escape($to);
        }

        if (count($parts)) {
            $searchQuery = '(' . implode(' AND ', $parts) . ')';
        }

        $result = $this->db->query("
            select `mw`.*
            from `md_weight` as `mw` 
            where " . $searchQuery
        );

        $data = $this->_fetch($result);

        $weight_data = [];
        if ($data) {
            foreach ($data as $key => $item) {
                $weight_data[] = [
                    'year' => date('Y-m-d', strtotime($item['date'])),
                    'value' => (float)$item['weight'],
                ];
            }
        }
        
        return $weight_data;
    }

    /**
     * Add new payment
     *
     * @param array $post
     * @return int|bool
     */
    public function addWeight($post)
    {
        $date = date('Y-m-d H:i:s');

        $result = $this->db->query("
            insert into `md_weight` (`weight`, `date`)
            values (
                ".$this->_escape($post['weight']).",
                ".$this->_escape($date)."
            )"
        );

        return $result;
    }
}