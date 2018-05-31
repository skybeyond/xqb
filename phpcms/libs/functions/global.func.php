<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/23
 * Time: 16:10
 */


function new_addslashes($string){
    if(!is_array($string)) return addslashes($string);
    foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;
}


function cnf($name,$value=''){
    static $config;
    if($config) {
    } else {
        $config  = Loader::load_config();
    }
    return $value?$config[$name][$value]:$config[$name];
}

function array2nr($d,&$str='') {
    if (is_array($d)) {
        foreach ($d as $k =>$v ) {
            if(is_array($v)) {
                $str .= "{$k}\n";
            } else {
                $str .= "{$k}:{$v}\n";
            }
            array2nr($v,$str);
        }
    }
    return $str;
}


/**
 * 获取请求ip
 *
 * @return ip地址
 */
function ip() {
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}

function  static_logs($data=array(),$file='log'){
    $data = is_array($data)?$data:array($data);
    $data = str_replace("\n","",array2nr($data));
    $log_dir = CACHE_PATH."logs".DIRECTORY_SEPARATOR.date("Ym").DIRECTORY_SEPARATOR.date("md").DIRECTORY_SEPARATOR;
    if(!is_dir($log_dir)) {
        mkdir($log_dir, 0777, true);
    }
    error_log("<?php exit;?>"."\t".date("Y-m-d H:i:s",time())."\t".ip()."\t".$data."\n",3,$log_dir.$file."_".date("Ymd").".php");
}



function template($template = 'index') {
    $template_cache = new template_cache();
    $compiledtplfile = PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_tpl'.DIRECTORY_SEPARATOR.$template.'.php';
    if(file_exists(PHPCMS_PATH.'templates'.DIRECTORY_SEPARATOR.$template.'.html')) {
        if(!file_exists($compiledtplfile) || (@filemtime(PC_PATH.'templates'.DIRECTORY_SEPARATOR.$template.'.html') > @filemtime($compiledtplfile))) {
            $template_cache->template_compile($template);
        }
    } else {
        $compiledtplfile = PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_tpl'.DIRECTORY_SEPARATOR.$template.'.php';
        if(!file_exists($compiledtplfile) || (file_exists(PC_PATH.'templates'.DIRECTORY_SEPARATOR.$template.'.html') && filemtime(PC_PATH.'templates'.DIRECTORY_SEPARATOR.$template.'.html') > filemtime($compiledtplfile))) {
            $template_cache->template_compile($template);
        } elseif (!file_exists(PC_PATH.'templates'.DIRECTORY_SEPARATOR.$template.'.html')) {
            echo('Template does not exist.'.DIRECTORY_SEPARATOR.$template.'.html');
        }
    }
    return $compiledtplfile;
}