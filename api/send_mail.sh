#!/bin/sh
mailgun=`curl -s --user 'api:key-0833f71939602fc006e87d6dad8dc845' \
    https://api.mailgun.net/v3/mailgun.dipenpradhan.com/messages \
    -F from='ECS Mailer <mailgun@mailgun.dipenpradhan.com>' \
    -F to=$1 \
    -F subject='ECS Order' \
    -F text="$2"`
    echo $1
echo $mailgun
