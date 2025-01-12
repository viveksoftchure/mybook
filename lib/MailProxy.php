<?php
require_once __DIR__.'/phpmailer/src/Exception.php';
require_once __DIR__.'/phpmailer/src/PHPMailer.php';
require_once __DIR__.'/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailProxy extends PHPMailer
{
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
     * Recipient email address
     *
     * @var string
     */
    protected $toEmailList = '';

    /**
     * CC recipient email address
     *
     * @var string
     */
    protected $toEmailListCC = '';

    /**
     * Recipient user name
     *
     * @var string
     */
    protected $toNameList = '';

    /**
     * CC recipient user name
     *
     * @var string
     */
    protected $toNameListCC = '';

    /**
     * Skip including header and footer templates
     *
     * @var bool
     */
    public $skipTemplate = false;

    /**
     * Class constructor
     *
     * @param mysqli $db
     * @param array $config
     * @param mixed $exceptions
     */
    public function __construct($db, $config, $exceptions = null)
    {
        $this->config = $config;
        $this->db = $db;
        $this->CharSet = 'UTF-8';

        parent::__construct($exceptions);
    }

    /**
     * Set email From
     *
     * @return bool
     */
    public function addFrom()
    {
        $address = isset($template_data['email_from']) ? $template_data['email_from'] : '';
        $name = 'L’équipe Certifopac';
        
        if (!empty($address)) {
            return $this->setFrom($address, $name);
        } else if (empty($this->From)) {
            return $this->setFrom('no_reply@certifopac.fr', 'L’équipe Certifopac');
        }
    }

    /**
     * Set email subject
     *
     * @param string $subject
     */
    public function addSubject($subject = '')
    {
        $this->Subject = $subject;
    }

    /**
     * Set email body
     *
     * @param string $body
     * @return string
     */
    public function addBody($body = '', $emailFrom = '')
    {
        $this->Body = $customBody . $body;
        $data['project_title'] = $this->config['project_title'];

        if (!$this->skipTemplate) {
            $layout = new Layout($this->config, 'en');
            $header = $layout->fetchView('emails/header', $data);
            $footer = $layout->fetchView('emails/footer_no_reply', $data);

            $this->Body = $header . $this->Body . $footer;
        }
    }

    /**
     * Add a "To" address
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     * @throws Exception
     */
    public function addAddress($address, $name = '')
    {
        $this->toEmailList = $address;
        $this->toNameList = $name;

        list($address, $name) = $this->processAddress($address, $name);
        return $this->addOrEnqueueAnAddress('to', $address, $name);
    }

    /**
     * Add a "CC" address
     *
     * @param string $address
     * @param string $name
     *
     * @throws Exception
     * @return bool
     */
    public function addCC($address, $name = '')
    {
        $this->toEmailListCC = $address;
        $this->toNameListCC = $name;

        list($address, $name) = $this->processAddress($address, $name);
        return $this->addOrEnqueueAnAddress('cc', $address, $name);
    }

    /**
     * Process address in case of development mode, redirect all emails to admin addresses
     *
     * @param string $address
     * @param string $name
     * @return array
     */
    public function processAddress($address, $name = '')
    {
        return [$address, $name];
    }

    /**
     * Send message and add record to email history
     *
     * @return bool
     * @throws Exception
     */
    public function send()
    {
        $this->addFrom();
        $result = parent::send();
        
        return $result;
    }

    /**
     * Clear all recipient types
     */
    public function clearAllRecipients()
    {
        $this->toEmailList = '';
        $this->toNameList = '';
        $this->toEmailListCC = '';
        $this->toNameListCC = '';

        parent::clearAllRecipients();
    }
}