{strip}
{bitmodule title="$moduleTitle" name="switch_theme"}

	{if $change_theme eq 'y'}
		{form method="get" ipackage=themes ifile="switch_theme.php"}
			<select name="theme" onchange="this.form.submit();">
				{section name=ix loop=$styleslist}
					<option value="{$styleslist[ix]}"{if $styleslist[ix] == $style} selected="selected"{/if}>{$styleslist[ix]}</option>
				{/section}
			</select>
		{/form}
	{/if}
	
	{if $gBitUser->isAdmin}
		{tr}This feature has to be enabled via Admin &gt; User Settings.{/tr}
	{/if}

{/bitmodule}
{/strip}