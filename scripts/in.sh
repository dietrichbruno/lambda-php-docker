#!/usr/bin/env bash

ASK="Please enter your choice: "
EXIT="exit"
PS3=$ASK

options=(`docker container ls --format "{{.Names}}"`)
select opt in "${options[@]}" $EXIT
do
    if [[ $opt == $EXIT || $REPLY == 'exit' ]]; then
        break;
    fi

    for item in "${options[@]}"; do
        if [[ $item == $opt ]]; then
            echo "Entering $opt..."
            docker exec -it -u www-data $opt bash
            break 2;
        else
            echo "Entering $opt..."
            docker exec -it -u root $opt bash
            break  2
        fi
    done
done
