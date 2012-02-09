#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='1.0.4'
ARCHIVE=http://launchpad.net/libmemcached/1.0/%s/+download/libmemcached-%s.tar.gz
ARCHIVE=`printf ${ARCHIVE} ${VERSION} ${VERSION}`
download ${ARCHIVE}

NAME='libmemcached-'${VERSION}''

run rm -rf ${NAME}
run rm -rf usr
run tar xzvf ${NAME}.tar.gz

function build_libmemcached(){
    run pushd ${NAME}
    run ./configure \
	--prefix=/usr/local 
    run make install DESTDIR=${ROOT}
    run sudo make install
    run popd
    run ~/.capkg/config/capkg.sh generate -p libmemcached -v ${VERSION}  -i /usr -s usr/local
}

build_libmemcached


