<?php

trait SeleniumLogin {

    protected function logIn()
    {
        return $this->visit('/')
            ->submitForm('Anmelden')
            ->waitForElement('logout', 3000);
    }
}