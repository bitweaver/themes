{strip}

<div class="admin themes">
	<div class="header">
		<h1> {tr}Themes Manager{/tr}</h1>
	</div>

	<div class="body">
		{jstabs}
			{jstab title="Site Theme"}
				{legend legend="Pick Site Theme"}
					<ul class="data">
						{foreach from=$stylesList item=s}
							<li class="{cycle values='odd,even"} item">
								<h2>
									{if $style eq $s.style}
										{biticon ipackage=liberty iname=success iexplain="Current Theme"}&nbsp;
									{/if}
									<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}">{$s.style}</a>
								</h2>

								{if $s.style_info.preview}
									<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}">
										<img class="thumb" src="{$s.style_info.preview}" alt="{tr}Theme Preview{/tr}" title="{$s.style}" />
									</a>
								{/if}

								{if $s.style_info.description}
									{$s.style_info.description}
									{if $s.alternate}
										<h3>{tr}Variations of this theme{/tr}</h3>
										<ul>
											{foreach from=$s.alternate key=variation item=d}
												<li><a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}&amp;style_variation={$variation}">{$variation|replace:"_":" "}</a></li>
											{/foreach}
										</ul>
									{/if}
								{/if}

								<div class="clear"></div>
							</li>
						{/foreach}
					</ul>
				{/legend}
			{/jstab}

			{jstab title="Miscellaneous"}
				{form legend="Miscellaneous Settings"}
					<div class="row">
						{formlabel label="Slideshows theme" for="site_slide_style"}
						{forminput}
							{html_options name="site_slide_style" id="site_slide_style" output=$styles values=$styles selected=$gBitSystem->mPrefs.site_slide_style}
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

					<div class="row">
						{formlabel label="Disable Javascript Tabs" for="site_disable_jstabs"}
						{forminput}
							<input type="checkbox" name="site_disable_jstabs" value='y' id="site_disable_jstabs"{if $gBitSystem->isFeatureActive( 'site_disable_jstabs' )} checked="checked"{/if} />
							{formhelp note="If you have difficulties with the javascript tabs, of you don't like them, you can disable them here."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Disable Fading" for="site_disable_fat"}
						{forminput}
							<input type="checkbox" name="site_disable_fat" value='y' id="site_disable_fat"{if $gBitSystem->isFeatureActive( 'site_disable_fat' )} checked="checked"{/if} />
							{formhelp note="Disable the fading effect used when displaying any success, warning or error messages."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="themeTabSubmit" value="{tr}Apply Settings{/tr}" />
					</div>
				{/form}
			{/jstab}

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
