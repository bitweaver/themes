#!/bin/bash
echo "This script will resize statically sized layouts to whatever size you want.  If
you have not modified the script manually, it will resize pixel values to work
with a total width of 960px instead of the original 700px. If this is not what
you want, please edit this file manually to suit your needs."
echo

if [[ ( $1 == '--help' ) || ( $1 == '-h' ) || ( $1 == '?' ) ]]
then
	exit
fi

# Check to see if we've already made a backup
if [ -f layouts_css_backup.tar.gz ]
then
	echo "I have found a css backup file. Please rename or remove the file
layouts_css_backup.tar.gz before executing this script again."
	exit
fi

echo "Creating backup of css files."
tar -czf layouts_css_backup.tar.gz *.css
echo
echo "Created backup in layouts_css_backup.tar.gz"
echo
echo "The script will continue in 5 seconds - hit <ctrl-c> to abort."
echo 5; sleep 1
echo 4; sleep 1
echo 3; sleep 1
echo 2; sleep 1
echo 1; sleep 1
echo
echo "Executing substitutions."

# This sample script will resize all statically set dimensions to work with 960px instead of 800px
# total width
find . -name "*.css" -exec perl -i -wpe 's/900px/960px/g' {} \;
# half the total width
find . -name "*.css" -exec perl -i -wpe 's/450px/480px/g' {} \;

# one side column
find . -name "*.css" -exec perl -i -wpe 's/260px/240px/g' {} \;
# two side columns
find . -name "*.css" -exec perl -i -wpe 's/520px/480px/g' {} \;
# remainder of total width - one side column
find . -name "*.css" -exec perl -i -wpe 's/540px/720px/g' {} \;

# narrow side column (used when there are 2 side columns in a static layout)
find . -name "*.css" -exec perl -i -wpe 's/220px/200px/g' {} \;
# two narrow side columns
find . -name "*.css" -exec perl -i -wpe 's/440px/400px/g' {} \;
# remainder of total width - one narrow side column
find . -name "*.css" -exec perl -i -wpe 's/680px/560px/g' {} \;



## Use the replacements below to generate a very small layout that works with the layout_style.tar.gz
## This sample script will resize all statically set dimensions to work with 960px instead of 800px
## total width
#find . -name "*.css" -exec perl -i -wpe 's/700px/100px/g' {} \;
## half the total width
#find . -name "*.css" -exec perl -i -wpe 's/350px/50px/g' {} \;
#
## one side column
#find . -name "*.css" -exec perl -i -wpe 's/200px/29px/g' {} \;
## two side columns
#find . -name "*.css" -exec perl -i -wpe 's/400px/58px/g' {} \;
## remainder of total width - one side column
#find . -name "*.css" -exec perl -i -wpe 's/500px/71px/g' {} \;
#
## narrow side column (used when there are 2 side columns in a static layout)
#find . -name "*.css" -exec perl -i -wpe 's/150px/22px/g' {} \;
## two narrow side columns
#find . -name "*.css" -exec perl -i -wpe 's/300px/44px/g' {} \;
## remainder of total width - one narrow side column
#find . -name "*.css" -exec perl -i -wpe 's/550px/78px/g' {} \;
