<?php
require_once 'app/backend/core/Init.php';

if (Input::exists()) {
    if (Token::check(Input::get('csrf_token'))) {
        $validate = new Validation();

        $validation = $validate->check($_POST, array(

            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),

            'password' => array(
                'required' => true,
                'min' => 6
            ),

            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            )
        ));

        if ($validate->passed()) {
            try {
                $user->create(array(
                    'username'  => Input::get('username'),
                    'password'  => Password::hash(Input::get('password')),
                ));
                Session::flash('register-success', 'Thanks for registering! You can login now.');
                Redirect::to('index.php');
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo '<div class="alert alert-danger"><strong></strong>' . cleaner($error) . '</div>';
            }
        }
    }
}
