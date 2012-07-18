#!/usr/bin/env bash
MAIL=`dirname $0`/../mail/mail.py

# first line is subject 
read SUBJECT

${MAIL} --to=hiroaki.kubota@mail.rakuten.com \
	--from=daemon@cockatoo.jp \
	--charset=utf-8 \
	--subject="$SUBJECT" \
	--body=- 