{strip}

<div class="admin themes">
	<div class="header">
		<h1> {tr}Active Menus{/tr}</h1>
	</div>

	<div class="body">

	{form legend="Select menus that are active in the top bar."}
		<div class="row">
			{formlabel label="Package Menus" for=""}
			{forminput}
				{foreach from=$gBitSystem->mAppMenu key=pkgName item=menu}
					<label><input type="checkbox" name="menu_{$pkgName}" {if $gBitSystem->getConfig("menu_`$pkgName`",'y')=='y'}checked="checked"{/if}/> {$menu.title|escape}</label><br />
				{/foreach}
				<br /><br />
				{foreach from=$gBitSystem->mAppMenuDisabled key=pkgName item=menu}
					<label><input type="checkbox" name="menu_{$pkgName}" {if $gBitSystem->getConfig("menu_`$pkgName`",'y')=='y'}checked="checked"{/if}/> {$menu.title|escape}</label><br />
				{/foreach}
			{/forminput}
		</div>

		<div class="row submit">
			<input type="submit" name="update_menus" value="{tr}Update Menus{/tr}" />
		</div>
	{/form}

	</div> <!-- end .body -->
</div>  <!-- end .themes -->

{/strip}
