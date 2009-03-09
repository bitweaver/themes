{strip}
<div class="listing themes">
	<div class="header">
		<h1>{tr}Icon Listing{/tr}</h1>
	</div>

	<div class="body">
		<p class="help">
			{tr}These are the icons available in a standard set of icons. Icons from the Tango icon style are displayed here since this is the default style and should be used as refernce. Both sizes <em>large</em> and <em>small</em> are displayed side by side.{/tr}<br />
			Please view the <a class="external" href="http://tango.freedesktop.org/Tango_Icon_Gallery">Tango icon gallery</a> for the originally designated uses of the icons.
		</p>
		<table class="data">
			<tr>
				<th class="width1p" colspan="2">{tr}Default Icons{/tr}</th>
				{if $activeIconList}
					<th class="width1p" colspan="2">{tr}Active Icons{/tr}</th>
				{/if}
				<th class="width70p;">{tr}Icon name{/tr}</th>
				<th class="width29p;">{tr}bitweaver uses{/tr}</th>
			</tr>

			{foreach from=$iconNames item=name}
				<tr class="{cycle values="odd,even"}">
					<td>
					{if $defaultIcons.$name}
						{* avoid translation here by not using iexplain *}
						{biticon istyle=tango ipackage=icons iname="small/`$defaultIcons.$name`"}
					{/if}
					</td>
					<td>
					{if $defaultIcons.$name}
						{biticon istyle=tango ipackage=icons iname="large/`$defaultIcons.$name`"}
					{/if}
					</td>
					<td>
					{if $activeIcons.$name}
						{* avoid translation here by not using iexplain *}
						{biticon ipackage=icons iname="small/`$activeIcons.$name`"}
					{/if}
					</td>
					<td>
					{if $activeIcons.$name}
						{biticon ipackage=icons iname="large/`$activeIcons.$name`"}
					{/if}
					</td>
					<td>
						{$name}<br />
						<small>{ldelim}biticon ipackage="icons" iname="{$name}" iexplain="Icon"{rdelim}</small>
					</td>
					<td>
						{if $iconUsage.$name}
							{$iconUsage.$name}
						{/if}
					</td>
				</tr>
			{/foreach}
		</table>
	</div><!-- end .body -->
</div><!-- end .___ -->
{/strip}
