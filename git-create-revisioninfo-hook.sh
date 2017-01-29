#!/bin/bash

## Automatically generate a file with git branch and revision info
##
## Example:
##   [master] v2.0.0 (a830382)
## Install:
##   cp git-create-revisioninfo-hook.sh .git/hooks/post-commit
##   cp git-create-revisioninfo-hook.sh .git/hooks/post-checkout
##   cp git-create-revisioninfo-hook.sh .git/hooks/post-merge
##   chmod +x .git/hooks/post-*

FILENAME='public/gitrevision.txt'

exec 1>&2
branch=`git rev-parse --abbrev-ref HEAD`
shorthash=`git log --pretty=format:'%h' -n 1`
latesttag=`git describe --tags --abbrev=0`

VERSION="[$branch] $latesttag ($shorthash)"
echo $VERSION > $FILENAME
