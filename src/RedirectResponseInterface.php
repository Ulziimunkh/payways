<?php

namespace Selmonal\Payways;

interface RedirectResponseInterface
{
    /**
     * @return string
     */
    public function getRedirectMethod();

    /**
     * @return string
     */
    public function getRedirectUrl();

    /**
     * @return array
     */
    public function getRedirectData();

    /**
     * @return void
     */
    public function redirect();
}