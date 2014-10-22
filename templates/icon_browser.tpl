{strip}
<div class="listing themes">
	<div class="header">
		<h1>{tr}Icon Listing{/tr}</h1>
	</div>

	<div class="body">
		<p class="help">
			{tr}Very much work in progress, but getting there ... need to update the ross reference with current icon names rather than the desktop standard Tango ones!{/tr}<br />
			Source of Fontawesome <a class="external" href="http://fortawesome.github.io/Font-Awesome/">on GitHub</a> for the monochrome icons.<br />
			The font-awesome-to-png python script is used to create an image set for easier handling with the array display.<br />
			<a class="external" href="http://www.famfamfam.com/lab/icons/silk/">Original source of the silk icon set</a> for full colour icons.<br />
			This is enhanced by the <a class="external" href="http://www.fatcow.com/free-icons">FatCow extended icon set</a> which also provides 32x32 versions of the silk library<br />
			See the <a href="/wiki/colourstrap">Colourstrap information page</a> for more data on replacing Bootstraps monochrome icons with traditional colour ones.
		</p>
		<table class="table data">
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
