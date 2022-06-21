<?php

/**
 * elFinder Plugin Sanitizer
 * Sanitizer of file-name and file-path etc.
 * ex. binding, configure on connector options
 *    $opts = array(
 *        'bind' => array(
 *            'upload.pre mkdir.pre mkfile.pre rename.pre archive.pre ls.pre' => array(
 *                'Plugin.Sanitizer.cmdPreprocess'
 *            ),
 *            'upload.presave paste.copyfrom' => array(
 *                'Plugin.Sanitizer.onUpLoadPreSave'
 *            )
 *        ),
 *        // global configure (optional)
 *        'plugin' => array(
 *            'Sanitizer' => array(
 *                'enable' => true,
 *                'targets'  => array('\\','/',':','*','?','"','<','>','|'), // target chars
 *                'replace'  => '_', // replace to this
 *                'callBack' => null // Or @callable sanitize function
 *            )
 *        ),
 *        // each volume configure (optional)
 *        'roots' => array(
 *            array(
 *                'driver' => 'LocalFileSystem',
 *                'path'   => '/path/to/files/',
 *                'URL'    => 'http://localhost/to/files/'
 *                'plugin' => array(
 *                    'Sanitizer' => array(
 *                        'enable' => true,
 *                        'targets'  => array('\\','/',':','*','?','"','<','>','|'), // target chars
 *                        'replace'  => '_', // replace to this
 *                        'callBack' => null // Or @callable sanitize function
 *                    )
 *                )
 *            )
 *        )
 *    );
 *
 * @package elfinder
 * @author  Naoki Sawada
 * @license New BSD
 */
class elFinderPluginSanitizer extends elFinderPlugin
{
    private $replaced = array();
    private $keyMap = array(
        'ls' => 'intersect',
        'upload' => 'renames',
        'mkdir' => array('name', 'dirs')
    );

    public function __construct($opts)
    {
        $defaults = array(
            'enable' => true,  // For control by volume driver
            'targets' => array('\\', '/', ':', '*', '?', '"', '<', '>', '|'), // target chars
            'replace' => '_',   // replace to this
            'callBack' => null   // Or callable sanitize function
        );
        $this->opts = array_merge($defaults, $opts);
    }

    public function cmdPreprocess($cmd, &$args, $elfinder, $volume)
    {
        $opts = $this->getCurrentOpts($volume);
        if (!$opts['enable']) {
            return false;
        }
        $this->replaced[$cmd] = array();
        $key = (isset($this->keyMap[$cmd])) ? $this->keyMap[$cmd] : 'name';

        if (is_array($key)) {
            $keys = $key;
        } else {
            $keys = array($key);
        }
        foreach ($keys as $key) {
            if (isset($args[$key])) {
                if (is_array($args[$key])) {
                    foreach ($args[$key] as $i => $name) {
                        if ($cmd === 'mkdir' && $key === 'dirs') {
                            // $name need '/' as prefix see #2607
                            $name = '/' . ltrim($name, '/');
                            $_names = explode('/', $name);
                            $_res = array();
                            foreach ($_names as $_name) {
                                $_res[] = $this->sanitizeFileName($_name, $opts);
                            }
                            $this->replaced[$cmd][$name] = $args[$key][$i] = join('/', $_res);
                        } else {
                            $this->replaced[$cmd][$name] = $args[$key][$i] = $this->sanitizeFileName($name, $opts);
                        }
                    }
                } else if ($args[$key] !== '') {
                    $name = $args[$key];
                    $this->replaced[$cmd][$name] = $args[$key] = $this->sanitizeFileName($name, $opts);
                }
            }
        }
        if ($cmd === 'ls' || $cmd === 'mkdir') {
            if (!empty($this->replaced[$cmd])) {
                // un-regist for legacy settings
                $elfinder->unbind($cmd, array($this, 'cmdPostprocess'));
                $elfinder->bind($cmd, array($this, 'cmdPostprocess'));
            }
        }
        return true;
    }

    public function cmdPostprocess($cmd, &$result, $args, $elfinder, $volume)
    {
        if ($cmd === 'ls') {
            if (!empty($result['list']) && !empty($this->replaced['ls'])) {
                foreach ($result['list'] as $hash => $name) {
                    if ($keys = array_keys($this->replaced['ls'], $name)) {
                        if (count($keys) === 1) {
                            $result['list'][$hash] = $keys[0];
                        } else {
                            $result['list'][$hash] = $keys;
                        }
                    }
                }
            }
        } else if ($cmd === 'mkdir') {
            if (!empty($result['hashes']) && !empty($this->replaced['mkdir'])) {
                foreach ($result['hashes'] as $name => $hash) {
                    if ($keys = array_keys($this->replaced['mkdir'], $name)) {
                        $result['hashes'][$keys[0]] = $hash;
                    }
                }
            }
        }
    }

    // NOTE: $thash is directory hash so it unneed to process at here
    public function onUpLoadPreSave(&$thash, &$name, $src, $elfinder, $volume)
    {
        $opts = $this->getCurrentOpts($volume);
        if (!$opts['enable']) {
            return false;
        }
        $name = $this->sanitizeFileName($name, $opts);
        return true;
    }

    protected function sanitizeFileName($filename, $opts)
    {
        if (!empty($opts['callBack']) && is_callable($opts['callBack'])) {
            return call_user_func_array($opts['callBack'], array($filename, $opts));
        }

        $filename = addslashes(html_entity_decode($filename));
        $filename = $this->toNormal($filename);
        $filename = preg_replace("/[^a-zA-Z0-9\/_+ -.]/", '', $filename);
        $filename = preg_replace("/( )/", '-', $filename);
        $filename = str_replace('/', '', $filename);
        $filename = str_replace("\/", '', $filename);
        $filename = str_replace("+", "", $filename);
        $filename = str_replace(" - ", "-", $filename);
        $filename = str_replace("---", "-", $filename);
        $filename = str_replace("--", "-", $filename);
        $filename = strtolower($filename);
        $filename = stripslashes($filename);
        trim($filename, '-');

        return str_replace($opts['targets'], $opts['replace'], $filename);
    }

    private function toNormal($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
}
