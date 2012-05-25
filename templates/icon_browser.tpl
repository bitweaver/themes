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
				{foreach from=$iconList item=icons key=iconStyle}
				<th class="width1p" colspan="3"><a href="{$smarty.request.SCRIPT_NAME}?icon_style={$iconStyle}">{$iconStyle}</a></th>
				{/foreach}
				<th class="width70p;">{tr}Icon name{/tr}</th>
				<th class="width29p;">{tr}bitweaver uses{/tr}</th>
			</tr>

			{foreach from=$iconNames item=name}
				<tr class="{cycle values="odd,even"}">
					{foreach from=$iconList item=icons key=iconStyle}
					<td {if $gBitSystem->getConfig( 'site_icon_style' ) == $iconStyle}class="prio1"{elseif $iconStyle == $smarty.const.DEFAULT_ICON_STYLE}class="prio2"{/if}>
						{if $iconList.$iconStyle.$name}
							{* avoid translation here by not using iexplain *}
							{biticon istyle=$iconStyle ipackage=icons iname="small/`$iconList.$iconStyle.$name`"}
						{/if}
					</td>
					<td {if $gBitSystem->getConfig( 'site_icon_style' ) == $iconStyle}class="prio1"{elseif $iconStyle == $smarty.const.DEFAULT_ICON_STYLE}class="prio2"{/if}>
						{if $iconList.$iconStyle.$name}
							{* avoid translation here by not using iexplain *}
							{biticon istyle=$iconStyle ipackage=icons iname="large/`$iconList.$iconStyle.$name`"}
						{/if}
					</td>
					<td>
						{* only show huge size if looking at a particular set *}
						{if $smarty.request.icon_style && $iconList.$iconStyle.$name}
							{* avoid translation here by not using iexplain *}
							{biticon istyle=$iconStyle ipackage=icons iname="huge/`$iconList.$iconStyle.$name`"}
						{/if}
					</td>
					{/foreach}
					
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
