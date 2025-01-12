<?php
function make_resize_pro($source_folder,$source_file,$final_folder,$final_file,$width,$height)
{
    $imgsize = getimagesize($source_folder);
    $orig_width = $imgsize[0];
    $orig_height = $imgsize[1];
    $mime = $imgsize['mime'];
     
    switch ($mime) 
    {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            $final_file_ext = ".jpg";
            break;
 
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $final_file_ext = ".jpg";
            $quality = 7;
            break;
 
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $final_file_ext = ".jpg";
            $quality = 80;
            break;
 
        default:
            return false;
            break;
    }
 
    $dst_img = imagecreatetruecolor($width, $height);
 
    imagealphablending($dst_img, false);
    imagesavealpha($dst_img, true);
    $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
    imagefilledrectangle($dst_img, 0, 0, $width, $height, $transparent);
 
    $src_img = $image_create($source_folder);

    if($src_img == false) 
    {
        global $config;
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if ($config['lang']=='fr') 
        {
            $error_msg = "Il y a une erreur avec l'image que vous avez téléchargée, veuillez essayer avec une autre";
        }
        else
        {
            $error_msg = 'There is an error with the image you uploaded, please try with another one';
        }
        
        alert_push($error_msg, 'danger');
        header("Location: $actual_link");
        exit();
    }
 
    $width_new = $orig_height * $width / $height;
    $height_new = $orig_width * $height / $width;

    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if ($width_new > $orig_width) 
    {
        //cut point by height
        $h_point = (($orig_height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $width, $height, $orig_width, $height_new);
    } 
    else
    {
        //cut point by width
        $w_point = (($orig_width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $width, $height, $width_new, $orig_height);
    }
 
    $image($dst_img, $final_folder.$final_file.$final_file_ext, $quality);

    // echo '<pre>'; print_r($width); echo '</pre>'; exit;
 
    if ($dst_img)
        imagedestroy($dst_img);
    if ($src_img)
        imagedestroy($src_img);
}

function make_resize_nopro($source_folder,$source_file,$final_folder,$final_file,$width,$height) 
{
    $imgsize = getimagesize($source_folder);
    $orig_width = $imgsize[0];
    $orig_height = $imgsize[1];
    $mime = $imgsize['mime'];
     
    switch ($mime) 
    {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            $final_file_ext = ".jpg";
            break;
 
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $final_file_ext = ".jpg";
            $quality = 7;
            break;
 
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $final_file_ext = ".jpg";
            $quality = 80;
            break;
 
        default:
            return false;
            break;
    }
 
    $dst_img = imagecreatetruecolor($width, $height);
 
    imagealphablending($dst_img, false);
    imagesavealpha($dst_img, true);
    $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
    imagefilledrectangle($dst_img, 0, 0, $width, $height, $transparent);
 
    $src_img = $image_create($source_folder);

    if($src_img == false) 
    {
        global $config;
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if ($config['lang']=='fr') 
        {
            $error_msg = "Il y a une erreur avec l'image que vous avez téléchargée, veuillez essayer avec une autre";
        }
        else
        {
            $error_msg = 'There is an error with the image you uploaded, please try with another one';
        }
        
        alert_push($error_msg, 'danger');
        header("Location: $actual_link");
        exit();
    }
 
    $width_new = $orig_height * $width / $height;
    $height_new = $orig_width * $height / $width;

    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if ($width_new > $orig_width) 
    {
        //cut point by height
        $h_point = (($orig_height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $width, $height, $orig_width, $height_new);
    } 
    else 
    {
        //cut point by width
        $w_point = (($orig_width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $width, $height, $width_new, $orig_height);
    }
 
    $image($dst_img, $final_folder.$final_file.$final_file_ext, $quality);

    // echo '<pre>'; print_r($width); echo '</pre>'; exit;
 
    if ($dst_img)
        imagedestroy($dst_img);
    if ($src_img)
        imagedestroy($src_img);
}

/**
 * Format date
 *
 * Returns a string formatted according to 'Month day, year'
 * Example: October 9, 2020
 *
 * @param string $date
 * @param string $default
 * @return null|string
 */
function format_date($date, $default = '') 
{
    if ($date && $date != '0000-00-00') {
        return date('d.m.Y', strtotime($date));
    }
    return $default;
}

/**
 * Format date to standard format
 *
 * Returns a string formatted according to 'day.month.year'
 * Example: 9.11.2020
 *
 * @param string $date
 * @param string $default
 * @return null|string
 */
function format_date_st($date, $default = '') {
    if ($date && $date != '0000-00-00') {
        return date('d.m.Y', strtotime($date));
    }
    return $default;
}

/**
 * Format datetime to standard format
 *
 * Returns a string formatted according to 'day.month.year hours:minutes'
 * Example: 9.11.2020 23:59
 *
 * @param string $date
 * @param string $default
 * @return null|string
 */
function format_datetime_st($date, $default = '') {
    if ($date && $date != '0000-00-00') {
        return date('d.m.Y H:i', strtotime($date));
    }
    return $default;
}

/**
 * Format time to standard format
 *
 * Returns a string formatted according to 'hours:minutes'
 * Example: 23:59
 *
 * @param string $date
 * @param string $default
 * @return null|string
 */
function format_time_st($date, $default = '') {
    if ($date && $date != '0000-00-00') {
        return date('H:i', strtotime($date));
    }
    return $default;
}

/**
 * Format calendar date to MySQL date format
 *
 * Returns a string date formatted according to MySQL date format
 * Example: input 1/12/2020, output 2020-12-1
 *
 * @param string $date
 * @return null|string
 */
function format_date_ms($date){
    if ($date) {
        $parts = array_reverse(explode('/', $date));
        return implode('-', $parts);
    }
}

/**
 * Format MySQL date to calendar date format
 *
 * Returns a string date formatted according to calendar 'dd/mm/yyyy' format
 * Example: input 2020-12-1, output 1/12/2020
 *
 * @param string $date
 * @return null|string
 */
function format_date_cl($date){
    if ($date && $date != '0000-00-00') {
        return date('d/m/Y', strtotime($date));
    }
}

/**
 * Format MySQL date to the time elapsed since a date time
 *
 * Example: input 2020-12-22 08:40:38, output 2h ago
 *
 * @param string $date
 * @return null|string
 */
function human_timing($date) {
    $time = strtotime($date);

    // to get the time since that moment
    $time = time() - $time;
    $time = ($time < 1) ? 1 : $time;
    $tokens = [
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    ];

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) {
            continue;
        }
        $number_of_units = floor($time / $unit);
        return $number_of_units .' '. $text . (($number_of_units > 1) ? 's' : '');
    }
}

/**
 * Dump debug data
 *
 * @param array $data
 */
function dump($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * Returns value for 'flag' options
 *
 * @param array $array
 * @param string $key
 * @param mixed $default
 *
 * @return mixed
 */
function flag($array, $key, $default = '-') {
    $value = ifset($array, $key);
    if ($value == '1') {
        return 'Yes';
    } elseif ($value == '0') {
        return 'No';
    } else {
        return $default;
    }
}

/**
 * Returns value by key from array if set
 *
 * @param array $array
 * @param string $key
 * @param mixed $default
 * @param bool $strict
 * @return mixed
 */
function ifset($array, $key, $default = null, $strict = false) {
    if ($strict) {
        return !empty($array[$key]) ? $array[$key] : $default;
    }
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * Redirect to the specified part of application
 *
 * @param string $t1
 * @param string $t2
 * @param string $id
 * @param string $ext
 */
function redirect($t1, $t2, $id = '', $ext = '') {
    header('Location: ' . get_url($t1, $t2, $id, $ext));
    exit;
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
function get_url($t1, $t2, $id = '', $ext = '') {
    $location = 'index.php?t1=' . $t1 . '&t2=' . $t2;
    if ($id) {
        $location .= '&' . $id;
    }
    if ($ext) {
        $location .= '&' . $ext;
    }
    return $location;
}

/**
 * Save alert message to the current user session
 *
 * @param string $message
 * @param string $type
 * @return mixed
 */
function alert_push($message, $type = 'success') {
    $_SESSION['alert'][$type] = $message;
}

/**
 * Retrieve alert messages from current user session
 *
 * @return array
 */
function alert_shift() {
    $result = [];
    $types = ['success', 'info', 'warning', 'danger', 'error'];

    foreach($types as $k => $type) {
        if (isset($_SESSION['alert'][$type])) {
            $result[$k] = $_SESSION['alert'][$type];
            unset($_SESSION['alert'][$type]);
        } else {
            $result[$k] = '';
        }
    }
    return array_combine($types, $result);
}

/**
 * Generate random string of symbols
 *
 * @param int $length
 * @return string
 */
function authentication_string($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Sanitize value
 *
 * @param string $value
 * @return string
 */
function sanitize($value) {
    return htmlspecialchars($value,ENT_QUOTES);
}

/**
 * Encode data in JSON format
 *
 * @param mixed $data
 * @return string
 */
function jsonify($data) {
    return str_replace(['\t','\n','\r'], ' ', json_encode($data));
}

/**
 * make date range array from given data
 *
 * @param varchar $from
 * @param varchar $to
 * @param varchar $pattern
 * @return array
 */
function makeDateRange($from,$to,$pattern='Y-m-d') {
    $from = !empty($from) ? $from : date('Y-m-01');
    $to = !empty($to) ? $to : date('Y-m-t');

    $year = date('Y', strtotime($from));

    $from_month = date('m', strtotime($from));
    $to_month = date('m', strtotime($to));

    $to = date('d', strtotime($to));

    $dateArray = [];
    for($m = $from_month; $m <= $to_month; $m++)
    {
        $end = date($year.'-'.$m.'-d');
        $end = date('t', strtotime($end)); // total days of month

        for ($d=1; $d <= $end ; $d++) 
        { 
            $dm = $m < 10 ? '0'.$m: $m;
            $dm = str_replace('00', '0', $dm);
            $dd = $d < 10 ? '0'.$d: $d;

            $dateArray[] = date($year.'-'.$dm.'-'.$dd);
        }
    }

    return $dateArray;
}

/**
 * Convert the amount to INR
 *
 * @param string $amount
 * @return string
 */
function formatToINR($amount) {
    // Convert the amount to a float and round to 2 decimal places
    $amount = round((float)$amount, 2);

    // Split the amount into the integer and decimal parts
    $parts = explode('.', $amount);
    $integerPart = $parts[0];
    $decimalPart = isset($parts[1]) ? $parts[1] : '00';

    // Add commas to the integer part
    $lastThree = substr($integerPart, -3);
    $otherNumbers = substr($integerPart, 0, -3);
    if ($otherNumbers != '') {
        $lastThree = ',' . $lastThree;
    }
    $formattedInteger = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $otherNumbers) . $lastThree;

    // Ensure the decimal part is two digits
    if (strlen($decimalPart) < 2) {
        $decimalPart .= '0';
    }

    // Combine the integer and decimal parts
    $formattedAmount = '₹' . $formattedInteger . '.' . $decimalPart;

    return $formattedAmount;
}

/**
* generate pagination for given counts
*
* @param int $current current page number
* @param int $num_pages total pages
* @param int $edge_number_count count of pages to show before and after current page
* @return string
*/
function getPageRange($current_page, $num_pages, $edge_number_count = 5) {
    $start_number = $current_page - $edge_number_count;
    $end_number = $current_page + $edge_number_count;
    
    // Minus one so that we don't split the start number unnecessarily, eg: "1 ... 2 3" should start as "1 2 3"
    if ( ($start_number - 1) < 1 ) {
        $start_number = 1;
        $end_number = min($num_pages, $start_number + ($edge_number_count*2));
    }
    
    // Add one so that we don't split the end number unnecessarily, eg: "8 9 ... 10" should stay as "8 9 10"
    if ( ($end_number + 1) > $num_pages ) {
        $end_number = $num_pages;
        $start_number = max(1, $num_pages - ($edge_number_count*2));
    }
    
    if ( $end_number == $num_pages && (($edge_number_count*2) + 1) > ($end_number - $start_number) && $start_number > 1 ) {
        $start_number = $end_number - ($edge_number_count*2);
    }
    
    return array( $start_number, $end_number );
}