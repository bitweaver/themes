{*literal}
<script type="text/javascript">//<![CDATA[
    function initDragDrop() {
        var list = $( "menusorter" );
        DragDrop.makeListContainer( list, "menu_sort" );
//      list.onDragOver = function() { this.style["background"] = "#feb"; };
//      list.onDragOut = function() {this.style["background"] = "none"; };
    };
//]]></script>
{/literal*}
{strip}

<div class="admin themes">
	<div class="header">
		<h1> {tr}Active Menus{/tr}</h1>
	</div>

	<div class="body">
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

		{if $gBitSystem->isFeatureActive( 'site_top_bar' )}
			{form legend="Top bar menu"}
				<p class="help">
					{tr}Here you can select what menus to display, their order and what title they should have. If you don't provide positional information, they will be sorted alphabetically.{/tr}<br />
					{tr}If you want to create custom menus, please use the Nexus package.{/tr}
				</p>

				{foreach from=$gBitSystem->mAppMenu key=pkgName item=menu}
					<div class="row">
						{formlabel label="$pkgName" for=""}
						{forminput}
							<label>{tr}Visisble{/tr}: <input type="checkbox" name="menu_{$pkgName}" {if !$menu.is_disabled}checked="checked"{/if}/></label>
							<br />
							<label>{tr}Title{/tr}: <input type="text" name="{$pkgName}_menu_text" value="{$menu.menu_title|escape}"/></label>
							<br />
							<label>{tr}Position{/tr}: <input type="text" name="{$pkgName}_menu_position" size="2" value="{$menu.menu_position|escape}"/></label>
						{/forminput}
					</div>
				{/foreach}

				<div class="row submit">
					<input type="submit" name="update_menus" value="{tr}Update Menus{/tr}" />
				</div>
			{/form}
		{/if}
	</div> <!-- end .body -->
</div>  <!-- end .themes -->

{/strip}
