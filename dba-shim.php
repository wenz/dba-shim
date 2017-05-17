<?php

/**
 * (c) Christian Wenz <christian@wenz.org>
 * @license http://opensource.org/licenses/MIT
 */

if (!function_exists('dba_handlers')) {
    function dba_handlers()
    {
        return ['inifile'];
    }
}

if (!function_exists('dba_open')) {
    function dba_open($path, $mode, $handler = '')
    {
        if (!in_array($handler, dba_handlers())) {
            return false;
        }

        if (!in_array($mode, ['r', 'w', 'c', 'n'])) {
            return false;
        }

        if (!isset($GLOBALS['__DBA_DATA'])) {
            $_GLOBLAS['__DBA_DATA'] = [];
        }

        $id = 'ID' . mt_rand();

        $GLOBALS['__DBA'][$id] = [];
        $GLOBALS['__DBA'][$id]['mode'] = $mode;
        $GLOBALS['__DBA'][$id]['position'] = 0;

        switch ($handler) {
            case 'inifile':
                $data = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $GLOBALS['__DBA'][$id]['data'] = [];
                foreach ($data as $date) {
                    list($key, $value) = explode('=', $date);
                    $GLOBALS['__DBA'][$id]['data']['KEY' . $key] = $value;
                }
                break;
        }

        return $id;
    }
}

if (!function_exists('dba_firstkey')) {
    function dba_firstkey($handle)
    {
        if (!isset($GLOBALS['__DBA']) ||
            !isset($GLOBALS['__DBA'][$handle]) ||
            !isset($GLOBALS['__DBA'][$handle]['data'])) {
            return false;
        }

        $data = $GLOBALS['__DBA'][$handle]['data'];
        $id = null;
        foreach ($data as $key => $value) {
            if (substr($key, 0, 3) === 'KEY') {
                $id = substr($key, 3);
                break;
            }
        }
        if ($id === null) {
            return false;
        }

        $GLOBALS['__DBA'][$id]['position'] = 0;
        return $id;
    }
}

if (!function_exists('dba_exists')) {
    function dba_exists($key, $handle)
    {
        if (!isset($GLOBALS['__DBA']) ||
            !isset($GLOBALS['__DBA'][$handle]) ||
            !isset($GLOBALS['__DBA'][$handle]['data'])) {
            return false;
        }

        $data = $GLOBALS['__DBA'][$handle]['data'];

        foreach ($data as $keyname => $value) {
            if ($keyname === 'KEY' . $key) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('dba_fetch')) {
    function dba_fetch($key, $handle)
    {
        if (!isset($GLOBALS['__DBA']) ||
            !isset($GLOBALS['__DBA'][$handle]) ||
            !isset($GLOBALS['__DBA'][$handle]['data'])) {
            return false;
        }

        $data = $GLOBALS['__DBA'][$handle]['data'];

        foreach ($data as $keyname => $value) {
            if ($keyname === 'KEY' . $key) {
                return $value;
            }
        }
        return false;
    }
}

if (!function_exists('dba_nextkey')) {
    function dba_nextkey($handle)
    {
        if (!isset($GLOBALS['__DBA']) ||
            !isset($GLOBALS['__DBA'][$handle]) ||
            !isset($GLOBALS['__DBA'][$handle]['data'])) {
            return false;
        }

        $GLOBALS['__DBA'][$handle]['position']++;
        $position = $GLOBALS['__DBA'][$handle]['position'];

        $data = $GLOBALS['__DBA'][$handle]['data'];
        $id = null;
        $currentPosition = -1;
        foreach ($data as $key => $value) {
            if (substr($key, 0, 3) === 'KEY') {
                $currentPosition++;
                if ($currentPosition === $position) {
                    $id = substr($key, 3);
                    break;
                }
            }
        }
        if ($id === null) {
            return false;
        }
        return $id;
    }
}

if (!function_exists('dba_popen')) {
    function dba_popen($path, $mode, $handler = '')
    {
        $args = func_get_args();
        return call_user_func_array('dba_open', $args);
    }
}

if (!function_exists('dba_close')) {
    function dba_close($handle)
    {
        if (!isset($GLOBALS['__DBA']) ||
            !isset($GLOBALS['__DBA'][$handle]) ||
            !isset($GLOBALS['__DBA'][$handle]['data'])) {
            return false;
        }

        unset($GLOBALS['__DBA'][$handle]);
    }
}

