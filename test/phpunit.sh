#!/usr/bin/env bash
cd `dirname $0`
rm -rf report 
rm -f coverage.dat
export COCKATOO_CONF=`pwd`/config.php
export TZ=Asia/Tokyo
#phpunit --process-isolation --coverage-html `pwd`/report  --aggregate `pwd`/coverage.dat `pwd`/testcase

rm testcase/beak/BeakFileTest.php
rm testcase/beak/BeakMongoTest.php
rm testcase/beak/BeakMemcachedTest.php
rm testcase/beak/BeakMysqlTest.php
bash testcase/beak/BeakFileTest.template   > testcase/beak/BeakFileTest.php
bash testcase/beak/BeakMongoTest.template  > testcase/beak/BeakMongoTest.php
bash testcase/beak/BeakMemcachedTest.template  > testcase/beak/BeakMemcachedTest.php
#bash testcase/beak/BeakMysqlTest.template  > testcase/beak/BeakMysqlTest.php
#phpunit  --coverage-html `pwd`/report  --aggregate `pwd`/coverage.dat `pwd`/testcase/beak
phpunit  `pwd`/testcase/beak

# phpunit  --coverage-html `pwd`/report  --aggregate `pwd`/coverage.dat `pwd`/testcase/urlparse
