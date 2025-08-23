<?php
/**
 * bitweaver feedback system
 *
 * Copyright (c) 2004 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * created 2025/8/23
 *
 * @author spider <spider@steelsun.com>
 * @package  themes
 */

class BitFeedback {
	/**
	 * Adds a feedback message with a specified level for a given page key.
	 * Levels are based on Bootstrap alerts: success, info, warning, danger.
	 *
	 * @param string $level The alert level (success, info, warning, danger).
	 * @param string $message The feedback message.
	 * @param string $pagekey The key identifying the page or section.
	 */
	public static function add( string $message, string $level, string $pagekey): void {
		if (!session_id()) {
			session_start();
		}

		if (!isset($_SESSION['bit_feedback'])) {
			$_SESSION['bit_feedback'] = [];
		}

		if (!isset($_SESSION['bit_feedback'][$pagekey])) {
			$_SESSION['bit_feedback'][$pagekey][$level] = [];
		}

		// Validate level
		$validLevels = ['success', 'info', 'warning', 'danger'];
		if (!in_array($level, $validLevels)) {
			throw new InvalidArgumentException("Invalid level: $level. Must be one of: " . implode(', ', $validLevels));
		}

		$_SESSION['bit_feedback'][$pagekey][$level][] = $message;
	}

	/**
	 * Retrieves and clears all feedback messages for a given page key.
	 *
	 * @param string $pagekey The key identifying the page or section.
	 * @return array An array of feedback messages, each with 'level' and 'message'.
	 */
	public static function get(string $pagekey): array {
		if (!session_id()) {
			session_start();
		}
		session_start();

		$messages = [];

		if (isset($_SESSION['bit_feedback'][$pagekey])) {
			$messages = $_SESSION['bit_feedback'][$pagekey];
			$_SESSION['bit_feedback'][$pagekey] = array();
			unset($_SESSION['bit_feedback'][$pagekey]);
		}

		return $messages;
	}
}

