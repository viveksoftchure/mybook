<?php
/*
Subject: "You're Invited to Join ".$this->config['project_title']."!";
Description: Send invitation to user
Sent to: User
*/
?>
<p>Hi <strong><?= $data['user']['first_name'].' '. $data['user']['last_name'] ?></strong>,</p>
<p>Your account has been successfully created. Below are your login details:</p>
<p><strong>Username:</strong> <?= $data['user']['email'] ?></p>
<p><strong>Password:</strong> <?= $data['password'] ?></p>
<p>You can log in to your account using the button below:</p>
<p><a href='<?= $this->config['url'] ?>' class='btn'>Login to Your Account</a></p>
<p>If you did not request this account, please contact our support team immediately.</p>
<p>Thank you for joining us!</p>