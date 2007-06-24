{* $Header: /cvsroot/bitweaver/_bit_themes/templates/admin_themes.tpl,v 1.2 2007/06/24 21:48:32 squareing Exp $ *}
{strip}
{form legend="Theme Settings"}
	<input type="hidden" name="page" value="{$page}" />
	<div class="row">
		{formlabel label="Display action links as" for="site_biticon_display_style"}
		{forminput}
			{html_options name="site_biticon_display_style" id="site_biticon_display_style" options=$biticon_display_options selected=$gBitSystem->getConfig('site_biticon_display_style')}
			{formhelp note="Changing this setting will modify the way all action icons are displayed on your site. Icons in menus are not affected."}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Set default Icon size" for="site_icon_size"}
		{forminput}
			{html_options name="site_icon_size" id="site_icon_size" options=$biticon_sizes selected=$gBitSystem->getConfig('site_icon_size')}
			{formhelp note="Pick large or small icons throughout your website."}
		{/forminput}
	</div>

	{foreach from=$themeSettings key=feature item=output}
		<div class="row">
			{formlabel label=`$output.label` for=$feature}
			{forminput}
				{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
				{formhelp note=`$output.note` page=`$output.page`}
			{/forminput}
		</div>
	{/foreach}

	<div class="row submit">
		<input type="submit" name="change_prefs" value="{tr}Apply Settings{/tr}" />
	</div>
{/form}
{/strip}
