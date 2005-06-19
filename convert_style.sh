echo "this file is broken, it is just a dump of spider's find history"
exit;

# BELOW HERE IS A BIG PILE OF CRAP that was dumped from my shell history. these finds may or may NOT work
# !!! USE AT YOUR OWN PERIL !!!
# This is also not complete.
find . -name "*tpl" -exec perl -i -wpe "s/file=comments.tpl/file=\"bitpackage:kernel\/comments.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=comments.tpl/include file=\"bitpackage:kernel\/comments.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=categorize.tpl/include file=\"bitpackage:categories\/categorize.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=tiki-edit_help_tool.tpl/include file=\"bitpackage:kernel\/edit_help_tool.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=tiki-user/include file=bitpackage:users\/user/g" {} \;
find . -name "wiki/templates/editpage.tpl" -exec perl -i -wpe "s/include file=textareasize.tpl/include file=\"bitpackage:kernel\/textarea_size.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=\"tiki-smileys.tpl\"/include file=\"bitpackage:kernel\/smileys.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=\"textareasize.tpl\"/include file=\"bitpackage:kernel\/textarea_size.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=tiki-mybitweaver_bar.tpl/include file=\"bitpackage:users\/my_tiki_bar.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe 's/messu-nav.tpl/bitpackage:messu\/messu_nav.tpl/g' {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=tiki-top_bar.tpl/include file=\"bitpackage:kernel\/top_bar.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=[\"']tiki-top_bar.tpl[\"']/include file=\"bitpackage:kernel\/top_bar.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=[\"']tiki-page_bar.tpl[\"']/include file=\"bitpackage:wiki\/page_bar.tpl\"/g" {} \;
find . -name "*tpl" -exec perl -i -wpe "s/include file=[\"']tiki-preview.tpl[\"']/include file=\"bitpackage:wiki\/preview.tpl\"/g" {} \;
