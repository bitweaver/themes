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

<ul class="data">
{foreach from=$gBitSystem->mAppMenu key=pkgName item=menu}
<li class="item"><input type="checkbox" name="menu_{$pkgName}" {if $gBitSystem->getConfig("menu_`$pkgName`",'y')=='y'}checked="checked"{/if}/>{$menu.title}</li>
{/foreach}
</ul>
			{/forminput}
		</div>

		<div class="row submit">
			<input type="submit" name="update_menus" value="{tr}Update Menus{/tr}" />
		</div>
	{/form}

	</div> <!-- end .body -->
</div>  <!-- end .themes -->

{/strip}
