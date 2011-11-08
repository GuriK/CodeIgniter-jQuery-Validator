<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Jquery_validation {
    private $rules;    // json rules for jQuery validator
    private $messages; // custom error messages for jQuery validator
    private $js_rules = array(  'required'       => 'required',
                                'matches'        => 'equalTo',
                                'min_length'     => 'minlength',
                                'max_length'     => 'maxlength',
                                'greater_than'   => 'min',
                                'less_than'      => 'max',
                                'numeric'        => 'digits',
                                'valid_email'    => 'email'
                             ); // CI rule names to jQuery validator rules
    /**
    * Build and return jQuery validation code
    *
    * @access public
    * @param  string
    * @return string
    */
    public function run($form_name)
    {
        return $this->build_script($form_name);
    }
    
    /**
    * Build jQuery validationcode
    *
    * @access private
    * @param  string
    * @return string
    */
    private function build_script($form_name) 
    {
        $script = '$(document).ready(function() { $("'.$form_name.'").validate({rules: %s,messages: %s});});';
        return sprintf($script, $this->rules, ($this->messages ? $this->messages : '{}'));
    }
    /**
    * Set validation rules
    * 
    * @access public
    * @param  array
    * @return string json formatted string
    */
    public function set_rules($rules) 
    {
        foreach ($rules as $k => $v) 
        {
            // CI uses "|" delimiter to apply different rules. Let's split it ... 
            $expl_rules = explode('|', $v['rules']);
            foreach ($expl_rules as $index => $rule) 
            {   
                // check and parse rule if it has parameter. eg. min_length[2]
                if (preg_match("/(.*?)\[(.*)\]/", $rule, $match))
                {
                    // Check if we have similar rule in jQuery plugin
                    if($this->is_js_rule($match[1])) 
                    {
                        // If so, let's use jQuery rule name instead of CI's one
                        $json[$v['field']][$this->get_js_rule($match[1])] = $match[2];	
                    }
                }
                // jQuery plugin doesn't support callback like CI, so we'll ignore it and convert everything else 
                elseif (!preg_match("/callback\_/",$rule))
                {
                    if($this->is_js_rule($rule)) 
                    {
                        $json[$v['field']][$this->get_js_rule($rule)] = TRUE;	
                    }
                }
            }
        }
        $this->rules = json_encode($json);
        return $this->rules;
    }
    
    /**
    * check if we have alternative rule of CI in jQuery
    *
    * @access private
    * @param  string
    * @return bool
    */
    private function is_js_rule($filter) 
    {
        if (in_array($filter,array_keys($this->js_rules)))
        {
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    /**
    * Get rule name
    *
    * get jQuery rule name by CI rule name
    *
    * @access private
    * @param  string
    * @return string
    */
    private function get_js_rule($filter)
    {
        return $this->js_rules[$filter];
    }
    
    /**
    * Set messages
    *
    * set custom error messages on each rule for jQuery validation
    * 
    * @access public
    * @param  array
    * @return string json formated string
    */
    public function set_messages($messages) 
    {
        // We do same as above in set_rules function  check and convert CI to jQuery rules
        foreach ($messages as $k=>$v) 
        {
            foreach ($v as $a=>$v) 
            {
                if ($this->is_js_rule($a)) 
                {
                    // Remove CI rule name ...
                    unset($messages[$k][$a]);
                    // and insert jQuery's one 
                    $messages[$k][$this->get_js_rule($a)] = $v;
                }
            }
        }
        $this->messages = json_encode($messages);
        return $this->messages;
    }
}
// END Jquery_validation class

/* End of file Jquery_validation.php */
/* Location: ./application/libraries/Jquery_validation.php */
