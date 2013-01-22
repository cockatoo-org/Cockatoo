#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='2.0.16'
ARCHIVE=https://github.com/downloads/libevent/libevent/libevent-%s-stable.tar.gz
ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
download ${ARCHIVE}

NAME='libevent-'${VERSION}'-stable'

run rm -rf ${NAME}
run rm -rf usr
run tar xzvf ${NAME}.tar.gz

function build_libevent {
    run pushd ${NAME}
    run ./configure \
	--disable-openssl
    run make install DESTDIR=${ROOT}
    run sudo make install
    run popd
    if [ "${WITH_CAPKG}" != "" ]; then
	run eval ~/.capkg/config/capkg.sh generate -p libevent -v ${VERSION}  -i /usr -s usr/local
    fi
}

build_libevent
