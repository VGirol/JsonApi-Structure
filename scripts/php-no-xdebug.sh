#!/bin/bash

php=$(which php)

# get the xdebug config
xdebugConfig=$(php -i | grep xdebug | while read line; do echo $line; exit; done)

# no xdebug? Nothing to do!
if [ "$xdebugConfig" == "" ]; then
    $php "$@"
    exit
fi

# get the configfile (which should be the first value)
# so strip off everything after the first space of the xdebug-config
xdebugConfigFile=$(php -i | grep xdebug | while read line; do echo $line; exit; done)

# test whether we got it right
if [ ! -f "$xdebugConfigFile" ]; then
    echo "No XDebug configfile found!"
    exit 1
fi

# disable xdebug by renaming the relevant .ini file
mv ${xdebugConfigFile}{,.temporarily-disabled}

# dissect the argument to extract the first one (which should be a script or an application in $PATH) from the rest
index=0
for arg in $(echo $@ | tr ' ' "\n")
do
    if [ "$index" == "0" ]; then
        firstArg=$arg
    else
      restArg="$restArg $arg"
    fi

   ((index++))
done

# check whether the command to be executed is a local PHP file or something in the $PATH like composer or php-cs-fixer
fullPath="$(which $firstArg)"
if [ "$fullPath" == "" ]; then
    # check whether it's a local file
    if [ ! -f  $firstArg ]; then
        echo "Could not find $firstArg. No such file or directory"
        exit 1
    else
        # just run the commands
        $php $@
    fi
else
    # run the command with the fullpath followed by the rest of the arguments provided
    $php $fullPath $restArg
fi

# execute the command
$php "$@"

# re-enable xdebug
mv ${xdebugConfigFile}{.temporarily-disabled,}

# test whether the conf file is restored correctly
if [ ! -f "$xdebugConfigFile" ]; then
    echo "Something went wrong with restoring the configfile for xdebug!"
    exit 1
fi
