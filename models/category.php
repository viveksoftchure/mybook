<?php
/**
 * Category model
 */
class CategoryModel extends Model
{
    /**
     * Return Payments type
     *
     * @return array
     */
    public function getPaymentTypes()
    {
        $options = [
            'credit' => [
                'icon' => 'fa fa-file',
                'title' => 'Credit',
            ],
            'debit' => [
                'icon' => 'fa fa-briefcase',
                'title' => 'Debit',
            ]
        ];

        return $options;
    }

    /**
     * Return Kind data
     *
     * @param string $kind
     * @return string
     */
    public function getKindData($kind)
    {
        $options = $this->getKindOptions();
        return isset($options[$kind]) ? $options[$kind] : '';
    }

    /**
     * Add new category
     *
     * @param array $post
     * @return int|bool
     */
    public function add($post)
    {
        $result = $this->db->query("
            insert into `md_category` (`title`,`type`,`icon`,`icon_color`,`icon_bg`,`description`)
            values (
                ".$this->_escape($post['title']).",
                ".$this->_escape($post['type']).",
                ".$this->_escape($post['icon']).",
                ".$this->_escape($post['icon_color']).",
                ".$this->_escape($post['icon_bg']).",
                ".$this->_escape($post['description'])."
            )"
        );

        $id_category = $this->db->insert_id;
        
        return $id_category;
    }

    /**
     * Update category
     *
     * @param array $post
     * @param null|int $id_category
     * @return bool
     */
    public function updateCategory($post, $id_category = null)
    {
        $result = $this->db->query("
            update `md_category` set
            `title` = " . $this->_escape($post['title']) . ",
            `type` = " . $this->_escape($post['type']) . ",
            `icon` = " . $this->_escape($post['icon']) . ",
            `icon_color` = " . $this->_escape($post['icon_color']) . ",
            `icon_bg` = " . $this->_escape($post['icon_bg']) . ",
            `description` = " . $this->_escape($post['description']) . "
            where `id_category`=" . $id_category
        );

        return $result;
    }

    /**
     * Remove category
     *
     * @param null|int $id_category
     * @return bool
     */
    public function removeCategory($id_category)
    {
        $result = $this->db->query("
            delete from `md_category` 
            where `id_category`=".$id_category
        );

        return $result;
    }

    /**
     * Get all Categories
     *
     * @return array
     */
    public function getAllCategories($type = '')
    {
        $ext = '1=1';
        if ($type) {
            $ext.= " and `mc`.`type` = ".$this->_escape($type);
        }

        $result = $this->db->query("
            select `mc`.*
            from `md_category` as `mc` 
            where ".$ext." 
            order by `mc`.`title` asc"
        );

        $data = $this->_fetch($result);

        if ($data) {
            foreach ($data as $key => $item) {
                $data[$key]['title'] = html_entity_decode($item['title'], ENT_QUOTES);
                $data[$key]['description'] = html_entity_decode($item['description'], ENT_QUOTES);
                $data[$key]['remove_link'] = get_url('passbook', 'category', 'remove_category=1&id_category=' . $item['id_category']);
            }
        }

        return $data;
    }

    /**
     * Get all Payment types
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $result = $this->db->query("
            select `mc`.*
            from `md_category` as `mc` 
            where `mc`.`type` = 'payment-method' 
            order by `mc`.`title` asc"
        );

        $data = $this->_fetch($result);

        if ($data) {
            foreach ($data as $key => $item) {
                $data[$key]['title'] = html_entity_decode($item['title'], ENT_QUOTES);
                $data[$key]['description'] = html_entity_decode($item['description'], ENT_QUOTES);
                $data[$key]['remove_link'] = get_url('passbook', 'category', 'remove_category=1&id_category=' . $item['id_category']);
            }
        }

        return $data;
    }

    /**
     * Get all Payment Categories
     *
     * @return array
     */
    public function getPaymentCategories()
    {
        $result = $this->db->query("
            select `mc`.*
            from `md_category` as `mc` 
            where `mc`.`type` = 'payment-category' 
            order by `mc`.`title` asc"
        );

        $data = $this->_fetch($result);

        if ($data) {
            foreach ($data as $key => $item) {
                $data[$key]['title'] = html_entity_decode($item['title'], ENT_QUOTES);
                $data[$key]['description'] = html_entity_decode($item['description'], ENT_QUOTES);
                $data[$key]['remove_link'] = get_url('passbook', 'category', 'remove_category=1&id_category=' . $item['id_category']);
            }
        }

        return $data;
    }

    /**
     * Get category
     *
     * @param null|int $id_category
     * @return array
     */
    public function getCategory($id_category)
    {
        $result = $this->db->query("
            select `me`.*
            from `md_category` as `me` 
            where `me`.`id_category`=".$id_category
        );

        $data = $this->_fetch($result, false);

        if ($data) {
            $data['title'] = html_entity_decode($data['title'], ENT_QUOTES);
            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES);
            $data['remove_link'] = get_url('passbook', 'category', 'remove_category=1&id_category=' . $data['id_category']);
        }

        return $data;
    }
}