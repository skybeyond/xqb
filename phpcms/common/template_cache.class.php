<?php
/**
 * Created by PhpStorm.
 * User: beyond
 * Date: 2018/5/30
 * Time: 15:48
 */
final class template_cache{
    public function template_compile($template) {
        $tplfile = $_tpl = PC_PATH.'templates'.DIRECTORY_SEPARATOR.$template.'.html';
        if (! file_exists ( $tplfile )) {
            exit($tplfile." is not exists!");
        }
        $content = @file_get_contents ( $tplfile );

        $filepath = CACHE_PATH.'caches_tpl'.DIRECTORY_SEPARATOR;
        if(!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }
        $compiledtplfile = $filepath.$template.'.php';
        $content = $this->template_parse($content);
        $strlen = file_put_contents ( $compiledtplfile, $content );
        chmod ( $compiledtplfile, 0777 );
        return $strlen;
    }




    public function template_parse($str) {
        $str = preg_replace ( "/\{template_auto\s+(.+)\}/", "<?php include template_auto(\\1); ?>", $str );
        $str = preg_replace ( "/\{include\s+(.+)\}/", "<?php include \\1; ?>", $str );
        $str = preg_replace ( "/\{php\s+(.+)\}/", "<?php \\1?>", $str );
        $str = preg_replace ( "/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str );
        $str = preg_replace ( "/\{else\}/", "<?php } else { ?>", $str );
        $str = preg_replace ( "/\{elseif\s+(.+?)\}/", "<?php } elseif (\\1) { ?>", $str );
        $str = preg_replace ( "/\{\/if\}/", "<?php } ?>", $str );
        //for 循环
        $str = preg_replace("/\{for\s+(.+?)\}/","<?php for(\\1) { ?>",$str);
        $str = preg_replace("/\{\/for\}/","<?php } ?>",$str);
        //++ --
        $str = preg_replace("/\{\+\+(.+?)\}/","<?php ++\\1; ?>",$str);
        $str = preg_replace("/\{\-\-(.+?)\}/","<?php ++\\1; ?>",$str);
        $str = preg_replace("/\{(.+?)\+\+\}/","<?php \\1++; ?>",$str);
        $str = preg_replace("/\{(.+?)\-\-\}/","<?php \\1--; ?>",$str);
        $str = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\}/", "<?php \$n=1;if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $str );
        $str = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php \$n=1; if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $str );
        $str = preg_replace ( "/\{\/loop\}/", "<?php \$n++;}unset(\$n); ?>", $str );
        $str = preg_replace ( "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str );
        $str = preg_replace ( "/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str );
        $str = preg_replace ( "/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1;?>", $str );
        $str = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "\$this->addquote('<?php echo \\1;?>')",$str);
        $str = preg_replace ( "/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $str );
        $str = "<?php defined('IN_PHPCMS') or exit('illegal infiltration.'); ?>" . $str;
        return $str;
    }

}