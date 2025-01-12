<?php
// Define the directory containing SVG files
$svgDirectory = dirname(dirname(__DIR__)) . '/css/svg'; // Path to your SVG folder
$svUrlgDirectory = $this->config['url'] . 'css/svg'; // Path to your SVG folder

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
<div class="row p-sm m-t">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content text-right d-flex justify-content-between">
                <input type="search" name="search" id="search" placeholder="Search Icon..." class="form-control" style="max-width: 360px;">
                <button class="btn btn-primary add_form" data-toggle="modal" data-target="#modal" data-form="#add_form">Add file</button>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4>Icons</h4>
            </div>
            <div class="ibox-content">
                <div class="icon-list">
                    <?php foreach ($svgFiles as $svg): ?>
                        <?php
                            $className = pathinfo($svg, PATHINFO_FILENAME);
                            $svgPath = "{$svUrlgDirectory}/{$svg}";
                        ?>
                        <div class="icon-item" onclick="copyToClipboard('<?php echo 'svg-icon svg-' . $className; ?>')">
                            <img src="<?php echo $svgPath; ?>" alt="<?php echo $className; ?>">
                            <div class="icon-name"><?php echo $className; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="add_form" class="hidden">
    <div class="hidden">
        <input type="hidden" name="add_icon" value="1">
        <div class="form-title">Add file</div>
        <div class="form-img"><h1><i class="svg-icon svg-folder-open-dark"></i></h1></div>
    </div>

    <div class="form-group row m-b-none">
        <label class="col-lg-6 col-form-label"><strong>File</strong></label>
        <div class="col-lg-6 p-xxs">
            <div class="custom-file">
                <input id="file" name="file[]" type="file" class="custom-file-input" multiple="multiple">
                <label for="file" class="custom-file-label">Choose file...</label>
            </div>
        </div>
    </div>
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
<script>
$(document).ready(function() {
    $('#search').on('blur', function() {
        let s = $(this).val();

        window.open('https://www.svgrepo.com/vectors/' + s, '_blank');
    });
});
</script>