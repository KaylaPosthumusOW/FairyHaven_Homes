<?php
$target_dir = "uploads/";
$target_file = $target_dir . "test_file.txt";

if (is_writable($target_dir)) {
    if (file_put_contents($target_file, "This is a test file.") !== false) {
        echo "File was successfully written.";
    } else {
        echo "Failed to write the file.";
    }
} else {
    echo "The directory is not writable.";
}
?>