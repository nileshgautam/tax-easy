<?php
// Function to upload file
function uploadData($arr = null, $path = null)
{
    // Upload directory
    $upload_location = "uploads/";
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
function uploadFile($arr = null, $path = null)
{
    // Count total files
    $countfiles = count($arr['files']['name']);
    // Upload directory
    $upload_location = "uploads/";
    $dirName = $upload_location . $path;

    if (!file_exists($dirName)) {
        mkdir($dirName, 0755, true);
    }
    // To store uploaded files path
    $files_arr = array();

    // Loop all files
    for ($index = 0; $index < $countfiles; $index++) {
        // File name
        $filename = $arr['files']['name'][$index];
        $ftitle=$arr['files']['name'][$index];
        // Get extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        // Valid image extension
        $valid_ext = array("png", "jpeg", "jpg", "pdf", "docx", "doc", 'xlsx', 'xls');
        // Check extension
        if (in_array($ext, $valid_ext)) {
            $file_name = preg_replace("/\s+/", "_", $filename);
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_name = pathinfo($file_name, PATHINFO_FILENAME);
            $filename = $file_name . "_" . date('mjYHis') . "." . $file_ext;
            // File path
            $file_path = $dirName . '/' . $filename;
            // Upload file
            if (move_uploaded_file($arr['files']['tmp_name'][$index], $file_path)) {
                $item['title']=$ftitle;
                $item['path']=$file_path;
                $files_arr[] = $item;
            }
        }
    }
    // print_r($files_arr);
    return  json_encode($files_arr);
    // die;
}
// function to makeuserid
function makeuserid($useremail)
{
    $user_id = 'u-';
    $count_id_char = strripos($useremail, '@');
    for ($i = 0; $i < $count_id_char; $i++) {
        $user_id .= $useremail[$i];
    }
    return $user_id;
}
?>