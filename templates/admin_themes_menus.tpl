{strip}

<div class="admin themes">
	<div class="header">
		<h1> {tr}Active Menus{/tr}</h1>
	</div>

	<div class="body">

		{jstabs}
			{jstab title="Menu Settigns"}
				{form legend="Menu Settings"}
					{foreach from=$formMenuSettings key=feature item=output}
						<div class="row">
							{formlabel label=`$output.label` for=$feature}
							{forminput}
								{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
								{formhelp hash=$output}
							{/forminput}
						</div>
					{/foreach}

					<div class="row">
						{formlabel label="Menu Title" for="site_menu_title"}
						{forminput}
							<input size="40" type="text" name="site_menu_title" id="site_menu_title" value="{$gBitSystem->getConfig('site_menu_title')|escape}" />
							{formhelp note="Override the default home page link name in the top menu bar."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="menu_settings" value="{tr}Change preferences{/tr}" />
					</div>
				{/form}
			{/jstab}

			{jstab title="Package Menus"}
				{form legend="Select menus that are active in the top bar"}
					<div class="row">
						{formlabel label="Package Menus" for=""}
						{forminput}
							{foreach from=$gBitSystem->mAppMenu key=pkgName item=menu}
								<label>
									<input type="checkbox" name="menu_{$pkgName}" {if !$menu.is_disabled}checked="checked"{/if}/>
									<input type="text" name="{$pkgName}_menu_text" value="{$menu.menu_title|escape}"/>
								</label>
								<br />
							{/foreach}
							{formhelp note="Here you can select what menus to display and what title they should have."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="update_menus" value="{tr}Update Menus{/tr}" />
					</div>
				{/form}
			{/jstab}
		{/jstabs}

	</div> <!-- end .body -->
</div>  <!-- end .themes -->

{/strip}
