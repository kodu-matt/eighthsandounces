<?php

// WooCommerce handles login errors automatically - no custom handling needed

// Manual Registration Error Handling
add_action('woocommerce_register_post', 'custom_registration_validation', 10, 3);

function custom_registration_validation($username, $email, $errors) {
    // Username validation
    if (empty($username)) {
        $errors->add('username_required', __('Username is required.', 'woocommerce'));
    } elseif (username_exists($username)) {
        $errors->add('username_exists', __('Username already exists.', 'woocommerce'));
    }
    
    // Email validation
    if (empty($email)) {
        $errors->add('email_required', __('Email address is required.', 'woocommerce'));
    } elseif (!is_email($email)) {
        $errors->add('email_invalid', __('Please enter a valid email address.', 'woocommerce'));
    } elseif (email_exists($email)) {
        $errors->add('email_exists', __('Email address already exists.', 'woocommerce'));
    }
    
    // Password validation
    if (empty($_POST['password'])) {
        $errors->add('password_required', __('Password is required.', 'woocommerce'));
    } elseif (strlen($_POST['password']) < 8) {
        $errors->add('password_short', __('Password must be at least 8 characters.', 'woocommerce'));
    }
}