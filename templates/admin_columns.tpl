{form}
	<input type="hidden" name="page" value="{$page}" />

	{legend legend="Available Columns"}
		{foreach from=$activeColumns key=feature item=output}
			<div class="row">
				{formlabel label=`$output.label` for=$feature}
				{forminput}
					{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
					{formhelp hash=$output}
				{/forminput}
			</div>
		{/foreach}
	{/legend}

	{legend legend="Hide Columns in Content Display Modes"}
		<table id="hidecolumnsinmodes">
			<caption>{tr}Hide Columns in Content Display Modes{/tr}</caption>
			<thead>
				<tr>
					<th style="width:60%">{tr}Display Mode{/tr}</th>
					<th style="width:10%">{tr}Top{/tr}</th>
					<th style="width:10%">{tr}Left{/tr}</th>
					<th style="width:10%">{tr}Right{/tr}</th>
					<th style="width:10%">{tr}Bottom{/tr}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$displayModes item=name key=mode}
					<tr class="{cycle values="odd,even"}">
						<td>{$name}</td>
						<td style="text-align:center;"><input type="checkbox" name="mode[{$mode}_hide_top_col]" value="y"    {if $gBitSystem->isFeatureActive("`$mode`_hide_top_col")}checked="checked"{/if} /></td>
						<td style="text-align:center;"><input type="checkbox" name="mode[{$mode}_hide_left_col]" value="y"   {if $gBitSystem->isFeatureActive("`$mode`_hide_left_col")}checked="checked"{/if} /></td>
						<td style="text-align:center;"><input type="checkbox" name="mode[{$mode}_hide_right_col]"  value="y" {if $gBitSystem->isFeatureActive("`$mode`_hide_right_col")}checked="checked"{/if} /></td>
						<td style="text-align:center;"><input type="checkbox" name="mode[{$mode}_hide_bottom_col]" value="y" {if $gBitSystem->isFeatureActive("`$mode`_hide_bottom_col")}checked="checked"{/if} /></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/legend}

	{legend legend="Hide Columns in Packages"}
		<table id="hidecolumnsinpackages">
			<caption>{tr}Hide Columns in Packages{/tr}</caption>
			<thead>
				<tr>
					<th style="width:60%">{tr}Package{/tr}</th>
					<th style="width:10%">{tr}Top{/tr}</th>
					<th style="width:10%">{tr}Left{/tr}</th>
					<th style="width:10%">{tr}Right{/tr}</th>
					<th style="width:10%">{tr}Bottom{/tr}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$hideColumns item=name key=package}
					<tr class="{cycle values="odd,even"}">
						<td>{$name}</td>
						<td style="text-align:center;"><input type="checkbox" name="package[{$package}_hide_top_col]" value="y"    {if $gBitSystem->isFeatureActive("`$package`_hide_top_col")}checked="checked"{/if} /></td>
						<td style="text-align:center;"><input type="checkbox" name="package[{$package}_hide_left_col]" value="y"   {if $gBitSystem->isFeatureActive("`$package`_hide_left_col")}checked="checked"{/if} /></td>
						<td style="text-align:center;"><input type="checkbox" name="package[{$package}_hide_right_col]"  value="y" {if $gBitSystem->isFeatureActive("`$package`_hide_right_col")}checked="checked"{/if} /></td>
						<td style="text-align:center;"><input type="checkbox" name="package[{$package}_hide_bottom_col]" value="y" {if $gBitSystem->isFeatureActive("`$package`_hide_bottom_col")}checked="checked"{/if} /></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/legend}

	<div class="row submit">
		<input type="submit" name="column_control" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
