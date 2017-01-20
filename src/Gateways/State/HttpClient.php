<?php

namespace Selmonal\Payways\Gateways\State;

interface HttpClient
{
	/**
	 * @param  string $content
	 * @return string
	 */
	public function send($content);
}