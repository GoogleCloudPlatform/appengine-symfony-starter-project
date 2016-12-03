<?php
namespace AppEngine;
/**
*
*/
class MemcacheStreamWrapper
{
    private $memcache;
    private $key;
    private $position = 0;
    private $value;
    private $statcache = [];
    // Bit fields for the stat mode field
    const S_IFREG = 0100000;
    const S_IFDIR = 0040000;
    const S_IRWXU = 00700;  // mask for owner permissions
    const S_IRUSR = 00400;  // read for owner
    const S_IWUSR = 00200;  // write for owner
    const S_IXUSR = 00100;  // execute for owner
    const S_IRWXG = 00070;  // mask for group permissions
    const S_IRGRP = 00040;  // read for group
    const S_IWGRP = 00020;  // write for group
    const S_IXGRP = 00010;  // execute for group
    const S_IRWXO = 00007;  // mask for other other permissions
    const S_IROTH = 00004;  // read for other
    const S_IWOTH = 00002;  // write for other
    const S_IXOTH = 00001;  // execute for other
    public function __construct(\Memcache $memcache = null)
    {
        if (is_null($memcache)) {
            $memcache = new \Memcached();
        }
        $this->memcache = $memcache;
    }
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->key = $this->createKeyFromPath($path);
        return true;
    }
    public function stream_read($count)
    {
        if (empty($this->value)) {
            $this->value = $this->memcache->get($this->key, null);
        }
        $ret = substr($this->value, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }
    public function stream_write($data)
    {
        $this->removeStatCache($this->key);
        $left = substr($this->value, 0, $this->position);
        $right = substr($this->value, $this->position + strlen($data));
        $this->value = $left . $data . $right;
        $this->position += strlen($data);
        $this->memcache->set($this->key, $this->value);
        return strlen($data);
    }
    public function stream_tell()
    {
        return $this->position;
    }
    public function stream_eof()
    {
        return $this->position >= strlen($this->value);
    }
    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($this->value) && $offset >= 0) {
                     $this->position = $offset;
                     return true;
                } else {
                     return false;
                }
                break;
            case SEEK_CUR:
                if ($offset >= 0) {
                     $this->position += $offset;
                     return true;
                } else {
                     return false;
                }
                break;
            case SEEK_END:
                if (strlen($this->value) + $offset >= 0) {
                     $this->position = strlen($this->value) + $offset;
                     return true;
                } else {
                     return false;
                }
                break;
            default:
                return false;
        }
    }
    public function stream_metadata($path, $option, $var)
    {
        return false;
    }
    public function stream_lock($operation)
    {
        return false;
    }
    private function createKeyFromPath($path)
    {
        return $path;
    }
    /**
    * Close an open directory handle.
    */
    public function dir_closedir()
    {
        return true;
    }
    /**
    * Open a directory handle.
    */
    public function dir_opendir($path, $options)
    {
        return true;
    }
    /**
    * Read entry from the directory handle.
    *
    * @return string representing the next filename, of false if there is no
    * next file.
    */
    public function dir_readdir()
    {
        return true;
    }
    /**
    * Reset the output returned from dir_readdir.
    *
    * @return bool true if the stream can be rewound, false otherwise.
    */
    public function dir_rewinddir()
    {
        return true;
    }
    public function mkdir($path, $mode, $options)
    {
        $key = $this->createKeyFromPath($path);
        $this->removeStatCache($key);
        $this->memcache->set($key, '__DIR__');
        return true;
    }
    public function rmdir($path, $options)
    {
        $key = $this->createKeyFromPath($path);
        $this->removeStatCache($key);
        $this->memcache->delete($key);
        return true;
    }
    /**
    * Rename a cloud storage object.
    *
    * @return TRUE if the object was renamed, FALSE otherwise
    */
    public function rename($from, $to)
    {
        @rename($from, $to);
        return true;
    }
    public function stream_stat()
    {
        return $this->url_stat($this->key, 0);
    }
    public function url_stat($path, $flags)
    {
        $key = $this->createKeyFromPath($path);
        // doesn't exist
        if (!isset($this->statcache[$key])) {
            if (!$value = $this->memcache->get($key)) {
                return false;
            }
            $this->statcache[$key] = [
                'mode' => ($value == '__DIR__' ? self::S_IFDIR : self::S_IFREG),
                'size' => strlen($value),
            ];
            $this->value = $value;
        }
        $mode = 0;
        // determine mode
        $readable = self::S_IRUSR | self::S_IRGRP | self::S_IROTH;
        $writable = self::S_IWUSR | self::S_IWGRP | self::S_IWOTH;
        $mode |= $readable | $writable | $this->statcache[$key]['mode'];
        $stats['uid'] = getmyuid();
        $stats['gid'] = getmyuid();
        $stats['atime'] = time();
        $stats['mtime'] = time();
        $stats['ctime'] = time();
        $stats['size'] = $this->statcache[$key]['size'];
        $stats['mode'] = $mode;
        return $this->createStatArray($stats);
    }
    private function removeStatCache($key)
    {
        unset($this->statcache[$key]);
    }
    private function createStatArray($stat_args)
    {
        $stat_keys = ["dev", "ino", "mode", "nlink", "uid", "gid", "rdev", "size",
            "atime", "mtime", "ctime", "blksize", "blocks"];
        $result = [];
        foreach ($stat_keys as $key) {
            $value = 0;
            if (array_key_exists($key, $stat_args)) {
            $value = $stat_args[$key];
            }
            // Add the associative entry.
            $result[$key] = $value;
            // Add the index entry.
            $result[] = $value;
        }
        return $result;
    }
}
