#!/bin/bash

# $1=Username

# Used to check admin group privlige.
groupVar=$(groups $1)

# Parsing.
groups=$(echo "$groupVar" | sed "s/$1 : //g")

# Present groups.
echo $groups
