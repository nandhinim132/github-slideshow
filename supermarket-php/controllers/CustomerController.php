<?php

class CustomerController
{
    public function index(): void
    {
        require_login();
        // Optional placeholder
        View::render('customers/index');
    }
}