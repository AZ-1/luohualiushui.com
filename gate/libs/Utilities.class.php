<?php
namespace Gate\Libs;
/**
 * util工具集
 */

class Utilities{

		/*
		 * timestamp time_gap
		 *
		 */
        public static function getTimeGapMsg($time) {
			$time_gap = time() - $time;
            if ($time_gap <= 60) {
                $time_msg = '1分钟内';
            }
            elseif ($time_gap <= 120) {
                $time_msg = '1分钟前';
            }
            elseif ($time_gap <= 180) {
                $time_msg = '2分钟前';
            }
            elseif ( $time_gap <= 600) {
                $time_msg = '3分钟前';
            }
            elseif ($time_gap <= 1800) {
                $time_msg = '10分钟前';
            }
            elseif ( $time_gap <= 3600) {
                $time_msg = '30分钟前';
            }
            elseif ( $time_gap <= 3 * 3600) {
                $time_msg = '1小时前';
            }
            elseif ( $time_gap <= 6 * 3600) {
                $time_msg = '3小时前';
            }
            elseif ( $time_gap <= 12 * 3600) {
                $time_msg = '6小时前';
            }
            elseif ( $time_gap <= 24 * 3600) {
                $time_msg = '12小时前';
            }
            elseif ( $time_gap <= 48 * 3600) {
                $time_msg = '1天前';
            }
            elseif ( $time_gap <= 72 * 3600) {
                $time_msg = '2天前';
            }
            elseif ( date('Y', $time) == date('Y') ) {
                $time_msg = date('M j', $time);
            }
            else {
                $time_msg = date('M j,Y', $time);
            }
            return $time_msg;
    }

	public static function parseRequestHeaders() {
		$headers = array();
		foreach($_SERVER as $key => $value) {
			if (substr($key, 0, 5) <> 'HTTP_') {
				continue;
			}
			$header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
			$headers[$header] = $value;
		}
		return $headers;
	}

	/**
	 * Jsonize data and indents the flat JSON string to make it more
	 * human-readable.
	 *
	 * @link
	 * http://recursive-design.com/blog/2008/03/11/format-json-with-php/
	 *
	 * @param mixed $data The data to be jsonized.
	 * @return string Indented version of the original JSON string.
	 */
	public static function jsonEncode($data, $pretty_print = FALSE, $options = 0) {
		$json = json_encode($data, $options);
		if (!$pretty_print) {
			return $json;
		}

		$result        = '';
		$pos           = 0;
		$str_len       = strlen($json);
		$indent_str    = '    ';
		$new_line      = "\n";
		$prev_char     = '';
		$out_of_quotes = TRUE;

		for ($i = 0; $i <= $str_len; $i++) {

			// Grab the next character in the string.
			$char = substr($json, $i, 1);

			// Are we inside a quoted string?
			if ($char == '"' && $prev_char != '\\') {
				$out_of_quotes = !$out_of_quotes;
			}
			else if (($char == '}' || $char == ']') && $out_of_quotes) {
				// If this character is the end of an element, 
				// output a new line and indent the next line.
				$result .= $new_line;
				$pos--;
				for ($j = 0; $j < $pos; $j++) {
					$result .= $indent_str;
				}
			}
			
			// Add the character to the result string.
			$result .= $char;

			// If the last character was the beginning of an element, 
			// output a new line and indent the next line.
			if (($char == ',' || $char == '{' || $char == '[') && $out_of_quotes) {
				$result .= $new_line;
				if ($char == '{' || $char == '[') {
					$pos ++;
				}
				
				for ($j = 0; $j < $pos; $j++) {
					$result .= $indent_str;
				}
			}
			
			$prev_char = $char;
		}

		return $result;
	}

    public static function zaddslashes($string, $force = 0, $strip = FALSE) {
        if (!defined("MAGIC_QUOTES_GPC")) {
            define("MAGIC_QUOTES_GPC", "");
        }
        if (!MAGIC_QUOTES_GPC || $force) {
            if (is_array($string)) {
                foreach ($string as $key => $val) {
                    $string[$key] = \Gate\Libs\Utilities::zaddslashes($val, $force, $strip);
                }
            }
            else {
                $string = ($strip ? stripslashes($string) : $string);
                $string = htmlspecialchars($string);
            }
        }
        return $string;
    }

	public static function htmlStripTags($str){
		$str = htmlspecialchars_decode($str);
		$str = strip_tags($str);
		return $str;
	}

	public static function htmlSpDecode($str){
		$str = htmlspecialchars_decode($str);
		$str = strip_tags($str, '<p><br><img>');
		$str = preg_replace('/style=["|\'][^"|\']*["|\']/U', '', $str);
		//$str = preg_replace('/<div[^>]*>/', '', $str);
		//$str = preg_replace('#</div>#', '', $str);
		return $str;
	}

    public static function sortArray($array, $order_by, $order_type = 'ASC') {
        if (!is_array($array)) {
            return array();
        }
        $order_type = strtoupper($order_type);
        if ($order_type != 'DESC') {
            $order_type = SORT_ASC;
        } else {
            $order_type = SORT_DESC;
        }

        $order_by_array = array ();
        foreach ( $array as $k => $v ) {
            $order_by_array [] = $array [$k] [$order_by];
        }
        array_multisort($order_by_array, $order_type, $array);
        return $array;
    }

    public static function changeDataKeys($data, $keyName, $toLowerCase=false) {
        $resArr = array ();
        if(empty($data)){
            return false;
        }
        foreach ( $data as $v ) {
            $k = $v [$keyName];
            if( $toLowerCase === true ) {
                $k = strtolower($k);
            }
            $resArr [$k] = $v;
        }
        return $resArr;
    }
	
    public static function getUniqueId() {
        return md5(uniqid(mt_rand(), TRUE) . $_SERVER['REQUEST_TIME'] . mt_rand());
    }

	public static function objectToArray($obj) {
		if (is_object($obj)) { 
			$obj = get_object_vars($obj);
		} 
		if (is_array($obj)) { 
			return array_map(array('self',  __FUNCTION__), $obj);
		} 
		return $obj;
	}

    public static function DataToArray($dbData, $keyword) {
        $retArray = array ();
        if (is_array ( $dbData ) == false or empty ( $dbData )) {
            return $retArray;
        }
        foreach ( $dbData as $oneData ) {
            if (isset ( $oneData [$keyword] ) and empty ( $oneData [$keyword] ) == false) {
                $retArray [] = $oneData [$keyword];
            }
        }
        return $retArray;
    }

    /**
     * 速度比array_diff快，但只支持两个数组。
     * @warn 因为多一个array_flip，内存占用峰值多一些。
     */
    public static function array_diff_fast($firstArray, $secondArray) {
        if (!is_array($firstArray) || !is_array($secondArray)) {
            return FALSE;
        }
        $secondArray = array_flip($secondArray);
        foreach ($firstArray as $key => $value) {
            if (isset($secondArray[$value])) {
                unset($firstArray[$key]);
            }
        }
        return $firstArray;
    }

	/**
	 * @author guochao
	 */
	public static function ArrayToObject($data) {
		if (is_array($data)) {
			//return (object) array_map(array(__CLASS__, __FUNCTION__), $data);
			return json_decode(json_encode($data), FALSE);
		}	
		else {
			return $data;
		}
	}

	/*
	 * 匹配utf8中文
	 */
	public static function cutStr($string, $sublen, $start = 0, $code = 'UTF-8') 
	{ 
		if($code == 'UTF-8') 
		{ 
			$pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
			preg_match_all($pa, $string, $t_string); if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."..."; 
			return join('', array_slice($t_string[0], $start, $sublen)); 
		} 
		else 
		{ 
			$start = $start*2; 
			$sublen = $sublen*2; 
			$strlen = strlen($string); 
			$tmpstr = ''; for($i=0; $i<$strlen; $i++) 
		{ 
			if($i>=$start && $i<($start+$sublen)) 
			{ 
				if(ord(substr($string, $i, 1))>129) 
				{ 
					$tmpstr.= substr($string, $i, 2); 
				} 
				else 
				{ 
					$tmpstr.= substr($string, $i, 1); 
				} 
			} 
			if(ord(substr($string, $i, 1))>129) $i++; 
		} 
			if(strlen($tmpstr)<$strlen ) $tmpstr.= "..."; 
			return $tmpstr; 
		} 
	}

	/*
	 * 用户ip
	 */
	public static function getClientIp(){
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else $ip = 0;
		return ip2long($ip);
	}

	/*
	 * 随机数组
	 */
	public static function array_random($arr, $num = 1) {
	    shuffle($arr);
	    
	    $r = array();
		for ($i = 0; $i < $num; $i++) {
			$r[] = $arr[$i];
		    }
		return $num == 1 ? $r[0] : $r;
	}

	/*
	 * 随机数组
	 */
	public static function array_random_assoc($arr, $num = 1) {
	    $keys = array_keys($arr);
	    shuffle($keys);
	    
	    $r = array();
		for ($i = 0; $i < $num; $i++) {
			$r[$keys[$i]] = $arr[$keys[$i]];
		    }
	    return $r;
	}

}
