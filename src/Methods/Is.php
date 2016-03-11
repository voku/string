<?php namespace Gears\String\Methods;
////////////////////////////////////////////////////////////////////////////////
// __________ __             ________                   __________
// \______   \  |__ ______  /  _____/  ____ _____ ______\______   \ _______  ___
//  |     ___/  |  \\____ \/   \  ____/ __ \\__  \\_  __ \    |  _//  _ \  \/  /
//  |    |   |   Y  \  |_> >    \_\  \  ___/ / __ \|  | \/    |   (  <_> >    <
//  |____|   |___|  /   __/ \______  /\___  >____  /__|  |______  /\____/__/\_ \
//                \/|__|           \/     \/     \/             \/            \/
// -----------------------------------------------------------------------------
//          Designed and Developed by Brad Jones <brad @="bjc.id.au" />
// -----------------------------------------------------------------------------
////////////////////////////////////////////////////////////////////////////////

use voku\helper\UTF8;

trait Is
{
    /**
	 * Is the entire string lower case?
	 *
	 * @return bool Whether or not $str contains only lower case characters.
	 */
	public function isLowerCase()
	{
		return $this->matchesPattern('^[[:lower:]]*$');
	}

    /**
	 * Is the entire string upper case?
	 *
	 * @return bool Whether or not $str contains only upper case characters.
	 */
	public function isUpperCase()
	{
		return $this->matchesPattern('^[[:upper:]]*$');
	}

    /**
	 * Returns true if the string contains only alphabetic chars, false
	 * otherwise.
	 *
	 * @return bool Whether or not $str contains only alphabetic chars
	 */
	public function isAlpha()
	{
		return $this->matchesPattern('^[[:alpha:]]*$');
	}

    /**
	 * Returns true if the string contains only alphabetic and numeric chars,
	 * false otherwise.
	 *
	 * @return bool Whether or not $str contains only alphanumeric chars
	 */
	public function isAlphanumeric()
	{
		return $this->matchesPattern('^[[:alnum:]]*$');
	}

	/**
	 * Returns true if the string contains only whitespace chars, false
	 * otherwise.
	 *
	 * @return bool Whether or not $str contains only whitespace characters
	 */
	public function isBlank()
	{
		return $this->matchesPattern('^[[:space:]]*$');
	}

	/**
	 * Returns true if the string contains only hexadecimal chars, false
	 * otherwise.
	 *
	 * @return bool Whether or not $str contains only hexadecimal chars
	 */
	public function isHexadecimal()
	{
		return $this->matchesPattern('^[[:xdigit:]]*$');
	}

	/**
	 * Returns true if the string is JSON, false otherwise. Unlike json_decode
	 * in PHP 5.x, this method is consistent with PHP 7 and other JSON parsers,
	 * in that an empty string is not considered valid JSON.
	 *
	 * @return bool Whether or not $str is JSON
	 */
	public function isJson()
	{
		if ($this->getLength() === 0) return false;

		json_decode($this->scalarString);

		return (json_last_error() === JSON_ERROR_NONE);
	}

	/**
	 * Returns true if the string is serialized, false otherwise.
	 *
	 * @return bool Whether or not $str is serialized
	 */
	public function isSerialized()
	{
		if ($this->getLength() === 0) return false;

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		return
		(
			$this->scalarString === 'b:0;' ||
			@unserialize($this->scalarString) !== false
		);
	}

	/**
	 * Returns true if the string is base64 encoded, false otherwise.
	 *
	 * @return bool
	 */
	public function isBase64()
	{
		// An empty string is by definition not encoded.
		if ($this->getLength() === 0) return false;

		// Grab the current string value.
		$possiblyEncoded = $this->scalarString;

		// Attempt to decode it.
		$decoded = base64_decode($possiblyEncoded, true);

		// If we get false it can't be base64
		if ($decoded === false) return false;

		// Lets double check
		return (base64_encode($decoded) === $this->scalarString);
	}
}
