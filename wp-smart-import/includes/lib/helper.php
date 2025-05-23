<?php 
if (!defined('ABSPATH')) { exit; } // Exit if accessed directly
if (!class_exists('wpsi_helper')) {
	class wpsi_helper {

        static function key_count($array) {
            return array_count_values($array);
        }

		static function __($str) {
            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText, WordPress.WP.I18n.NonSingularStringLiteralDomain
            return __($str, wpSmartImport::getVar('lang'));
        }
        
        static function _d($arr, $key, $default = '') {
            $ret = isset($arr[$key]) ? esc_attr($arr[$key]) : $default;
            return $ret;
        }
        
        static function _dref(&$arr, $key, $default = '') { // assign value by reference
            $arr[$key] = self::_d($arr, $key, $default); 
        }
        
        static function _dr($arr, $key, $default = '') {
            return empty($arr[$key]) ? $default : '';
        }

        static function check_schar($str = '') {
            if(empty($str)) return;
            $esc_arr = array("," => " ", "&" => " ", "?" => " ", "|" => " ");
            $newstr = strtr($str, $esc_arr);
            $temp = explode(' ', $newstr);
            foreach ($temp as $key => $value) {
                if (!empty($value)) {
                    return $value;
                }
            }
            return false;
        }

        static function TrimArray($arr) { /*Trim Recursive */
            if (!is_array($arr)){ return $arr; }
            foreach ( $arr as $key => $value){
                if (is_array($value)){
                    $arr[$key] = self::TrimArray($value);
                } else {
                    $arr[$key] = trim($value);
                }
            }
            return $arr;
        }

        /**
         * Recursive remove/delete file from dir
         * 
         * @param $path (string)
         * @since  0.1
        */
        static function remove_recursive($path) {
             // Open the source directory to read in files
            $i = new DirectoryIterator($path);
            foreach($i as $f) {
                if ($f->isFile()) {
                    @unlink($f->getRealPath());
                } else if (!$f->isDot() && $f->isDir()) {
                     self::remove_recursive($f->getRealPath());
                }
            }
            @rmdir($path); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
        }

        /**
         * Recursive sanitation for text or array
         * 
         * @param $array_or_string (array|string)
         * @since  0.1
         * @return mixed
        */
        static function recursive_sanitize_text_field($array_or_string) {
            $reserve_field = array(
                'textarea' => array('post_des', 'media_imgs', 'download_imgs')
            );
            if (is_string($array_or_string)) {
                $array_or_string = sanitize_text_field($array_or_string);
            } elseif (is_array($array_or_string)) {
                foreach ($array_or_string as $key => &$value) {
                    if (is_array($value)) {
                        $value = self::recursive_sanitize_text_field( wp_unslash( $value ) );
                    } else {
                        if (in_array($key, $reserve_field['textarea'])) {
                            $value = sanitize_textarea_field($value);
                        } else {
                            $value = sanitize_text_field($value);
                        }
                    }
                }
            }
            return $array_or_string;
        }

        /* Return or die request if sertain condition false */
        static function wp_die_request($msg = 'Error Try Again !', $is_ajax = true, $return_arr = true) {
            $res = array('status' => 'error', 'msg' => $msg);
            if ($is_ajax) {
                if ($return_arr)
                    echo json_encode($res);
                else
                    echo esc_attr( $msg );
                wp_die();
            } else {
                if ($return_arr)
                    return $res;
                else 
                    return false;
            }
        }

        // array_key_exists_recursive
        static  function array_key_exists_r($key, $array) {
            if( array_key_exists($key, (array) $array)) 
                return true;
            else {
                foreach($array as $value) {
                    if (is_array($value)) {
                        if (self::array_key_exists_r($key, $value)) return true;
                    }
                }
            }
            return false;
        }

        static public function isHTML($string){
            return $string != wp_strip_all_tags( $string ) ? true : false;
        }

        static public function basename($url) {
            $url_arr =  explode('/', $url);
            return end($url_arr);
        }
    } // End wpsi_helper Class
}