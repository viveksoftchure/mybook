<?php
/**
 * CRON: Add Payment title to payment description
 */
require_once __DIR__.'/../../config/config.php';
require_once __DIR__.'/../../core/function.php';
require_once __DIR__.'/../../models/model.php';
require_once __DIR__.'/../../models/category.php';
require_once __DIR__.'/../../models/passbook.php';
require_once __DIR__.'/../../models/user.php';

$db = new mysqli($config['host'], $config['login'], $config['password'], $config['database']);
$db->set_charset('utf8');

$passbookModel = new PassbookModel($db, $config);

$data = $passbookModel->getPayments();

if ($data) {
	echo '<table border="1">';
		echo '<thead>';
			echo '<tr>';
				echo '<td>S.No</td>';
				echo '<td>Id</td>';
				echo '<td>Category</td>';
				echo '<td>Title</td>';
				echo '<td>Description</td>';
				echo '<td>Updated Description</td>';
				echo '<td>Updated</td>';
			echo '</tr>';
		echo '</thead>';

		echo '<tbody>';
			foreach ($data as $key => $item) {
				$new_description = $item['title'] . ' ' . $item['description'];

				$update = $passbookModel->addPaymentTitleToDescription($item['id_payment'], $new_description);

				echo '<tr>';
					echo '<td>'.($key+1).'</td>';
					echo '<td>'.$item['id_payment'].'</td>';
					echo '<td>'.$item['category_data']['title'].'</td>';
					echo '<td>'.$item['title'].'</td>';
					echo '<td>'.$item['description'].'</td>';
					echo '<td>'.nl2br($new_description).'</td>';
					echo '<td>'.($update?'True':'False').'</td>';
				echo '</tr>';
			}
		echo '</tbody>';
	echo '</table>';
} else {
	echo '<h3>No Data.</h3>';
}