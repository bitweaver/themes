{* $Header: /cvsroot/bitweaver/_bit_themes/templates/admin_modules.tpl,v 1.1 2007/04/02 18:55:02 squareing Exp $ *}
{form legend="Global Module Settings"}
	<input type="hidden" name="page" value="{$page}" />
	{foreach from=$formModuleFeatures key=feature item=output}
		<div class="row">
			{formlabel label=`$output.label` for=$feature}
			{forminput}
				{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
				{formhelp note=`$output.note` page=`$output.page`}
			{/forminput}
		</div>
	{/foreach}

	<div class="row submit">
		<input type="submit" name="module_settings" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
