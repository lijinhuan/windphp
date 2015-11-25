<?php
if(!defined('FRAMEWORK_PATH'))
{
	header("HTTP/1.1 404 Not Found");
	die;
}

class FileDir {
	
	/**
	 * 把文件读入数组（一行为一个元素）
	 * 1.去掉每行末的换行符。
	 * 2.可以设置是否过滤空行。
	 *
	 * @access public
	 * @param string $filename
	 * @param boolean $skip_empty_lines 是否过滤空行
	 * @return array
	 */
	public static function file($filename, $skip_empty_lines = false) {
		if ($skip_empty_lines) {
			return file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		} else {
			return file($filename, FILE_IGNORE_NEW_LINES);
		}
	}
	
	/**
	 * 读取文件为字符串
	 *
	 * @access public
	 * @param string $filename
	 * @return boolean string
	 */
	public static function read($filename) {
		return is_file($filename) ? file_get_contents($filename) : false;
	}
	
	/**
	 * 将字符串写入文件
	 *
	 * @access public
	 * @param string $filename
	 * @param string $data
	 * @return number
	 */
	public static function write($filename, $data) {
		$dirname = dirname($filename);
		self::mkdir($dirname);
		return file_put_contents($filename, $data);
	}
	
	/**
	 * 读取文件为一个数组
	 *
	 * @access public
	 * @param string $filename
	 * @return array 文件不存在或读取失败，返回空数组
	 */
	public static function readArray($filename) {
		$data = is_file($filename) ? file_get_contents($filename) : false;
		$data = unserialize($data);
		return is_array($data) ? $data : array();
	}
	
	/**
	 * 将一个数组写入文件
	 *
	 * @access public
	 * @param string $filename
	 * @param array $array
	 * @return array 返回写入的数组
	 */
	public static function writeArray($filename, $array) {
		self::write($filename, serialize($array));
		return $array;
	}
	
	/**
	 * 创建目录
	 * 1.递归创建多级目录
	 * 2.同时创建多个目录
	 *
	 * @param string|array $dirname
	 * @param number $mode
	 * @param boolean $recursive
	 */
	public static function mkdir($dirname, $mode = 0777, $recursive = true) {
		if (is_string($dirname) || is_numeric($dirname)) {
			if (!is_dir($dirname)) {
				return mkdir($dirname, $mode, $recursive);
			}
		} elseif (is_array($dirname)) {
			foreach ($dirname as $dir) {
				is_dir($dir) or mkdir($dir, $mode, $recursive);
			}
		}
		return false;
	}
	
	/**
	 * 删除目录和文件
	 * 1.可以删除文件和目录
	 * 2.判断目录和文件是否存在，存在进行删除。
	 * 3.递归删除目录及止内所有文件和文件夹
	 *
	 * @access public
	 * @param string $filename
	 */
	public static function delete($filename) {
		$filename = rtrim($filename, '/\\');
		if (is_dir($filename)) {
			if (($dh = @opendir($filename)) !== false) {
				while (false !== ($file = readdir($dh))) {
					if ($file != "." && $file != "..") {
						$path = $filename . '/' . $file;
						is_dir($path) ? self::delete($path) : @unlink($path);
					}
				}
				closedir($dh);
			}
			rmdir($filename);
		} else {
			unlink($filename);
		}
	}
	
	
	/**
	 * 递归列出目录下的所有子目录和文件
	 *
	 * @access public
	 * @param string $dirname
	 * @return array 成功返回包含所有子目录和文件的一维数组。失败返回空数组。
	 */
	public static function getSub($dirname) {
		$dirname = rtrim($dirname, '/\\');
		$ret = array();
		if (is_dir($dirname)) {
			if (($dh = @opendir($dirname)) !== false) {
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..') {
						$path = $dirname . DIRECTORY_SEPARATOR . $file;
						$ret[] = $path;
						is_dir($path) && $ret = array_merge($ret, self::getSub($path));
					}
				}
				closedir($dh);
			}
		}
		return $ret;
	}
	
	
	/**
	 * 递归列出目录下的所有子目录
	 *
	 * @access public
	 * @param string $dirname
	 * @return array 成功返回包含所有子目录的一维数组。失败返回空数组。
	 */
	public static function getSubDir($dirname) {
		$dirname = rtrim($dirname, '/\\');
		$ret = array();
		if (is_dir($dirname)) {
			if (($dh = @opendir($dirname)) !== false) {
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..') {
						$path = $dirname . DIRECTORY_SEPARATOR . $file;
						if (is_dir($path)) {
							$ret[] = $path;
							$ret = array_merge($ret, self::getSubDir($path));
						}
					}
				}
				closedir($dh);
			}
		}
		return $ret;
	}
	
	
	/**
	 * 递归列出目录下的所有文件
	 *
	 * @access public
	 * @param string $dirname
	 * @return array 成功返回包含所有文件的一维数组。失败返回空数组。
	 */
	public static function getSubFile($dirname) {
		$dirname = rtrim($dirname, '/\\');
		$ret = array();
		if (is_dir($dirname)) {
			if (($dh = @opendir($dirname)) !== false) {
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..') {
						$path = $dirname . DIRECTORY_SEPARATOR . $file;
						if (is_dir($path)) {
							$ret = array_merge($ret, self::getSubFile($path));
						} else {
							$ret[] = $path;
						}
					}
				}
				closedir($dh);
			}
		}
		return $ret;
	}
	
	
	/**
	 * 递归列出目录下的所有子目录和文件
	 *
	 * @access public
	 * @param string $dirname
	 * @return array 成功返回以子目录为键，子目录下所有文件的数组为值的数组，失败返回空数组。
	 */
	public static function getSubAsKV($dirname) {
		$dirname = rtrim($dirname, '/\\');
		$ret = array();
		if (is_dir($dirname)) {
			if (($dh = @opendir($dirname)) !== false) {
				while (false !== ($file = readdir($dh))) {
					if ($file != "." && $file != "..") {
						$path = $dirname . DIRECTORY_SEPARATOR . $file;
						is_dir($path) ? $ret[$path] = self::getSub($path) : $ret[] = $path;
					}
				}
				closedir($dh);
			}
		}
		return $ret;
	}
	
	
	/**
	 * 获取文件夹大小
	 *
	 * @access public
	 * @param string $dirname
	 * @return number 返回文件夹大小（单位为字节）。
	 */
	public static function getDirSize($dirname) {
		$dirname = rtrim($dirname, '/\\');
		$size = 0;
		if (is_dir($dirname)) {
			if (($dh = @opendir($dirname)) !== false) {
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..') {
						$file = $dirname . DIRECTORY_SEPARATOR . $file;
						$size += is_dir($file) ? self::getDirSize($file) : filesize($file);
					}
				}
				closedir($dh);
			}
		}
		return $size;
	}
	
	
	/**
	 * 获取自动转换单位的文件大小值（四舍五入）
	 *
	 * @access public
	 * @param float $byte
	 * @param integer $precision 精度
	 * @return string
	 */
	public static function getAutoSize($byte, $precision = 2) {
		$kb = 1024;
		$mb = $kb * 1024;
		$gb = $mb * 1024;
		$tb = $gb * 1024;
		if ($byte < $kb) {
			return $byte . ' B';
		} elseif ($byte < $mb) {
			return round($byte / $kb, $precision) . ' KB';
		} elseif ($byte < $gb) {
			return round($byte / $mb, $precision) . ' MB';
		} elseif ($byte < $tb) {
			return round($byte / $gb, $precision) . ' GB';
		} else {
			return round($byte / $tb, $precision) . ' TB';
		}
	}
	
}


?>