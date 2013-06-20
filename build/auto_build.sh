#!/usr/bin/env bash
source /usr/local/rvm/environments/ruby-1.8.7-p371
export CAPKG_NS=cockatoo
export WITH_CAPKG=1
~/.capkg/config/capkg.sh createrep
~/.capkg/config/capkg.sh setup

pushd httpd
./build.sh
popd
pushd php
./build.sh
popd
pushd zeromq
./build.sh
popd
pushd clearsilver
./build.sh
popd
pushd zookeeper
./build.sh
popd
pushd libmemcached
./build.sh
popd
pushd php-ext
./build.sh all
popd
pushd libevent
./build.sh
popd
memcached
pushd memcached
./build.sh
popd
mongodb
pushd mongodb
./build.sh
popd
nodejs
pushd node
./build.sh
popd

# create package
for f in `find . -name '*.capkcf'`; 
  do pushd `dirname $f`;
  ~/.capkg/config/capkg.sh create -c `basename $f`;
  popd;
done

# upload package
for f in `find . -name '*.capkcf'`; 
  do pushd `dirname $f`;
  ~/.capkg/config/capkg.sh upload -c `basename $f`;
  popd;
done
