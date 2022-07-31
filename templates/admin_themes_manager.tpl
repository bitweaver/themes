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
				<a class="btn btn-primary" href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$smarty.request.site_style}&amp;style_variation={$smarty.request.style_variation}&amp;site_style_layout={$smarty.request.site_style_layout}&amp;approved=1">{booticon iname="fa-circle-check"}{tr}Accept{/tr}</a>
				<a class="btn pull-right" href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php">{booticon iname="fa-circle-xmark" }{tr}Cancel{/tr}</a>
			</div>
		{/if}

		{jstabs}
			{jstab title="Site Theme"}
				{legend legend="Pick Site Theme"}
					{foreach from=$stylesList item=s}
						<legend {if $style eq $s.style}class="highlight"{/if}>
							{if $style eq $s.style}
								{booticon iname="fa-check" iexplain="Current Theme"}&nbsp;
							{/if}
							<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}">{$s.style|replace:"_":" "}</a>
						</legend>

						{if $s.style_info.preview}
							<a class="floatright" href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}">
								<img class="thumb" src="{$s.style_info.preview}" alt="{tr}Theme Preview{/tr}" title="{$s.style}" />
							</a>
						{/if}

						<a class="btn btn-primary" href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}">{tr}Select{/tr} {$s.style|replace:"_":" "}</a>
						{$s.style_info.description}
						{if $s.alternate}
							<h3>{tr}Variations of this style{/tr}</h3>
							<ul>
								{foreach from=$s.alternate key=variation item=d}
									<li><a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_style={$s.style}&amp;style_variation={$variation}">{$variation|replace:"_":" "}</a></li>
								{/foreach}
							</ul>
						{/if}
					{/foreach}
				{/legend}
			{/jstab}

			{jstab title="Icon Theme"}
				{legend legend="Pick Icon Theme"}
					<p class="help">
						Icon themes can be downloaded from <a class="external" href="http://art.gnome.org/themes/icon/">Gnome</a> or <a class="external" href="http://www.kde-look.org/?xcontentmode=27">KDE</a> as long as they adhere to the <a class="external" href="http://standards.freedesktop.org/icon-naming-spec/icon-naming-spec-latest.html">Icon Naming Specifications</a>. For more information, please visit <a class="external" href="http://www.bitweaver.org/wiki/IconThemes">IconThemes</a>.
					</p>
					<p class="help">
						If you are a developer and you want to view a list of available icons, you can do this with the {smartlink ititle="Icon Browser" ifile="icon_browser.php"}.
					</p>

					<ul>
						{foreach from=$iconThemes item=s}
							<li class="item">
								<legend {if $style eq $s.style}class="highlight"{/if}>
									{if $gBitSystem->getConfig(site_icon_style) eq $s.style}
										{booticon iname="fa-check" iexplain="Current Theme"}&nbsp;
									{/if}
									<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_icon_style={$s.style}">{$s.style|replace:"_":" "}</a>
								</legend>

								{if $s.style_info.preview}
									<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php?site_icon_style={$s.style}">
										<img class="thumb" src="{$s.style_info.preview}" alt="{tr}Theme Preview{/tr}" title="{$s.style}" />
									</a>
								{/if}

								{$s.style_info.description}

								<h3>{tr}Sample icons{/tr}</h3>
								{foreach from=$sampleIcons item=icon}
									{biticon ipackage=icons istyle=$s.style iname="fa-large/$icon" iexplain=$icon}
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
