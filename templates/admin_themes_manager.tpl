{strip}

<div class="admin themes">
	<div class="header">
		<h1> {tr}Themes Manager{/tr}</h1>
	</div>

	<div class="body">
		{jstabs}
			{jstab title="Site Style"}
				{legend legend="Pick Site Style"}
					<ul class="data">
						{foreach from=$stylesList item=s}
							<li class="{cycle values='odd,even"} item">
								<h2>
									{if $style eq $s.style}
										{biticon ipackage="icons" iname="dialog-ok" iexplain="Current Style"}&nbsp;
									{/if}
									<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}">{$s.style|replace:"_":" "}</a>
								</h2>

								{if $s.style_info.preview}
									<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}">
										<img class="thumb" src="{$s.style_info.preview}" alt="{tr}Theme Preview{/tr}" title="{$s.style}" />
									</a>
								{/if}

								{$s.style_info.description}
								{if $s.alternate}
									<h3>{tr}Variations of this style{/tr}</h3>
									<ul>
										{foreach from=$s.alternate key=variation item=d}
											<li><a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}&amp;style_variation={$variation}">{$variation|replace:"_":" "}</a></li>
										{/foreach}
									</ul>
								{/if}

								<div class="clear"></div>
							</li>
						{/foreach}
					</ul>
				{/legend}
			{/jstab}

			{jstab title="Style Layout"}
				<p class="help">
					{tr}Here you can pick the layout of the site style. this will basically rearrange the positions of the three columns.
					<br />Please note that not all styles support this method of layout selection. Themes that support the style layout selection have a note of it in the description.
					<br />For more information on the layouts and how to tweak them, please visit the <a class="external" href="http://www.bitweaver.org/wiki/StyleLayouts">StyleLayouts</a>{/tr}
				</p>

				{legend legend="Pick Style Layout"}
					<ul id="layoutgala">
						{foreach from=$styleLayouts key=key item=layout}
							<li class="{cycle values="even,odd"}">
								<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style_layout={$key}">
									{if $layout.gif}<img src="{$smarty.const.THEMES_PKG_URL}layouts/{$layout.gif}" alt="{tr}Layout{/tr}: {$key}" title="{tr}Layout{/tr}: {$key}"/><br />{/if}
									{if $gBitSystem->getConfig('site_style_layout') == $key}{biticon ipackage="icons" iname="dialog-ok" iexplain="Current Style Layout"}{/if}
									{$key|replace:"_":" "}
									{if $layout.txt}<br />{include file="`$smarty.const.THEMES_PKG_PATH`layouts/`$layout.txt`"}{/if}
								</a>
							</li>
						{/foreach}
					</ul>
				{/legend}

				<ul class="help">
					<li style="background:#ace; border-bottom:3px solid #fff;">{tr}Header: Found at the top of a website - contains website title and slogan.{/tr}</li>
					<li style="background:#eca; border-bottom:3px solid #fff;">{tr}Content: The main content bearing section of a website.{/tr}</li>
					<li style="background:#aec; border-bottom:3px solid #fff;">{tr}Navigation: Usually found on the left hand side - frequently contains links to important pages.{/tr}</li>
					<li style="background:#cae; border-bottom:3px solid #fff;">{tr}Extra: Sometimes found on the right hand side - frequently contains adidtional information and links.{/tr}</li>
					<li style="background:#cea; border-bottom:3px solid #fff;">{tr}Footer: Usually found at the bottom of a website - contains copyright information and 'powered by' link.{/tr}</li>
					<li style="background:#eee; border-bottom:3px solid #fff;">{tr}px: Indicates that the block is set using a defined pixel width.{/tr}</li>
					<li style="background:#eee; border-bottom:3px solid #fff;">{tr}%: Indicates that the block is set using a percentage, making it fluid in terms of browser window width.{/tr}</li>
				</ul>
			{/jstab}

			{jstab title="Icon Style"}
				{legend legend="Pick Icon Style"}
					<p class="help">
						Icon themes can be downloaded from <a href="http://art.gnome.org/themes/icon/">Gnome</a> or <a href="http://www.kde-look.org/?xcontentmode=27">KDE</a> as long as they adhere to the <a href="http://standards.freedesktop.org/icon-naming-spec/icon-naming-spec-latest.html">Icon Naming Specifications</a>. For more information, please visit <a href="http://www.bitweaver.org/wiki/IconStyles">IconStyles</a>.
					</p>

					<ul class="data">
						{foreach from=$iconStyles item=s}
							<li class="{cycle values='odd,even"} item">
								<h2>
									{if $gBitSystem->getConfig('site_icon_style') eq $s.style}
										{biticon ipackage="icons" iname="dialog-ok" iexplain="Current Style"}&nbsp;
									{/if}
									<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_icon_style={$s.style}">{$s.style|replace:"_":" "}</a>
								</h2>

								{if $s.style_info.preview}
									<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_icon_style={$s.style}">
										<img class="thumb" src="{$s.style_info.preview}" alt="{tr}Theme Preview{/tr}" title="{$s.style}" />
									</a>
								{/if}

								{$s.style_info.description}

								<h3>{tr}Sample icons{/tr}</h2>
								{foreach from=$sampleIcons item=icon}
									{biticon ipackage=icons istyle=$s.style iname="large/$icon" iexplain=$icon}
								{/foreach}

								{if $smarty.const.DEFAULT_ICON_STYLE eq $s.style}
									<br /><span class="highlight">{tr}This icon style is the default. If an icon is not found in the selected icon theme, the icon from here will be used instead{/tr}</span>
								{/if}
								<div class="clear"></div>
							</li>
						{/foreach}
					</ul>
				{/legend}
			{/jstab}

			{jstab title="Miscellaneous"}
				{form legend="Miscellaneous Settings"}
				{*
					<div class="row">
						{formlabel label="Slideshows theme" for="site_slide_style"}
						{forminput}
							{html_options name="site_slide_style" id="site_slide_style" output=$styles values=$styles selected=$gBitSystem->mConfig.site_slide_style}
							{formhelp note="This theme will be used when viewing a wikipage as a slideshow."}
						{/forminput}
					</div>
				*}

					<div class="row">
						{formlabel label="Display action links as" for="site_biticon_display_style"}
						{forminput}
							{html_options name="site_biticon_display_style" id="site_biticon_display_style" options=$biticon_display_options selected=$gBitSystem->mConfig.site_biticon_display_style}
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

			{*jstab title="Delete Theme"}
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
			{/jstab*}
		{/jstabs}
	</div> <!-- end .body -->
</div>  <!-- end .themes -->

{/strip}
