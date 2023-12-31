<?php

namespace VK\Exceptions\Api;

use VK\Client\VKApiError;
use VK\Exceptions\VKApiException;

class VKApiMessagesTooManyPostsException extends VKApiException
{
	/**
	 * VKApiMessagesTooManyPostsException constructor.
	 * @param VkApiError $error
	 */
	public function __construct(VKApiError $error)
	{
		parent::__construct(940, 'Too many posts in messages', $error);
	}
}

