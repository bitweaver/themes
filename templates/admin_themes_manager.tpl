{strip}

<div class="admin themes">
	<div class="header">
		<h1> {tr}Themes Manager{/tr}</h1>
	</div>

	<div class="body">
		{if $approve}
			<div id="themeapprove">
				<h1>{tr}Confirm Selection{/tr}</h1>
				<p>{tr}The settings you have chosen has not been applied to the site yet. This allows you to test the styles before applying them to your site. To accept the change, please click on the accept button below{/tr}<p>
				<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php">{biticon ipackage=icons iname="large/dialog-cancel" iexplain="Cancel"}</a>
				<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$smarty.request.site_style}&amp;style_variation={$smarty.request.style_variation}&amp;site_style_layout={$smarty.request.site_style_layout}&amp;approved=1">{biticon ipackage=icons iname="large/dialog-ok" iexplain="Accept"}</a>
			</div>
		{/if}

		{jstabs}
			{jstab title="Site Style"}
				{legend legend="Pick Site Style"}
					<ul class="data">
						{foreach from=$stylesList item=s}
							<li class="{cycle values='odd,even"} item">
								<h2 {if $style eq $s.style}class="highlight"{/if}>
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
								<a {if $gBitSystem->getConfig('site_style_layout') == $key}class="highlight" {/if}href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style_layout={$key}">
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
						Icon themes can be downloaded from <a class="external" href="http://art.gnome.org/themes/icon/">Gnome</a> or <a class="external" href="http://www.kde-look.org/?xcontentmode=27">KDE</a> as long as they adhere to the <a class="external" href="http://standards.freedesktop.org/icon-naming-spec/icon-naming-spec-latest.html">Icon Naming Specifications</a>. For more information, please visit <a class="external" href="http://www.bitweaver.org/wiki/IconStyles">IconStyles</a>.
					</p>
					<p class="help">
						If you are a developer and you want to view a list of available icons, you can do this with the {smartlink ititle="Icon Browser" ifile="admin/icon_browser.php"}.
					</p>

					<ul class="data">
						{foreach from=$iconStyles item=s}
							<li class="{cycle values='odd,even"} item">
								<h2 {if $style eq $s.style}class="highlight"{/if}>
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
		{/jstabs}
	</div> <!-- end .body -->
</div>  <!-- end .themes -->

{/strip}
