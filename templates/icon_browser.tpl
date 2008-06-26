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
		<table class="data">
			<tr>
				<th style="width:1%;" colspan="2">{tr}Icons{/tr}</th>
				<th style="width:70%;">{tr}Icon name{/tr}</th>
				<th style="width:29%;">{tr}bitweaver uses{/tr}</th>
			</tr>

			{foreach from=$iconList item=icon}
				<tr class="{cycle values="odd,even"}">
					<td>
						{* avoid translation here by not using iexplain *}
						{biticon istyle=tango ipackage=icons iname="small/$icon"}
					</td>
					<td>
						{biticon istyle=tango ipackage=icons iname="large/$icon"}
					</td>
					<td>
						{$icon}<br />
						<small>{ldelim}biticon ipackage="icons" iname="{$icon}" iexplain="Help"{rdelim}</small>
					</td>
					<td>
						{foreach from=$iconUsage key=ucon item=usage}
							{if $icon == $ucon}{$usage}{/if}
						{/foreach}
					</td>
				</tr>
			{/foreach}
		</table>
	</div><!-- end .body -->
</div><!-- end .___ -->
{/strip}
