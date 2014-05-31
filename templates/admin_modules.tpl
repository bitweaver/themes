{* $Header$ *}
{form legend="Global Module Settings"}
	<input type="hidden" name="page" value="{$page}" />
	{foreach from=$formModuleFeatures key=feature item=output}
		<div class="control-group column-group gutters">
			{formlabel label=$output.label for=$feature}
			{forminput}
				{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
				{formhelp note=$output.note page=$output.page}
			{/forminput}
		</div>
	{/foreach}

	<div class="control-group submit">
		<input type="submit" class="ink-button" name="module_settings" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
