<?php
// Function to upload file
function uploadData($arr = null, $path = null)
{
    // Upload directory
    $upload_location = "upload/";
    $dirName = $upload_location . $path;
    if (!file_exists($dirName)) {
        mkdir($dirName, 0755, true);
    }
    // To store uploaded files path
    $files_arr = '';
    // File name
    $filename = $arr['files']['name'];
    // Get extension
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    // Valid image extension
    $valid_ext = array("png", "PNG", "jpeg", "jpg", "pdf", "docx", "doc", 'xls', 'xlsx', 'csv');

    // Check extension
    if (in_array($ext, $valid_ext)) {
        $file_name = preg_replace("/\s+/", "_", $filename);
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = pathinfo($file_name, PATHINFO_FILENAME);
        $filename = $file_name . "_" . date('mjYHis') . "." . $file_ext;
        // File path
        $file_path = $dirName . '/' . $filename;
        // Upload file
        if (move_uploaded_file($arr['files']['tmp_name'], $file_path)) {
            $files_arr = $file_path;
            return  json_encode(array('status' => 200, 'data' => $files_arr));
        }
    } else {
        return json_encode(array('status' => 404));
    }
}
// function to remove file

