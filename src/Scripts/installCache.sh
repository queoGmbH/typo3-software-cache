#!/usr/bin/env bash

PATH_TYPO3_INDEX=typo3_src/index.php
PATH_CACHE_FILE=$1

if [ -f $PATH_TYPO3_INDEX ]
    then
		rm -f typo3.php
        ln -s $PATH_TYPO3_INDEX typo3.php
        rm -f index.php
        ln -s $PATH_CACHE_FILE index.php
fi