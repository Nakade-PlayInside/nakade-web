#!/bin/bash

VERSION=$1
BASEDIR=`dirname $0`

if [ "$#" -eq 0 ]; then
    echo "No valid version supplied. Exit."
    exit 1
fi

#replacing version in file
sed -i 's/<li class="version">.*</<li class="version">'$VERSION'</' $BASEDIR/module/Application/view/layout/layout.phtml

sed -i 's/project.version = .*$/project.version = '$VERSION'/' $BASEDIR/version.properties 2>/dev/null
if [ $? != 0 ]; then
    echo project.version = $VERSION >> $BASEDIR/project.properties
fi

echo Use this command to commit your version bump:
echo $ git commit -am '"'[~TASK] Version bumped to $VERSION'"'
