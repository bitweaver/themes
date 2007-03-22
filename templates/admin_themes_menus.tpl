{strip}
<div class="admin themes">
	<div class="header">
		<h1>{tr}Active Menus{/tr}</h1>
	</div>

	<div class="body">
	
	{jstabs}
		{jstab title="Settings"}
		
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
		
		{jstab title="Effects"}
			{if $gBitSystem->isFeatureActive( 'site_top_bar' ) && $gBitSystem->isFeatureActive( 'site_top_bar_dropdown' )}
				{form legend="Menu Javascript Settings"}
					{foreach from=$formMenuJsSettings key=feature item=output}
						<div class="row">
							{formlabel label=`$output.label` for=$feature}
							{forminput}
								{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
								{formhelp hash=$output}
							{/forminput}
						</div>
					{/foreach}
					<div class="row submit">
						<input type="submit" name="menu_js_settings" value="{tr}Change preferences{/tr}" />
					</div>
				{/form}
			{else}
				<p class="warning">{tr}No menu enabled.{/tr}</p>
			{/if}
		{/jstab}

		{jstab title="Visibility"}
			{if $gBitSystem->isFeatureActive( 'site_top_bar' )}
				{form legend="Top bar menu"}
					<p class="help">
						{tr}Select what menus to display at the <strong>top of the page</strong>, their order and what title they should have. If you don't provide positional information, they will be sorted alphabetically. To create <strong>custom menus</strong>, please use the Nexus package instead.{/tr}
					</p>			
					<table summary="{tr}Select menus to display, their order and titles.{/tr}">
						<thead>
							<tr>
								<th>{tr}Package{/tr}</th>
								<th>{tr}Title{/tr}</th>
								<th>{tr}Position{/tr}</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="3">
									<div class="row submit">
										<input type="submit" name="update_menus" value="{tr}Update Menus{/tr}" />
									</div>
								</td>
							</tr>
						</tfoot>
						<tbody>
							{foreach from=$gBitSystem->mAppMenu key=pkgName item=menu}
								{forminput}
									<tr>
										<td title="{tr}Visible?{/tr}">
											<input type="checkbox" name="menu_{$pkgName}" id="menu_{$pkgName}" {if !$menu.is_disabled}checked="checked"{/if}/>
											&nbsp;
											<label for="menu_{$pkgName}">{tr}{$pkgName}{/tr}
										</td>
										<td>
											<input type="text" name="{$pkgName}_menu_text" value="{$menu.menu_title|escape}"/>
										</td>
										<td>
											<input type="text" name="{$pkgName}_menu_position" size="2" value="{$menu.menu_position|escape}"/>
										</td>
									</tr>
								{/forminput}
							{/foreach}
						</tbody>
					</table>
				{/form}
			{else}
				<p class="warning">{tr}No menu enabled.{/tr}</p>
			{/if}
		{/jstab}
	{/jstabs}


	</div> <!-- end .body -->
</div>  <!-- end .themes -->

{/strip}
