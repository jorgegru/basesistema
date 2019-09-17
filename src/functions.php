<?php
/*
	Jorge Goulart de Jesus <jorgegru@gmail.com>
*/
    if (!function_exists('uuid')) {
		function uuid() {
			//uuid version 4
			return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				// 32 bits for "time_low"
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
				// 16 bits for "time_mid"
				mt_rand( 0, 0xffff ),
				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand( 0, 0x0fff ) | 0x4000,
				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand( 0, 0x3fff ) | 0x8000,
				// 48 bits for "node"
				mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
			);
		}
		//echo uuid();
	}
	if (!function_exists('is_uuid')) {
		function is_uuid($uuid) {
			$regex = '/^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$/i';
			return preg_match($regex, $uuid);
		}
	}
	if (!function_exists('recursive_copy')) {
		if (file_exists('/bin/cp')) {
			function recursive_copy($src, $dst, $options = '') {
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'SUN') {
					//copy -R recursive, preserve attributes for SUN
					$cmd = 'cp -Rp '.$src.'/* '.$dst;
				} else {
					//copy -R recursive, -L follow symbolic links, -p preserve attributes for other Posix systemss
					$cmd = 'cp -RLp '.$options.' '.$src.'/* '.$dst;
				}
				//$this->write_debug($cmd);
				exec ($cmd);
			}
		} elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			function recursive_copy($src, $dst, $options = '') {
				$src = normalize_path_to_os($src);
				$dst = normalize_path_to_os($dst);
				exec("xcopy /E /Y \"$src\" \"$dst\"");
			}
		} else {
			function recursive_copy($src, $dst, $options = '') {
				$dir = opendir($src);
				if (!$dir) {
					throw new Exception("recursive_copy() source directory '".$src."' does not exist.");
				}
				if (!is_dir($dst)) {
					if (!mkdir($dst,02770,true)) {
						throw new Exception("recursive_copy() failed to create destination directory '".$dst."'");
					}
				}
				while(false !== ( $file = readdir($dir)) ) {
					if (( $file != '.' ) && ( $file != '..' )) {
						if ( is_dir($src . '/' . $file) ) {
							recursive_copy($src . '/' . $file,$dst . '/' . $file);
						}
						else {
							copy($src . '/' . $file,$dst . '/' . $file);
						}
					}
				}
				closedir($dir);
			}
		}
	}
	if (!function_exists('recursive_delete')) {
		if (file_exists('/bin/rm')) {
			function recursive_delete($dir) {
				//$this->write_debug('rm -Rf '.$dir.'/*');
				exec ('rm -Rf '.$dir.'/*');
				clearstatcache();
			}
		}elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
			function recursive_delete($dir) {
				$dst = normalize_path_to_os($dst);
				//$this->write_debug("del /S /F /Q \"$dir\"");
				exec("del /S /F /Q \"$dir\"");
				clearstatcache();
			}
		}else{
			function recursive_delete($dir) {
				foreach (glob($dir) as $file) {
					if (is_dir($file)) {
						//$this->write_debug("rm dir: ".$file);
						recursive_delete("$file/*");
						rmdir($file);
					} else {
						//$this->write_debug("delete file: ".$file);
						unlink($file);
					}
				}
				clearstatcache();
			}
		}
    }
    
    if (!function_exists('file_upload')) {
        function file_upload($field = '', $file_type = '', $dest_dir = '') {
                $uploadtempdir = $_ENV["TEMP"]."\\";
                ini_set('upload_tmp_dir', $uploadtempdir);
                $tmp_name = $_FILES[$field]["tmp_name"];
                $file_name = $_FILES[$field]["name"];
                $file_type = $_FILES[$field]["type"];
                $file_size = $_FILES[$field]["size"];
                $file_ext = get_ext($file_name);
                $file_name_orig = $file_name;
                $file_name_base = substr($file_name, 0, (strlen($file_name) - (strlen($file_ext)+1)));
                //$dest_dir = '/tmp';
                if ($file_size ==  0){
                    return;
                }
                if (!is_dir($dest_dir)) {
                    echo "dest_dir not found<br />\n";
                    return;
                }
                //check if allowed file type
                if ($file_type == "img") {
                        switch (strtolower($file_ext)) {
                            case "jpg":
                                break;
                            case "png":
                                break;
                            case "gif":
                                break;
                            case "bmp":
                                break;
                            case "psd":
                                break;
                            case "tif":
                                break;
                            default:
                                return false;
                        }
                }
                if ($file_type == "file") {
                    switch (strtolower($file_ext)) {
                        case "doc":
                            break;
                        case "pdf":
                            break;
                        case "ppt":
                            break;
                        case "xls":
                            break;
                        case "zip":
                            break;
                        case "exe":
                            break;
                        default:
                            return false;
                        }
                }
                //find unique filename: check if file exists if it does then increment the filename
                    $i = 1;
                    while( file_exists($dest_dir.'/'.$file_name)) {
                        if (strlen($file_ext)> 0) {
                            $file_name = $file_name_base . $i .'.'. $file_ext;
                        }
                        else {
                            $file_name = $file_name_orig . $i;
                        }
                        $i++;
                    }
                //echo "file_type: ".$file_type."<br />\n";
                //echo "tmp_name: ".$tmp_name."<br />\n";
                //echo "file_name: ".$file_name."<br />\n";
                //echo "file_ext: ".$file_ext."<br />\n";
                //echo "file_name_orig: ".$file_name_orig."<br />\n";
                //echo "file_name_base: ".$file_name_base."<br />\n";
                //echo "dest_dir: ".$dest_dir."<br />\n";
                //move the file to upload directory
                //bool move_uploaded_file  ( string $filename, string $destination  )
                    if (move_uploaded_file($tmp_name, $dest_dir.'/'.$file_name)){
                         return $file_name;
                    }
                    else {
                        echo "File upload failed!  Here's some debugging info:\n";
                        return false;
                    }
                    exit;
        } //end function
    }
    if ( !function_exists('sys_get_temp_dir')) {
        function sys_get_temp_dir() {
            if( $temp=getenv('TMP') )        return $temp;
            if( $temp=getenv('TEMP') )        return $temp;
            if( $temp=getenv('TMPDIR') )    return $temp;
            $temp=tempnam(__FILE__,'');
            if (file_exists($temp)) {
                unlink($temp);
                return dirname($temp);
            }
            return null;
        }
	}
	
	if(!function_exists('generate_password')) {
		//generate a random password with upper, lowercase and symbols
		function generate_password($length = 0, $strength = 0) {
			$password = '';
			$charset = '';
			if ($length === 0 && $strength === 0) { //set length and strenth if specified in default settings and strength isn't numeric-only
				$length = (is_numeric($_SESSION["security"]["password_length"]["numeric"])) ? $_SESSION["security"]["password_length"]["numeric"] : 10;
				$strength = (is_numeric($_SESSION["security"]["password_strength"]["numeric"])) ? $_SESSION["security"]["password_strength"]["numeric"] : 4;
			}
			if ($strength >= 1) { $charset .= "0123456789"; }
			if ($strength >= 2) { $charset .= "abcdefghijkmnopqrstuvwxyz";	}
			if ($strength >= 3) { $charset .= "ABCDEFGHIJKLMNPQRSTUVWXYZ";	}
			if ($strength >= 4) { $charset .= "!!!!!^$%*?....."; }
			srand((double)microtime() * rand(1000000, 9999999));
			while ($length > 0) {
					$password .= $charset[rand(0, strlen($charset)-1)];
					$length--;
			}
			return $password;
		}
	}
	
	if(!function_exists('get_time_zone_offset')) {
		function get_time_zone_offset($remote_tz, $origin_tz = 'UTC') {
			$origin_dtz = new DateTimeZone($origin_tz);
			$remote_dtz = new DateTimeZone($remote_tz);
			$origin_dt = new DateTime("now", $origin_dtz);
			$remote_dt = new DateTime("now", $remote_dtz);
			$offset = $remote_dtz->getOffset($remote_dt) - $origin_dtz->getOffset($origin_dt);
			return $offset;
		}
	}

	if(!function_exists('number_pad')) {
	
		function number_pad($number,$n) {
			return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
		}
	}
    
    // ellipsis nicely truncate long text
    if(!function_exists('ellipsis')) {
        function ellipsis($string, $max_characters, $preserve_word = true) {
            if ($max_characters+$x >= strlen($string)) { return $string; }
            if ($preserve_word) {
                for ($x = 0; $x < strlen($string); $x++) {
                    if ($string{$max_characters+$x} == " ") {
                        return substr($string,0,$max_characters+$x)." ...";
                    }
                    else { continue; }
                }
            }
            else {
                return substr($string,0,$max_characters)." ...";
            }
        }
    }
    //encrypt a string
	if (!function_exists('encrypt')) {
		function encrypt($key, $str_to_enc) {
			return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $str_to_enc, MCRYPT_MODE_CBC, md5(md5($key))));
		}
	}
//decrypt a string
	if (!function_exists('decrypt')) {
		function decrypt($key, $str_to_dec) {
			return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($str_to_dec), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		}
	}
//json detection
	if (!function_exists('is_json')) {
		function is_json($str) {
			return (is_string($str) && is_object(json_decode($str))) ? true : false;
		}
	}
//mac detection
	if (!function_exists('is_mac')) {
		function is_mac($str) {
			return (preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $str) == 1) ? true : false;
		}
	}
//format mac address
	if (!function_exists('format_mac')) {
		function format_mac($str, $delim = '-', $case = 'lower') {
			if (is_mac($str)) {
				$str = join($delim, str_split($str, 2));
				$str = ($case == 'upper') ? strtoupper($str) : strtolower($str);
			}
			return $str;
		}
	}
//transparent gif
	if (!function_exists('img_spacer')) {
		function img_spacer($width = '1px', $height = '1px', $custom = null) {
			return "<img src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' style='width: ".$width."; height: ".$height."; ".$custom."'>";
		}
	}
//lower case
	if (!function_exists('lower_case')) {
		function lower_case($string) {
			if (function_exists('mb_strtolower')) {
				return mb_strtolower($string, 'UTF-8');
			}
			else {
				return strtolower($string);
			}
		}
	}
//upper case
	if (!function_exists('upper_case')) {
		function upper_case($string) {
			if (function_exists('mb_strtoupper')) {
				return mb_strtoupper($string, 'UTF-8');
			}
			else {
				return strtoupper($string);
			}
		}
}
//email validate
	if (!function_exists('email_validate')) {
		function email_validate($strEmail){
			$validRegExp =  '/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.[a-zA-Z]{2,3}$/';
			// search email text for regular exp matches
			preg_match($validRegExp, $strEmail, $matches, PREG_OFFSET_CAPTURE);
			if (count($matches) == 0) {
				return false;
			}
			else {
				return true;
			}
		}
	}

	if (!function_exists('env')) {
		/**
		 * Gets the value of an environment variable
		 *
		 * @param  string $key
		 * @param  mixed $default
		 * @return mixed
		 */
		function env($key, $default = null)
		{
			$value = getenv($key);
			if ($value === false) {
				return $default;
			}
			switch (strtolower($value)) {
				case 'true':
				case '(true)':
					return true;
				case 'false':
				case '(false)':
					return false;
				case 'empty':
				case '(empty)':
					return '';
				case 'null':
				case '(null)':
					return null;
			}
			$strLen = strlen($value);
			if ($strLen > 1 && $value[0] === '"' && $value[$strLen - 1] === '"') {
				return substr($value, 1, -1);
			}
			return $value;
		}
	}

	if (!function_exists('base_path')) {
		/**
		 * Get the path to the base folder
		 *
		 * @return string
		 */
		function base_path()
		{
			return dirname(__DIR__);
		}
	}

