{* $Header$ *}
{strip}
{form legend="Theme Settings"}
	<input type="hidden" name="page" value="{$page}" />
	<div class="form-group">
		{formlabel label="Display action links as" for="site_biticon_display_style"}
		{forminput}
			{html_options name="site_biticon_display_style" id="site_biticon_display_style" options=$biticon_display_options selected=$gBitSystem->getConfig('site_biticon_display_style')}
			{formhelp note="Changing this setting will modify the way all action icons are displayed on your site. Icons in menus are not affected."}
		{/forminput}
	</div>

	<div class="form-group">
		{formlabel label="Default Icon Style" for="default_icon_style"}
		{forminput}
			{html_options name="default_icon_style" id="default_icon_style" options=$iconStyles selected=$gBitSystem->getConfig('default_icon_style')}
			{formhelp note="This is the default icon style set the site will fall back to using if it cant find an icon in the selected icon style set."}
		{/forminput}
	</div>

	<div class="form-group">
		{formlabel label="Default Icon Size" for="site_icon_size"}
		{forminput}
			{html_options name="site_icon_size" id="site_icon_size" options=$biticon_sizes selected=$gBitSystem->getConfig('site_icon_size')}
			{formhelp note="pick the icon size you wish to use on your site. please note that if the icon does not exist in the requested size, it will use the small one instead."}
		{/forminput}
	</div>

	<div class="form-group">
		{formlabel label="Use IE js fix" for="themes_use_msie_js_fix"}
		{forminput}
			{html_options name="themes_use_msie_js_fix" id="themes_use_msie_js_fix" options=$ieFixOptions selected=$gBitSystem->getConfig('themes_use_msie_js_fix')}
			{formhelp note="A Javascript library to make Microsoft's Internet Explorer behave like a standards-compliant browser. It fixes many HTML and CSS issues and makes transparent PNG work correctly under IE7 and older. It is also needed for CSS driven dropdown menus. It does cause some delay on every page load."}
		{/forminput}
	</div>

	{foreach from=$themeSettings key=feature item=output}
		<div class="form-group">
			{formlabel label=$output.label for=$feature}
			{forminput}
				{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
				{formhelp note=$output.note page=$output.page}
			{/forminput}
		</div>
	{/foreach}

	<div class="form-group submit">
		<input type="submit" class="btn btn-default" name="change_prefs" value="{tr}Apply Settings{/tr}" />
	</div>
{/form}
{/strip}
