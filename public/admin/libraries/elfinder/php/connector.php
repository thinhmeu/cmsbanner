<?php
session_start();
error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elFinderPlugin.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
    return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
        ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
        :  null;                                    // else elFinder decide it itself
}

$root = getcwd();
$root = explode('public_html', $root);
$root = $root[0].'public_html/public';
$path = $root.'/upload';
#
$user = strtolower($_SESSION['user']);
$year = date('Y');
$month = date('m');
$day = date('d');
#
//$path = $root . '/upload/' . $user . '/' . $year . '/' . $month . '/' . $day . '/';
$path = $root . '/upload/' . $user . '/';
$pathUser = $root . '/upload/' . $user . '/';
if (!is_dir($pathUser)) {
    mkdir($pathUser, 0777);
}
/*$pathYear = $root . '/upload/' . $user . '/' . $year . '/';
if (!is_dir($pathYear)) {
    mkdir($pathYear, 0777);
}
$pathMonth = $root . '/upload/' . $user . '/' . $year . '/' . $month . '/';
if (!is_dir($pathMonth)) {
    mkdir($pathMonth, 0777);
}
$pathDay = $root . '/upload/' . $user . '/' . $year . '/' . $month . '/' . $day . '/';
if (!is_dir($pathDay)) {
    mkdir($pathDay, 0777);
}*/
//$url_to_files = '/upload/' . $user . '/' . $year . '/' . $month . '/' . $day . '/';
$url_to_files = '/upload/' . $user . '/';

$opts = array(
    // 'debug' => true,
    'roots' => array(
        array(
            'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
            'path'          => $path,         // path to files (REQUIRED)
            'URL'           => $url_to_files, // URL to files (REQUIRED)
            'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
        )
    ),
    'bind' => array(
        'upload.presave' => array(
            'Plugin.Sanitizer.onUpLoadPreSave'
        )
    ),
    'plugin' => array(
        'Sanitizer' => array(
            'enable' => true,
            'targets' => [], // target chars
            'replace' => '_', // replace to this
            'callBack' => null // Or @callable sanitize function
        )
    )
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

