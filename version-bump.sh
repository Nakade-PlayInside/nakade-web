#!/bin/bash
# bash script for automatic version setting in build.xml
VERSION=$1
BASEDIR=`pwd`
LAYOUT_FILE=layout.phtml
LAYOUT="$BASEDIR"/module/Application/view/layout/"$LAYOUT_FILE"
PROJECT_PROPERTIES=project.properties
VERSION_FILE="$BASEDIR"/"$PROJECT_PROPERTIES"

echo Base directory is "$BASEDIR"

#invalid version
if [ "$#" -eq 0 ]; then
    echo "No valid version supplied. Exit."
    exit 1
fi

echo New version is $1

#is layout file existing
if [ ! -f "$LAYOUT" ]
then
        echo File "$LAYOUT" not found.
        echo Exit.
        exit 1
else    echo File "$LAYOUT" found.
fi

#replacing version in file
sed -i 's/<li class="version">.*</<li class="version">'$VERSION'</' "$LAYOUT" 2>/dev/null
if [ $? -ne 0 ]
then
    echo Could not edit "$LAYOUT"
    exit 1
else echo Set new version in "$LAYOUT_FILE".
fi

#is project.properties existing
if [ ! -f "$VERSION_FILE" ]
then
        echo project.version = $VERSION >> "$VERSION_FILE"
        echo Created new "$PROJECT_PROPERTIES".
else    #replacing version in file
        sed -i 's/project.version = .*$/project.version = '$VERSION'/' "$VERSION_FILE" 2>/dev/null
fi

#error handling
if [ $? -ne 0 ]
then
     echo Could not edit or create "$PROJECT_PROPERTIES"
     exit 1
else echo Set new version in "$PROJECT_PROPERTIES".
fi

#uncomment if manual version setting
#echo Use this command to commit your version bump:
#echo git commit -am '"'[~TASK] Version bumped to $VERSION'"'

