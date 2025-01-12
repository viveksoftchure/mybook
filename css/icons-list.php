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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVG Icons</title>
    <style>
        .icon-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1em;
        }
        .icon-item {
            display: inline-block;
            cursor: pointer;
            text-align: center;
        }
        .icon-item img {
            display: block;
            width: 3em;
            height: 3em;
        }
        .icon-name {
            margin-top: 0.5em;
            font-size: 0.75em;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>SVG Icons</h1>
    <div class="icon-list">
        <?php foreach ($svgFiles as $svg): ?>
            <?php
                $className = pathinfo($svg, PATHINFO_FILENAME);
                $svgPath = "{$svgDirectory}/{$svg}";
            ?>
            <div class="icon-item" onclick="copyToClipboard('<?php echo 'svg-icon svg-' . $className; ?>')">
                <img src="<?php echo $svgPath; ?>" alt="<?php echo $className; ?>">
                <div class="icon-name"><?php echo $className; ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function copyToClipboard(text) {
            // Create a temporary input element to copy the text
            const input = document.createElement('input');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Copied: ' + text);
        }
    </script>
</body>
</html>
