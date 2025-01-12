<?php
/*
|--------------------------------------------------------------------------
| Advanced controller
|--------------------------------------------------------------------------
*/
require_once __DIR__.'/../models/export.php';

class Advanced extends Controller
{
    /**
     * Export model
     *
     * @var ExportModel
     */
    protected $export;

    /**
     *  Advanced pre-dispatcher
     */
    public function preDispatch()
    {
        $this->export = new ExportModel($this->db, $this->config);

        parent::preDispatch();
    }

    /**
     * Options action
     */
    public function optionsAction()
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0 ;

        $data['updateUrl'] = $this->getCurrentUrl();

        $this->renderView('advanced/options', $data);
    }

    /**
     * Icons list action
     */
    public function icons_listAction()
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;

        if (isset($_POST['add_icon'])) {
            $result = $this->addDropFile();

            if ($result) {
                alert_push('Icons List is successfully updated.');
            } else {
                alert_push('Icons List cannot be updated.', 'danger');
            }
            $this->redirect($this->getUrl($this->t1, $this->t2));
        }

        $this->refreshIconsList();

        $this->renderView('advanced/icons-list');
    }

    /**
     * Export action
     */
    public function exportAction()
    {
        $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0 ;

        if (isset($_GET['generate_new']) && !empty($_GET['file'])) {
            $result = $this->export->generate_new($_GET);

            if ($result) {
                alert_push('New files is successfully exported.');
            } else {
                alert_push('New files cannot be exported.', 'danger');
            }
            $this->redirect($this->getUrl($this->t1, $this->t2));
        }

        $data['history'] = $this->export->getAllExports();

        $this->renderView('advanced/export', $data);
    }

    /**
     * Get all files for export
     *
     * @return array
     */
    public function getExportFilesList()
    {
        return [
            'passbook' => 'Passbook',
        ];
    }

    /**
     * Get all files for export
     *
     * @return array
     */
    public function refreshIconsList()
    {
        // Define the directory containing SVG files
        $svgDirectory = dirname(__DIR__) . '/css/svg'; // Path to your SVG folder
        $svgUrlDirectory = 'svg'; // Path to your SVG folder

        // Open the directory
        $dir = opendir($svgDirectory);

        // Initialize an array to store SVG filenames
        $svgFiles = [];

        // Loop through the directory to get SVG files
        while (($file = readdir($dir)) !== false) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'svg') {
                $svgFiles[] = $file;
            }
        }

        // Close the directory
        closedir($dir);

        // Define the CSS file to be created
        $cssFile = dirname(__DIR__) . '/css/icons.css';

        // Open the CSS file for writing
        $cssHandle = fopen($cssFile, 'w');

        // Check if the file opened successfully
        if ($cssHandle) {
             // Write the default SVG icon CSS rule
            $defaultCssRule = ".svg-icon {\n";
            $defaultCssRule .= "    display: inline-block;\n";
            $defaultCssRule .= "    width: 1em;\n";
            $defaultCssRule .= "    height: 1em;\n";
            $defaultCssRule .= "    fill: currentColor;\n"; // Allows the SVG to inherit the text color
            $defaultCssRule .= "    vertical-align: middle;\n";
            $defaultCssRule .= "    mask-size: contain;\n";
            $defaultCssRule .= "    -webkit-mask-size: contain;\n";
            $defaultCssRule .= "    background: #212529;\n";
            $defaultCssRule .= "}\n\n";
            fwrite($cssHandle, $defaultCssRule);
            
            // Write the CSS rules to the file
            foreach ($svgFiles as $svg) {
                $className = pathinfo($svg, PATHINFO_FILENAME); // Use the filename without extension as the class name
                $cssRule = ".svg-{$className} {\n";
                $cssRule .= "    mask: url('{$svgUrlDirectory}/{$svg}') no-repeat center center / contain;\n";
                $cssRule .= "    -webkit-mask: url('{$svgUrlDirectory}/{$svg}') no-repeat center center / contain;\n";
                $cssRule .= "}\n\n";
                fwrite($cssHandle, $cssRule);
            }
            
            // Close the CSS file
            fclose($cssHandle);

            return true;
        } else {
            echo false;
        }
    }

    /**
     * Update Drop File
     *
     * @param array $ids
     * @return array
     */
    public function addDropFile()
    {
        $destination = getcwd() . '/css/svg/';
        $field = 'file';

        foreach ($_FILES[$field]['name'] as $key => $filename) {
            if (is_uploaded_file($_FILES[$field]['tmp_name'][$key])) {
                $parts = pathinfo($filename);

                copy($_FILES[$field]['tmp_name'][$key], $destination . $filename);
            }
        }

        return true;
    }
}