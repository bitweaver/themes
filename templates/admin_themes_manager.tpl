{strip}

<div class="admin themes">
	<div class="header">
		<h1> {tr}Themes Manager{/tr}</h1>
	</div>

	<div class="body">
		{jstabs}
			{jstab title="Site Theme"}
				{form legend="Pick Site Theme"}
					<div class="row">
						{formlabel label="Site theme" for="site_style"}
						{forminput}
							{html_options name="site_style" id="site_style" output=$styles values=$styles selected=$style}
							{formhelp note="This theme will be used as default throughout your site."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Slideshows theme" for="slide_style"}
						{forminput}
							{html_options name="slide_style" id="slide_style" output=$slide_styles values=$slide_styles selected=$gBitSystem->mPrefs.slide_style}
							{formhelp note="This theme will be used when viewing a wikipage as a slideshow."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Display action links as" for="biticon_display"}
						{forminput}
							{html_options name="biticon_display" id="biticon_display" options=$biticon_display_options selected=$gBitSystem->mPrefs.biticon_display}
							{formhelp note="Changing this setting will modify the way all action icons are displayed on your site. Icons in menus are not affected."}
						{/forminput}
					</div>
		{*
					<div class="row">
						{formlabel label="Content Theme Control" for="theme_control"}
						{forminput}
							{html_checkboxes name="feature_theme_control" id="theme_control" output=$slide_styles values="y" checked=`$gBitSystemPrefs.feature_theme_control`}
							{formhelp note="Allows selecting of themes on a per-content basis."}
						{/forminput}
					</div>
		*}
					<div class="row submit">
						<input type="submit" name="themeTabSubmit" value="{tr}Apply Theme Selection{/tr}" />
					</div>
				{/form}
			{/jstab}

			{if $gBitSystemPrefs.package_stylist eq 'y' and $gBitUser->hasPermission( 'bit_p_use_stylist' )}
				{jstab title="Edit Theme"}
					{form legend="Edit Theme" ipackage="stylist" ifile="index.php"}
						<div class="row">
							{formlabel label="Edit theme" for="c_style"}
							{forminput}
								{html_options name="c_style" id="c_style" output=$styles values=$styles}
								{formhelp note="You can edit your theme using the package <a href=\"http://www.bitweaver.org/wiki/index.php?page=StylistPackage\">stylist</a>."}
							{/forminput}
						</div>

						<div class="row submit">
							<input type="submit" name="stylistTabSubmit" value="{tr}Edit{/tr}" />
						</div>
					{/form}
				{/jstab}
			{/if}

			{jstab title="Delete Theme"}
				{form legend="Delete Theme"}
					<div class="row">
						{formlabel label="Delete theme" for="fRemoveTheme"}
						{forminput}
							{html_options name="fRemoveTheme" id="fRemoveTheme" output=$styles values=$styles}
							{formhelp note="This theme will physically be removed from your server and you will not be able to retrieve it."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="deleteTabSubmit" value="{tr}Delete{/tr}" onclick="return confirm('Are you sure you want to delete the theme {$styles[ix]|escape}? you will not be able to retrieve them!');" />
					</div>
				{/form}
			{/jstab}
		{/jstabs}
	</div> <!-- end .body -->
</div>  <!-- end .themes -->

{/strip}
