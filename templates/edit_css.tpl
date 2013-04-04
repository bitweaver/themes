{literal}
<script type="text/javascript">
<!--//
function confirmform(text)
{
var agree=confirm(text);

if (agree)
return true;
else
return false;
}
// -->
</script>
{/literal}

<a class="pagetitle" href="{$smarty.const.THEMES_PKG_URL}edit_css.php">{tr}Edit Custom Theme{/tr}</a><br /><br />
{if $successMsg}
<div style="color: green">{$successMsg}</div>
{/if}
{if $errorMsg}
<div style="color: red">{$errorMsg}</div>
{/if}
<div>
	<form method="post" action="{$smarty.const.THEMES_PKG_URL}edit_css.php">
	<div style="padding:4px;border-bottom:1px solid #c3b3a3;">
		<textarea name="textData" rows="42" cols="50" wrap="virtual" style="padding:7px;padding-right:0;">{$data|escape}</textarea>
	</div>
	<div style="">
		<span>  
			<input type="submit" class="btn" name="fSaveCSS" value="Save"> 
			<input type="submit" class="btn" name="fCancelCSS" value="Cancel">
		</span>
		<span style="float: right">
			
				<input type="submit" class="btn" name="fResetCSS" value="Reset CSS" onclick="return confirmform('Are you sure you want to reset your CSS back to the defaults? Any changes you have made will be lost.');">
				to the
				<select name="resetStyle">
				{section name=ix loop=$styles}
					<option value="{$styles[ix]|escape}" {if $assignStyle eq $styles[ix]}selected="selected"{/if}>{$styles[ix]}</option>
				{/section}
				</select>
				theme
			
		</span>
	</div>
	</form>	
</div>
<!-- Images Used by custom theme -->
<div>
<br /><br />
<h3>Images Used By Your Custom Theme</h3>
<br />

<table cellpadding="3">
	<tr>
		<th>Image</th>
		<th>Action</th>
	</tr> 
	{section name=ix loop=$themeImages}
	<tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
		<td width="200px" cellpadding="3">{$themeImages[ix]}</td>
		<td cellpadding="3">
		{booticon iname="icon-folder-open"  ipackage="icons"  iexplain=preview onclick="javascript"}
			{booticon iname="icon-folder-open"  ipackage="icons"  iexplain=preview onclick="javascript:popup('$customCSSImageURL/$themeImages[ix]')"}
			<a href="{$smarty.const.THEMES_PKG_URL}/edit_css.php?fDeleteImg={$themeImages[ix]}">
			{booticon iname="icon-trash" ipackage="icons" iexplain=remove onclick="return confirm('Are you sure you want to delete $themeImages[ix]?');"}
			<img class="icon" src="{$smarty.const.LIBERTY_PKG_URL}icons/delete.gif" title="{tr}Remove{/tr}" alt="{tr}Remove{/tr}" onclick="return confirm('Are you sure you want to delete {$themeImages[ix]}?');"/>
			</a>
			</input>
		</td>
	</tr>
	{/section}
</table>
<br />
<form enctype="multipart/form-data" method="post" action="{$smarty.const.THEMES_PKG_URL}edit_css.php"
<input type="hidden" name="MAX_FILE_SIZE" value="1024000">
Upload Image: <input type="file" name="fImgUpload"> <br /> <br/>
<input type="submit" class="btn" value="Upload Image" name="fUpload">
</form>
</div>

