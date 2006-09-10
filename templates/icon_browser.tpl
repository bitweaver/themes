{strip}
<div class="listing themes">
	<div class="header">
		<h1>{tr}Icon Listing{/tr}</h1>
	</div>

	<div class="body">
		<p class="help">
			These are the icons available in a standard set of icons. Icons from the Tango icon style are displayed here since this is the default style and should be used as refernce.
			Both sizes <em>large</em> and <em>small</em> are displayed side by side.
			<br />
			Missing icons haven't been submitted by the tango team yet - they will however be added as they become available.
			<br />
			Please view the <a class="external" href="http://tango.freedesktop.org/Tango_Icon_Gallery">Tango icon gallery</a> for the originally designated uses of the icons.
		</p>
		{foreach from=$iconHash key=set item=iconSet}
			<h2>{$set}</h2>
			<table class="data">
				<tr>
					<th style="width:29%">{tr}Icon name{/tr}</th>
					<th style="width:1%" colspan="2">{tr}Icons{/tr}</th>
					<th style="width:70%">{tr}bitweaver uses{/tr}</th>
				</tr>

				{foreach from=$iconSet key=iname item=iexplain}
					<tr class="{cycle values="odd,even"}">
						<td> {$iname} </td>
						<td>
							{* avoid translation here by not using iexplain *}
							{biticon istyle=tango ipackage=icons iname="small/$iname"}
						</td>
						<td>
							{biticon istyle=tango ipackage=icons iname="large/$iname"}
						</td>
						<td> {$iexplain} </td>
					</tr>
				{/foreach}
			</table>
		{/foreach}
	</div><!-- end .body -->
</div><!-- end .___ -->
{/strip}
