{form}
	{formfeedback hash=$feedback}
	<input type="hidden" name="page" value="{$page}" />
	{assign var=splitstyle value="border-right:3px solid black;"}

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
					<th style="width:40%">{tr}Display Mode{/tr}</th>
					{foreach from=$hideableAreas item=areaname key=area}
						<th style="width:15%">{tr}{$areaname}{/tr}</th>
					{/foreach}
				</tr>
			</thead>
			<tbody>
				{foreach from=$displayModes item=modename key=mode}
					<tr class="{cycle values="odd,even"}">
						<td>{$modename} <small>({$mode})</small></td>
						{foreach from=$hideableAreas item=areaname key=area}
							<td style="text-align:center;">
								<input type="checkbox" name="hide[{$mode}_hide_{$area}_col]" value="y" {if $gBitSystem->isFeatureActive("`$mode`_hide_`$area`_col")}checked="checked"{/if} />
							</td>
						{/foreach}
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
					<th style="width:40%">{tr}Package{/tr}</th>
					{foreach from=$hideableAreas item=areaname key=area}
						<th style="width:15%">{tr}{$areaname}{/tr}</th>
					{/foreach}
				</tr>
			</thead>
			<tbody>
				{foreach from=$packageColumns item=name key=package}
					<tr class="{cycle values="odd,even"}">
						<td>{$name}</td>
						{foreach from=$hideableAreas item=areaname key=area}
							<td style="text-align:center;">
								<input type="checkbox" name="hide[{$package}_hide_{$area}_col]" value="y" {if $gBitSystem->isFeatureActive("`$package`_hide_`$area`_col")}checked="checked"{/if} />
							</td>
						{/foreach}
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/legend}

	{legend legend="Hide Columns in Packages based on Display Mode"}
		<table id="hidecolumnsinpackages">
			<caption>{tr}Hide Columns in Packages based on Display Mode{/tr}</caption>
			<thead>
				<tr>
					<th>{tr}Package{/tr}</th>
					{foreach from=$hideableAreas item=areaname key=area}
						<th colspan="5">{tr}{$areaname}{/tr}</th>
					{/foreach}
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					{foreach from=$hideableAreas item=areaname key=area name=areas}
						{foreach from=$displayModes item=modename key=mode name=modes}
							<th style="width:4%;{if $smarty.foreach.modes.last && !$smarty.foreach.areas.last}{$splitstyle}{/if}">{$mode}</th>
						{/foreach}
					{/foreach}
				</tr>

				{foreach from=$packageColumns item=name key=package name=packages}
					<tr class="{cycle values="odd,even"}">
						<td>{$name}</td>
						{foreach from=$hideableAreas item=areaname key=area name=areas}
							{foreach from=$displayModes item=modename key=mode name=modes}
								<td style="text-align:center;{if $smarty.foreach.modes.last && !$smarty.foreach.areas.last}{$splitstyle}{/if}">
									<input type="checkbox" name="hide[{$package}_{$mode}_hide_{$area}_col]" value="y" {if $gBitSystem->isFeatureActive("`$package`_`$mode`_hide_`$area`_col")}checked="checked"{/if} />
								</td>
							{/foreach}
						{/foreach}
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/legend}

	<div class="row submit">
		<input type="submit" name="reset_columns" value="{tr}Reset all column settings{/tr}" />
		<input type="submit" name="column_control" value="{tr}Save Settings{/tr}" />
	</div>
{/form}
