## CodeIgniter jQuery validator library

This is very simple library which generates Javascript code for forms validation

It generates validation rules and custom error messages for [jQuery Validation Plugin](http://bassistance.de/jquery-plugins/jquery-plugin-validation/)

## Usage

1) Load library in your controller.

```php
$this->load->library('jquery_validation');
```

2) Set CodeIgniter standard validation rules ( The same rules format which has [form_validtion library](http://codeigniter.com/user_guide/libraries/form_validation.html#validationrulesasarray) ).

```php
$rules = array(
                array(
                     'field'   => 'username',
                     'label'   => 'Username',
                     'rules'   => 'required|min_length[2]'
                  ),
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'required|valid_email'
                  ),
               array(
                     'field'   => 'url',
                     'label'   => 'URL',
                     'rules'   => 'required'
                  )
           );
```

3) Set error messages.

```php
$messages = array(
                 'username'  => array( 'required'    => "Username is required",
                                       'min_length'  => "Please enter more then 2 char"
                                     ),
                                     
                 'email'     => array( 'required'    => "Email is required",
                                       'valid_email' => "Please enter valid email"
                                     )
                 );

```

4) Apply validation rules and messages to library.

```php

$this->jquery_validation->set_rules($rules);
$this->jquery_validation->set_messages($messages);

```

5) Generate Javascript validation code.

```php
// pass css selector for your form to run method

$validation_script = $this->jquery_validation->run('#registration_form');

// echo $validation_script in your <script> tag
```

6) Enjoy :-)