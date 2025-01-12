<?php
// Define the directory containing SVG files
$svgDirectory = 'svg'; // Path to your SVG folder

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
$cssFile = 'icons.css';

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
        $cssRule .= "    mask: url('{$svgDirectory}/{$svg}') no-repeat center center / contain;\n";
        $cssRule .= "    -webkit-mask: url('{$svgDirectory}/{$svg}') no-repeat center center / contain;\n";
        $cssRule .= "}\n\n";
        fwrite($cssHandle, $cssRule);
    }
    
    // Close the CSS file
    fclose($cssHandle);

    echo "CSS file '{$cssFile}' has been generated successfully.";
} else {
    echo "Failed to open the CSS file for writing.";
}
?>
