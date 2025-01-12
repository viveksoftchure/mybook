<?php
/**
 * User model
 */

class UserModel extends Model
{
    /**
     * Admin category id
     *
     * @var string
     */
    const ADMIN_USER_CATEGORY = '1';

    /**
     * Auditor category id
     *
     * @var string
     */
    const AUDITOR_USER_CATEGORY = '2';

    /**
     * Client category id
     *
     * @var string
     */
    const CLIENT_USER_CATEGORY = '3';

    /**
     * Return user status
     *
     * @return array
     */
    public function getUserStatus()
    {
        return [
            'enabled' => [
                'title' => 'Enabled',
                'color' => 'primary',
                'icon' => 'fa-solid fa-user-plus'
            ],
            'disabled' => [
                'title' => 'Disabled',
                'color' => 'danger',
                'icon' => 'fa-solid fa-user-xmark'
            ]
        ];
    }

    /**
     * Return user status data
     *
     * @param string $status
     * @return string
     */
    public function getUserStatusData($status)
    {
        $statuses = $this->getUserStatus();
        return isset($statuses[$status]) ? $statuses[$status] : [];
    }

    /**
     * Add new user
     *
     * @param array $post
     * @return int
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function add($post)
    {
        $category = isset($post['id_category']) ? $post['id_category'] : self::ADMIN_USER_CATEGORY;
        $password = $this->generatePassword();

        if ($category != self::ADMIN_USER_CATEGORY) {
            $post['status'] = 'enabled';
        }

        $result = $this->db->query("
            insert into `md_user` (`id_category`,`status`,`first_name`,`last_name`,`phone_number`,`email`,`password`)
            values (
                ".$this->_escape($category).",
                ".$this->_escape($post['status']).",
                ".$this->_escape($post['first_name']).",
                ".$this->_escape(ifset($post,'last_name', 'NULL')).",
                ".$this->_escape(ifset($post,'phone_number', 'NULL')).",
                ".$this->_escape($post['email']).",
                ".$this->_escape($this->hashPassword($password))."
            )"
        );

        $id_user = $this->db->insert_id;

        if (!empty($post['send_invitation'])) {
           $this->sendInvitation($id_user);
        }

        if ($profile = $this->processImage($id_user)) {
            $result = $result && $this->db->query("
                update `md_user` set
                `profile_picture` = ".$this->_escape($profile)."
                where `id_user`=" . $id_user
            );
        }
        return $id_user;
    }

    /**
     * Get users
     *
     * @param int $exclude_user
     * @return array
     */
    public function getList($exclude_user = 0)
    {
        $result = $this->db->query("
            select `mu`.*, CONCAT(`mu`.`first_name`,' ',`mu`.`last_name`) as `user_name`
            from `md_user` as `mu`
            where `mu`.`status` != 'removed' "
        );

        $data = $this->_fetch($result);

        foreach($data as $k=>$v) {
            $data[$k]['profile'] = $this->getProfilePicturePath($v['id_user']);
            $data[$k]['remove_link'] = get_url('user','search','remove_user=1','id_user=' . $v['id_user']);
            $data[$k]['send_link'] = get_url('user','search','send_invitation=1','id_user=' . $v['id_user']);
            $data[$k]['connect_link'] = get_url('user','connect','id_user=' . $v['id_user']);
        }
        return $data;
    }

    /**
     * Get all users
     *
     * @param int $exclude_category
     * @return array
     */
    public function getAllUsers($exclude_category = 0)
    {
        $ext = '';
        if ($exclude_category) {
            $ext = ' and `mu`.`id_category` != ' . $exclude_category;
        }

        $result = $this->db->query("
            select `mu`.*, CONCAT(`mu`.`first_name`,' ',`mu`.`last_name`) as `user_name`, `muc`.`title` as `category`
            from `md_user` as `mu`, `md_user_category` as `muc`
            where `mu`.`id_category`=`muc`.`id_category`
            and `mu`.`status` != 'removed' " . $ext . "
            order by `mu`.`id_user` asc"
        );

        $data = $this->_fetch($result);

        return $data;
    }

    /**
     * Get users by category
     *
     * @param int $category
     * @return array
     */
    public function getUsersByCategory($category)
    {
        $result = $this->db->query("
            select `mu`.*, CONCAT(`mu`.`first_name`,' ',`mu`.`last_name`) as `user_name`
            from `md_user` as `mu`
            where `mu`.`id_category` = " . $this->_escape($category) . "
            and status='enabled'"
        );
        return $this->_fetch($result);
    }

    /**
     * Check whether the user with the such email already exists
     *
     * @param string $email
     * @param int $id_user
     * @return bool
     */
    public function checkEmail($email, $id_user = 0)
    {
        $result = $this->db->query("
            select `mu`.`id_user`
            from `md_user` as `mu`
            where `mu`.`email` = " . $this->_escape($email) . " and `mu`.`id_user` != " . (int)$id_user
        );
        $data = $this->_fetch($result);
        return !(bool)count($data);
    }

    /**
     * Check whether the user password is correct or not
     *
     * @param string $password
     * @param int $id_user
     * @return bool
     */
    public function checkPassword($password)
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;
        $password = $this->hashPassword($password);

        $result = $this->db->query("
            select `mu`.`password`
            from `md_user` as `mu`
            where `mu`.`id_user` = " . (int)$id_user
        );
        $data = $this->_fetch($result, false);

        return isset($data['password']) && $data['password'] == $password ? true : false;
    }

    /**
     * Get user item by id
     *
     * @param int $id_user
     * @return array
     */
    public function getItem($id_user)
    {
        return $this->getUser($id_user);
    }

    /**
     * Get user by id
     *
     * @param int $id_user
     * @return array
     */
    public function getUser($id_user)
    {
        $result = $this->db->query("
            select `mu`.*, CONCAT(`mu`.`first_name`,' ',`mu`.`last_name`) as `user_name`
            from `md_user` as `mu`
            where `mu`.`id_user` = " . $id_user
        );

        return $this->_fetch($result, false);
    }

    /**
     * Save cookie generated for user
     *
     * @param int $id_user
     * @param string $cookie
     * @return bool
     */
    public function saveCookie($id_user, $cookie)
    {
        return $this->db->query("
            update `md_user` 
            set `cookie`= " . $this->_escape($cookie) . " 
            where `id_user`=" . $id_user
        );
    }

    /**
     * Fetch user by login and password
     *
     * @param string $login
     * @param string $password
     * @return array
     */
    public function getUserByCreds($login, $password)
    {
        $result = $this->db->query("
            select `mu`.*
            from `md_user` as `mu`
            where `mu`.`email` = " . $this->_escape($login) . "
            and `mu`.`password` = " . $this->_escape($this->hashPassword($password)). "
            and status='enabled'"
        );
        return $this->_fetch($result, false);
    }

    /**
     * Fetch user by cookie
     *
     * @param string $cookie
     * @return array
     */
    public function getUserByCookie($cookie)
    {
        $result = $this->db->query("
            select `mu`.*
            from `md_user` as `mu`
            where `mu`.`cookie` = " . $this->_escape($cookie) . "
            and status='enabled'"
        );
        return $this->_fetch($result, false);
    }

    /**
     * Fetch user by email
     *
     * @param string $email
     * @return array
     */
    public function getUserByEmail($email)
    {
        $result = $this->db->query("
            select `mu`.*
            from `md_user` as `mu`
            where `mu`.`email` = " . $this->_escape($email) . "
            and status='enabled'"
        );
        return $this->_fetch($result, false);
    }

    /**
     * Fetch user by reset string
     *
     * @param string $reset
     * @return array
     */
    public function getUserByReset($reset)
    {
        $result = $this->db->query("
            select `mu`.*
            from `md_user` as `mu`
            where `mu`.`reset` = " . $this->_escape($reset) . "
            and status='enabled'"
        );
        return $this->_fetch($result, false);
    }

    /**
     * Set reset string
     *
     * @param int $id_user
     * @param string $reset
     * @return bool
     */
    public function setResetString($id_user, $reset)
    {
        return $this->db->query("
            update `md_user` 
            set `reset`= " . $this->_escape($reset) . " 
            where `id_user`=" . $id_user
        );
    }

    /**
     * Update password
     *
     * @param int $id_user
     * @param string $password
     * @return bool
     */
    public function updatePassword($id_user, $password)
    {
        return $this->db->query("
            update `md_user` set 
            `password`= " . $this->_escape($this->hashPassword($password)) . ",
            `reset`= '',
            `cookie`= ''
            where `id_user`=" . $id_user
        );
    }

    /**
     * Remove user profile
     *
     * @param int $id_user
     * @return bool
     */
    public function removeUser($id_user)
    {
        return $this->setStatus($id_user, 'removed');
    }

    /**
     * Change user status
     *
     * @param int $id_user
     * @param string $status
     * @return bool
     */
    public function setStatus($id_user, $status)
    {
        return $this->db->query( "
            update `md_user` set
            `status` = " . $this->_escape($status) . "
            where `id_user`=" . $id_user
        );
    }

    /**
     * Get category list
     *
     * @return array
     */
    public function getCategories()
    {
        $result = $this->db->query("select * from `md_user_category`");
        return $this->_fetch($result);
    }

    /**
     * Get category by id
     *
     * @param int $id_category
     * @return array
     */
    public function getCategory($id_category)
    {
        $result = $this->db->query("
            select * 
            from `md_user_category`
            where `id_category`=" . $id_category
        );
        return $this->_fetch($result, false);
    }

    /**
     * Get module list
     *
     * @return array
     */
    public function getModules()
    {
        $result = $this->db->query("select * from `md_module`");
        return $this->_fetch($result);
    }

    /**
     * Return list of users for using as select options
     *
     * @return array
     */
    public function getUserOptions()
    {
        $result = $this->db->query("
            select `mu`.*, CONCAT(`mu`.`first_name`,' ',`mu`.`last_name`) as `user_name`
            from `md_user` as `mu`
            where `mu`.`status` = 'enabled'
            order by `user_name`"
        );

        $options = [];
        $data = $this->_fetch($result);
        foreach($data as $k => $v) {
            $options[$v['id_user']] = $v['user_name'];
        }
        return $options;
    }

    /**
     * Get user name by id
     *
     * @param int $id_user
     * @return string
     */
    public function getName($id_user)
    {
        $result = $this->db->query("
            select CONCAT(`mu`.`first_name`,' ',`mu`.`last_name`) as `user_name`
            from `md_user` as `mu`
            where `mu`.`id_user` =" . $id_user
        );

        $data = $this->_fetch($result, false);
        return $id_user ? $data['user_name'] : '';
    }

    /**
     * Update user profile
     *
     * @param array $post
     * @param null|int $id_user
     * @return bool
     */
    public function updateUser($post, $id_user = null)
    {
        $id_user = ($id_user) ? $id_user : $_SESSION['user']['id_user'];
        $user = $this->getUser($id_user);

        if ($profile = $this->processImage($id_user)) {
            $this->cleanProfileImages($user['profile_picture']);
        } else {
            $profile = $user['profile_picture'];
        }

        $status = isset($post['status']) ? $post['status'] : $user['status'];
        $category = isset($post['id_category']) ? $post['id_category'] : $user['id_category'];
        
        return $this->db->query("
            update `md_user` set
            `first_name` = " . $this->_escape($post['first_name']) . ",
            `last_name` = " . $this->_escape($post['last_name']) . ",
            `phone_number` = " . $this->_escape(ifset($post, 'phone_number')) . ",
            `profile_picture` = " . $this->_escape($profile) . ",
            `email` = " . $this->_escape($post['email']) . ",
            `status` = " . $this->_escape($status) . ",
            `id_category` = " . $this->_escape($category) . "
            where `id_user`=" . $id_user
        );
    }

    /**
     * Update user profile
     *
     * @param int $id_user
     * @return bool
     */
    public function updateProfile($id_user)
    {
        if ($profile = $this->processImage($id_user)) {

            $user = $this->getItem($id_user);
            $this->cleanProfileImages($user['profile_picture']);

            return $this->db->query( "
                update `md_user` set
                `profile_picture` = " . $this->_escape($profile) . "
                where `id_user`=" . $id_user
            );
        }
        return false;
    }

    /**
     * Check whether user can login
     *
     * @param array $user
     * @param int $id_entity
     * @return bool
     */
    public function canLogin($user, $id_entity)
    {
        $entity = $this->getEntityModel($user['id_category']);
        if ($entity) {
            return $entity->canLogin($id_entity);
        }
        return true;
    }

    /**
     * Generate new user password
     *
     * @return string
     */
    public function generatePassword()
    {
        return authentication_string(12);
    }

    /**
     * Hash user password
     *
     * @param string $password
     * @return string
     */
    protected function hashPassword($password)
    {
        return md5($password .'_' . $this->config['encrypt_salt']);
    }

    /**
     * Reset user
     *
     * @param int $id_user
     * @return string|bool
     */
    public function resetUser($id_user)
    {
        $password = $this->generatePassword();

        $result = $this->db->query( "
            update `md_user` set
            `password` = " . $this->_escape($this->hashPassword($password)) . ",
            `cookie` = " . $this->_escape('NULL') . ",
            `reset` = " . $this->_escape('NULL') . "
            where `id_user`=" . $id_user
        );
        return ($result) ? $password : false;
    }

    /**
     * Encrypt user passwords
     *
     * @return bool
     */
    public function encryptPasswords()
    {
        return $this->db->query( "
            update `md_user` set
            `password` = MD5(CONCAT(`password`,'_'," . $this->_escape($this->config['encrypt_salt']) . "))"
        );
    }

    /**
     * Get dir of user profile
     *
     * @return string
     */
    public function getDir()
    {
        return getcwd() . '/' . self::PROFILE_PICTURE_DIR . '/';
    }

    /**
     * Return picture profile for the user
     *
     * @param int $id_user
     * @param string $type
     * @return string
     */
    public function getProfilePicturePath($id_user, $type = 'c')
    {
        $user = $this->getItem($id_user);
        $dir = self::PROFILE_PICTURE_DIR;
        $gender = !empty($user['gender']) ? $user['gender'] : 'male';

        if (!$user['profile_picture']) {
            return sprintf('img/anonymous_%s.jpg', $gender);
        }
        return sprintf('%s/%s_%s.jpg', $dir, $user['profile_picture'], $type);
    }

    /**
     * Process profile image
     *
     * Saves profile images in 3 different dimensions for users
     *
     * @param int $id_user
     * @return string
     */
    public function processImage($id_user)
    {
        if (!empty($_FILES['image']['name'])) {
            $parts = pathinfo($_FILES['image']['name']);

            $final_folder = $this->getDir();
            $final_file = time() . rand(111, 999);
            $source_file = $id_user . '.' . $parts['extension'];
            $source_folder = $final_folder . $source_file;
            move_uploaded_file($_FILES['image']['tmp_name'], $source_folder);

            list($orig_width, $orig_height) = getimagesize($source_folder);

            // proportional size
            foreach (['a' => 800, 'b' => 250] as $type => $width) {
                $height = ceil(($width * $orig_height) / $orig_width);
                make_resize_pro($source_folder, $source_file, $final_folder, $final_file . '_' . $type, $width, $height);
            }

            // square size
            foreach (['c' => 100] as $type => $width) {
                $height = $width;
                make_resize_nopro($source_folder, $source_file, $final_folder, $final_file . '_' . $type, $width, $height);
            }
            unlink($source_folder);
            return $final_file;
        }
        return '';
    }

    /**
     * Clean user profile images
     *
     * @param string $profile
     * @return bool
     */
    protected function cleanProfileImages($profile)
    {
        if ($profile) {
            $dir = $this->getDir();
            foreach (['a','b','c'] as $type) {
                $file = sprintf('%s/%s_%s.jpg', $dir, $profile, $type);
                unlink($file);
            }
        }
        return true;
    }

    /**
     * Send reset email
     *
     * @param int $id_user
     * @param string $reset
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendResetEmail($id_user, $reset)
    {
        $layout = $this->getLayout();
        $user = $this->getUser($id_user);

        $data['user'] = $user;
        $data['resetLink'] = $this->config['url'] . get_url('login', 'reset','rs=' . $reset);

        $this->mail->setFrom('no_reply@wpwebguru.com', 'MyBook');
        $this->mail->addAddress($user['email'], $user['user_name']);
        $this->mail->isHTML(true);
        $this->mail->addSubject('Réinitialisez votre mot de passe');
        $this->mail->addBody($layout->fetchView('login/emails/reset_email', $data));

        // define extra variable for email history
        $this->mail->sent_by = 'user';
        $this->mail->id_user = $id_user;
        $this->mail->email_name = 'login/emails/reset_email';

        $result = $this->mail->send();

        echo '<pre>'; print_r($this->mail); echo '</pre>'; exit();
        

        $this->mail->clearAllRecipients();
        return $result;
    }

    /**
     * Send invitation
     *
     * @param int $id_user
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendInvitation($id_user)
    {
        $layout = $this->getLayout();
        $password = $this->resetUser($id_user);
        $user = $this->getUser($id_user);

        $data['user'] = $user;
        $data['password'] = $password;

        $this->mail->setFrom('no_reply@wpwebguru.com', 'WpWebGuru');
        $this->mail->addAddress($user['email'], $user['user_name']);
        $this->mail->isHTML(true);
        $this->mail->Subject = "You're Invited to Join ".$this->config['project_title']."!";
        $this->mail->addBody($layout->fetchView('user/emails/send_invitation', $data));

        $result = $this->mail->send();

        $this->mail->clearAllRecipients();
        return $result;
    }

    /**
     * Update user menu view
     *
     * @param int $id_user
     * @param int $view
     * @return bool
     */
    public function setMenuView($id_user, $view)
    {
        $_SESSION['user']['menu'] = $view;

        return $this->db->query("
            update `md_user` set
            `menu_small` = ".$this->_escape($view)."
            where `id_user`=" . $id_user
        );
    }

    /**
     * Update user mode view
     *
     * @param int $id_user
     * @param int $mode
     * @return bool
     */
    public function setModeView($id_user, $mode)
    {
        $_SESSION['user']['mode'] = $mode;

        return $this->db->query("
            update `md_user` set
            `darkmode` = " . $this->_escape($mode) . "
            where `id_user`=" . $id_user
        );
    }

    /**
     * Return user list as options
     *
     * @param int $category
     * @param bool $asJson
     * @return array
     */
    public function getUsersOptions()
    {
        $result = $this->db->query("
            select `mu`.`id_user`, CONCAT(`mu`.`first_name`,' ',`mu`.`last_name`) as `user_name`
            from `md_user` as `mu`
            where `mu`.`status` = 'enabled'
            order by `mu`.`first_name`"
        );

        $data = $this->_fetch($result, true, true);

        $list = [];
        foreach ($data as $k=>$v) {
            $list[] = [
                'name' => $v['user_name'],
                'id_user' => $v['id_user']
            ];
        }

        return $list;
    }
}