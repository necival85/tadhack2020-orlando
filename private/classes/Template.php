<?php

class Template {
    /////////////////////////////////////////////////////////////////////////
    public $output = '';
    public $src_file = '';

    /////////////////////////////////////////////////////////////////////////
    function __construct($tpl='') {
        $this->new($tpl);
        return true;
    } // END MAJIC construct

    /////////////////////////////////////////////////////////////////////////
    private function newTemplateFromFile($tpl_file='') {
        if (($tpl_file != '') && (!file_exists($tpl_file))) {
            $tpl_file = $this->findTpl($tpl_file);
        } // END IF findTpl
        if (($tpl_file != '') && file_exists($tpl_file)) {
            $this->src_file = $tpl_file;
            $this->output = file_get_contents($tpl_file);
            if ($this->output === false) { $this->output = ''; }
        } // END IF tpl_file
        return $this;
    } // END METHOD newTemplateFromFile

    private function newTemplateFromText($tpl_text='') {
        if (is_string($tpl_text)) {
            $this->output = $tpl_text;
        } // END IF template_text
        return $this;
    } // END METHOD newTemplateFromText

    public function findTpl($tpl='') {
        if ($tpl != '') {
            if (file_exists($tpl)) { return $tpl; } // absolute file matched
            if (file_exists(dirname(dirname(__DIR__)) .'/'. $tpl)) { return $tpl; } // {@core_private_dir}/{@tpl} Matched
            if ((substr($tpl, 0, 1) != '/') && (stripos($tpl, '.tpl') !== false)) {
                // Seems like a template filename, lets check for files
                $files = array();
                if (strpos($tpl, 'tpl/') !== false) {
                    $files = glob(dirname(dirname(__DIR__)).'/public_html/' . $tpl);
                }
                if (empty($files) && (strpos($tpl, '/') !== false)) {
                    $files = glob(dirname(dirname(__DIR__)).'/public_html/tpl/' . $tpl);
                }
                if (empty($files)) {
                    $files = glob(dirname(dirname(__DIR__)).'/public_html/tpl/*/' . $tpl);
                }
                if (!empty($files)) {
                    foreach ($files as $file) {
                        //error_log(basename(__FILE__).":".__LINE__.": file = ".$file);
                        return $file;
                    } // END FOREACH file
                } // END IF files
            } // END IF .tpl
        } // END IF tpl
        return $tpl;
    } // END METHOD findTpl

    public function new($tpl='') {
        if (($tpl != '') && (stripos($tpl, 'tpl') !== false)) {
            $tpl = $this->findTpl($tpl);
        } // END IF findTpl
        if (($tpl != '') && file_exists($tpl)) {
            $this->newTemplateFromFile($tpl);
        } else if ($tpl != '') {
            $this->newTemplateFromText($tpl);
        } else if ($this->src_file != '') {
            $this->newTemplateFromFile($this->src_file);
        } // END IF template
        return $this;
    } // END METHOD new

    public function set($var_name='', $var_value='', $output='', $recursive=true) {
        if (is_string($output) && ($output != '')) {
            $get_output = true;
            $set_output = false;
        } else if ($output === true) {
            $get_output = true;
            $set_output = true;
            $output = $this->output;
        } else{
            $get_output = false;
            $set_output = true;
            $output = $this->output;
        }
        if ($output == '') { return ($get_output ? $output : $this ); }
        if ((is_array($var_name) || is_object($var_name)) && (!empty($var_name))) {
            foreach ($var_name as $key => $value) {
                $output = $this->set($key, $value, $output, $recursive);
            } // END FOREACH key => value
        } else if (is_string($var_name) && ($var_name != '')) {
            if (is_array($var_value)) {
                if ($recursive) {
                    foreach ($var_value as $k => $v) { $output = $this->set($k, $v, $output, $recursive); }
                } else {
                    if (strpos($output, '[@'.$var_name.']') !== false) {
                        $output = str_replace('[@'.$var_name.']', json_encode($var_value), $output);
                    } else if (strpos($output, '{@'.$var_name.'}') !== false) {
                        $output = str_replace('{@'.$var_name.'}', json_encode($var_value), $output);
                    } // END IF [@var_name] or {@var_name}
                } // END IF recursive
            } else if (strpos($output, '[@'.$var_name.']') !== false) {
                $output = str_replace('[@'.$var_name.']', $var_value, $output);
            } else if (strpos($output, '{@'.$var_name.'}') !== false) {
                $output = str_replace('{@'.$var_name.'}', $var_value, $output);
            } // END IF {@var_name}
        } // END IF var_name
        if ($set_output === true) { $this->output = $output; }
        return ($get_output ? $output : $this );
    } // END METHOD set

    public function missed($output='') {
        $strpos_start = strpos($output, '[@');
        $strpos_end = strpos($output, ']');
        if (($strpos_start !== false) && ($strpos_start < $strpos_end)) {
            $pattern = '/\[\@([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\]/';
            $missed = preg_match_all($pattern, $output, $missed_vars);
            //error_log(basename(__FILE__).":".__LINE__.": missed = ". $missed . ", missed_vars = ".print_r($missed_vars, true));
            if (($missed > 0) && isset($missed_vars[1]) && (!empty($missed_vars[1]))) {
                foreach ($missed_vars[1] as $key) { $output = $this->set($key, '', $output, false); }
            } // END IF missed
        } // END IF {@.*}

        $strpos_start = strpos($output, '{@');
        $strpos_end = strpos($output, '}');
        if (($strpos_start !== false) && ($strpos_start < $strpos_end)) {
            $pattern = '/\{\@([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/';
            $missed = preg_match_all($pattern, $output, $missed_vars);
            //error_log(basename(__FILE__).":".__LINE__.": missed = ". $missed . ", missed_vars = ".print_r($missed_vars, true));
            if (($missed > 0) && isset($missed_vars[1]) && (!empty($missed_vars[1]))) {
                foreach ($missed_vars[1] as $key) { $output = $this->set($key, '', $output, false); }
            } // END IF missed
        } // END IF {@.*}

        return $output;
    } // END METHOD missed

    public function output($temp_set=array()) {
        $output = $this->output;

        // Replace temp vars that are set on output() execution
        $output = $this->set($temp_set, null, $output, true);

        // Replace any missed vars with nothing
        //$output = $this->missed($output);
        //error_log(basename(__FILE__).":".__LINE__.": output = " . $output);
        return $output;
    } // END METHOD output

} // END CLASS Template

?>