#!/usr/bin/env bash
cd `dirname $0`
rm -rf report 
rm -f coverage.dat
export COCKATOO_CONF=`pwd`/config.php
export TZ=Asia/Tokyo

rm testcase/beak/BeakFileTest.php
rm testcase/beak/BeakMongoTest.php
rm testcase/beak/BeakMemcachedTest.php
bash testcase/beak/BeakFileTest.template   > testcase/beak/BeakFileTest.php
bash testcase/beak/BeakMongoTest.template  > testcase/beak/BeakMongoTest.php
bash testcase/beak/BeakMemcachedTest.template  > testcase/beak/BeakMemcachedTest.php
#phpunit --process-isolation --coverage-html `pwd`/report `pwd`/testcase/beak 
#phpunit --process-isolation --coverage-html `pwd`/report `pwd`/testcase/urlparse
#phpunit --process-isolation --coverage-html `pwd`/report `pwd`/testcase/www
phpunit --process-isolation --coverage-html `pwd`/report `pwd`/testcase