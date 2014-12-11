<?php
/**
 * @package Plugins
 * @author Bimal Poudel
 * @copyright since 2006, Bimal Poudel
 * @support https://www.odesk.com/o/profiles/users/_~~657b70cc7f2c616a/
 * @contact http://www.sanjaal.com
 * @company Sanjaal Corps
 */

/*
* Makes a word plural, with easy guessing only.
* http://www.factmonster.com/ipka/A0886509.html
* @url http://www.smarty.net/forums/viewtopic.php?t=18399
* @example {'page'|plural:2}
*/
function smarty_modifier_plural($word = '', $counter = 0)
{
	# What to append to the word to make it plural?
	$plural_marker = 's';

	# Words ending in [ o ] 
	if(preg_match('/o$/', $word))
	{
		$plural_marker = 'es';
	}

	# Words ending in [ y ]
	# frequency => frequencies, copy => copies, company => companies
	# cookie => cookies
	if(preg_match('/y$/', $word))
	{
		$plural_marker = 'ies';

		# Remove the last letter: [ y ]
		$word = substr($word, 0, strlen($word) - 1);
	}

	# Words having [ oo ] in the second last letters.
	# foot => feet, goose => geese
	if(preg_match('/oo([a-z])?$/', $word, $data))
	{
		$plural_marker = 'ee' . $data[1];
		$word = substr($word, 0, strlen($word) - 3);
	}

	$plural = $word . (($counter != 1) ? $plural_marker : '');

	return $plural;
}

?>
