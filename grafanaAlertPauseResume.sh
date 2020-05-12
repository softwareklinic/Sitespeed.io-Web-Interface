#!/bin/bash

#Enable at 6am local
TZ='America/Chicago' date +%H | grep '06' && (


if [ $# -gt 0 ]; then
    echo "Your command line contains $# arguments"
else
    echo "Your command line contains no arguments - Usage: ./grafanaAlertPauseResume-PosParams.sh <id-1> <id-2> <id-3> ... <id-n>"
    exit
fi

echo "You start with $# positional parameters"

# Loop until all parameters are used up
while [ "$1" != "" ]; do
    echo "Pausing alert - $1"
    echo "You now have $# pending alerts"

	curl -XPOST https://GRAFANAENDPOINT/api/alerts/$1/pause -d '{"paused":false}' \
	-H "Content-Type: application/json" \
	-H "Authorization: Bearer eyJrIjoid3hCRVVrOXBYeURnd1cxUXRjem1PUXJPeldvd2hNRmkiLCJuIjoiUGF1c2UgQWxlcnRzIiwiaWQiOjF9"
    # Shift all the parameters down by one
    shift

done

)

#Disable at 9pm local
TZ='America/Chicago' date +%H | grep '23' && (

if [ $# -gt 0 ]; then
    echo "Your command line contains $# arguments"
else
    echo "Your command line contains no arguments - Usage: ./grafanaAlertPauseResume-PosParams.sh <id-1> <id-2> <id-3> ... <id-n>"
    exit
fi

echo "You start with $# positional parameters"

# Loop until all parameters are used up
while [ "$1" != "" ]; do
    echo "Pausing alert - $1"
    echo "You now have $# pending alerts"

	curl -XPOST https://GRAFANAENDPOINT/api/alerts/$1/pause -d '{"paused":true}' \
	-H "Content-Type: application/json" \
	-H "Authorization: Bearer eyJrIjoid3hCRVVrOXBYeURnd1cxUXRjem1PUXJPeldvd2hNRmkiLCJuIjoiUGF1c2UgQWxlcnRzIiwiaWQiOjF9"
    # Shift all the parameters down by one
    shift
done
  
)



