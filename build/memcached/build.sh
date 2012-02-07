#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='1.4.10'
NAME='memcached-'${VERSION}
ARCHIVE=http://memcached.googlecode.com/files/memcached-%s.tar.gz
ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
download ${ARCHIVE}

NAME=`basename ${ARCHIVE} .tar.gz`

run rm -rf ${NAME}
run rm -rf usr
run tar xzvf ${NAME}.tar.gz

function build_memcached {
    run pushd ${NAME}
    if [ $ARCH = "i686" ]; then
	run ./configure \
	    --prefix=/usr/local/${NAME} \
	    --with-libevent=/usr/local 
    else
	run ./configure \
	    --prefix=/usr/local/${NAME} \
	    --with-libevent=/usr/local \
	    --enable-64bit 
    fi
    run make install DESTDIR=${ROOT}
    run ln -sfT ${NAME} ${ROOT}/usr/local/memcached
#    run mkdir -p ${ROOT}/usr/local/${NAME}/logs
#    run mkdir -p ${ROOT}/usr/local/${NAME}/conf
#    run cp memcachedctrl  ${ROOT}/usr/local/${NAME}/
#    run cp memcached.conf ${ROOT}/usr/local/${NAME}/conf/
    run sudo make install
    run sudo ln -sfT ${NAME} /usr/local/memcached
#    run sudo mkdir -p /usr/local/${NAME}/logs
#    run sudo mkdir -p /usr/local/${NAME}/conf
#    run sudo cp memcachedctrl  /usr/local/${NAME}/
#    run sudo cp memcached.conf /usr/local/${NAME}/conf/
    run popd
    run eval ~/.capkg/config/capkg.sh generate -p memcached -v ${VERSION}  -i /usr/local -s usr/ "--require='libevent 2.0.0 2.0.999'"
}

build_memcached


# d run as daemon
# P pidfile
# p 11211 port
# m 1GB memory 
# c 32768 connections
# u nobody2 user
# f 1.25 chunk size
# t 10 threads
# b 1024 backlog queue need to do  $ echo 1024 > /proc/sys/net/core/somaxconn
# I 4mb max size 
# memcached -d -P /var/memcached.pid -p 11211 -m 1000 -c 32768 -u nobody2 -f 1.25 -t 10 -b 1024 -I 4m
