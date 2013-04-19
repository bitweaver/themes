{strip}

{form}
{formfeedback hash=$feedback}

	{jstabs}
		{jstab title="Theme Layout"}
			{form}
			<p class="help">
				{tr}Here you can pick the layout of the site style. this will basically rearrange the positions of the three columns.
				<br />Please note that not all styles support this method of layout selection. Themes that support the style layout selection have a note of it in the description.
				<br />For more information on the layouts and how to tweak them, please visit the <a class="external" href="http://www.bitweaver.org/wiki/ThemeLayouts">ThemeLayouts</a>{/tr}
			</p>

			{legend legend="Theme Layout"}
				<div class="control-group">
					{formlabel label="Header Layout"}
					{forminput}
					<select name="layout-header">
						<option value="">{tr}Fixed Width{/tr}</option>
						<option value="-fluid" {if $gBitSystem->getConfig('layout-header')}selected="selected"{/if}>{tr}Fluid Full Width{/tr}</option>
					</select>
					{/forminput}
				</div>
				<div class="control-group">
					{formlabel label="Main Content Section Layout"}
					{forminput}
					<select name="layout-maincontent">
						<option value="">{tr}Fixed Width{/tr}</option>
						<option value="-fluid" {if $gBitSystem->getConfig('layout-maincontent')}selected="selected"{/if}>{tr}Fluid Full Width{/tr}</option>
					</select>
					{/forminput}
				</div>
				<div class="control-group">
					{formlabel label="Footer Layout"}
					{forminput}
					<select name="layout-footer">
						<option value="">{tr}Fixed Width{/tr}</option>
						<option value="-fluid" {if $gBitSystem->getConfig('layout-footer')}selected="selected"{/if}>{tr}Fluid Full Width{/tr}</option>
					</select>
					{/forminput}
				</div>
				<div class="control-group submit">
					{forminput}
						<input type="submit" class="btn" name="save_layout" value="{tr}Save Layout{/tr}"/>
					{/forminput}
				</div>
			{/legend}
			{/form}
		{/jstab}

		{jstab title="Columns"}
			<input type="hidden" name="page" value="{$page}" />
	    	
			{legend legend="Visible Columns and Areas"}
				{formhelp warning="If checked, the column is visible."}
				{foreach from=$activeColumns key=feature item=output}
					<div class="control-group">
						{formlabel label=$output.label for=$feature}
						{forminput}
							{html_checkboxes name="$feature" values="y" checked=$gBitSystem->getConfig($feature) labels=false id=$feature}
							{formhelp hash=$output}
						{/forminput}
					</div>
				{/foreach}
			{/legend}
		{/jstab}
		
		
		{jstab title="Package"}
			
			{legend legend="Column visibility in packages"}
				{formhelp warning="If checked, the column is invisible."}
				<table id="hidecolumnsinpackages" summary="{tr}List of visible columns in packages{/tr}">
					<thead>
						<tr>
							<th>{tr}Package{/tr}</th>
							{foreach from=$hideableAreas item=areaname key=area}
								<th class="width15p">{tr}{$areaname}{/tr}</th>
							{/foreach}
						</tr>
					</thead>
					<tbody>
						{foreach from=$packageColumns item=name key=package}
							<tr class="{cycle values="odd,even"}">
								<td>{$name}</td>
								{foreach from=$hideableAreas item=areaname key=area}
									<td>
										<input type="checkbox" name="hide[{$package}_hide_{$area}_col]" value="y" {if $gBitSystem->isFeatureActive("`$package`_hide_`$area`_col")}checked="checked"{/if} />
									</td>
								{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table>
			{/legend}
		{/jstab}


		{jstab title="Mode"}
			{legend legend="Column visibility based on display mode"}
				{formhelp warning="If checked, the column is invisible."}
				<table id="hidecolumnsinmodes" summary="{tr}List of visible columns based on display mode{/tr}">
					<thead>
						<tr>
							<th>{tr}Mode{/tr}</th>
							{foreach from=$hideableAreas item=areaname key=area}
								<th class="width15p">{tr}{$areaname}{/tr}</th>
							{/foreach}
						</tr>
					</thead>
					<tbody>
						{foreach from=$displayModes item=modename key=mode}
							<tr class="{cycle values="odd,even"}">
								<td><abbr title="{$modename}"><strong>{$mode|capitalize}</strong></abbe></td>
								{foreach from=$hideableAreas item=areaname key=area}
									<td>
										<input type="checkbox" name="hide[{$mode}_hide_{$area}_col]" value="y" {if $gBitSystem->isFeatureActive("`$mode`_hide_`$area`_col")}checked="checked"{/if} />
									</td>
								{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table>
			{/legend}
		{/jstab}

				
		{jstab title="Package/Mode"}
			{legend legend="Column visibility in Packages based on Display Mode"}
				{formhelp warning="If checked, the column is invisible."}
				<table id="hidecolumnsinpackages" summary="{tr}List of visible columns in packages based on display mode{/tr}">
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
									<th><abbr title="{$mode}">{$mode|replace:"display":"Di"|replace:"list":"Li"|replace:"edit":"Ed"|replace:"upload":"Up"|replace:"admin":"Ad"}</abbr></th>
								{/foreach}
							{/foreach}
						</tr>
		
						{foreach from=$packageColumns item=name key=package name=packages}
							<tr class="{cycle values='odd,even'}">
								<td>{$name}</td>
								{foreach from=$hideableAreas item=areaname key=area name=areas}
									{foreach from=$displayModes item=modename key=mode name=modes}
										<td class="{if $smarty.foreach.modes.last && !$smarty.foreach.areas.last}splitstyle{/if}">
											<input type="checkbox" name="hide[{$package}_{$mode}_hide_{$area}_col]" value="y" {if $gBitSystem->isFeatureActive("`$package`_`$mode`_hide_`$area`_col")}checked="checked"{/if} />
										</td>
									{/foreach}
								{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table>
				
				<dl>
					{foreach from=$displayModes item=modename key=mode name=modes}
						<dt><abbr title="{$mode}">{$mode|replace:"display":"Di"|replace:"list":"Li"|replace:"edit":"Ed"|replace:"upload":"Up"|replace:"admin":"Ad"}</abbr></dt>
						<dd><strong>{$mode}</strong></dd>
						<dd>{$modename}</dd>
					{/foreach}
				</dl>
				
			{/legend}
		{/jstab}
		
	{/jstabs}
	
	<div class="control-group submit">
		<input type="submit" class="btn" name="reset_columns" value="{tr}Reset column settings{/tr}" />
		<input type="submit" class="btn" name="column_control" value="{tr}Save settings{/tr}" />
	</div>

{/form}

{/strip}
