#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='0.10.5'
ARCHIVE=http://www.clearsilver.net/downloads/clearsilver-%s.tar.gz
ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
download ${ARCHIVE}

NAME=`basename ${ARCHIVE} .tar.gz`
run rm -rf ${NAME}
run rm -rf usr
run run tar xzvf ${NAME}.tar.gz

function build_clearsilver(){
    run pushd ${NAME}
    run ./configure \
	--prefix=/usr/local \
	--with-apache=/usr/local/apache2
    # Test is failing in x86_64, because the test-code is not considered the integer range.
    run make install DESTDIR=${ROOT}
    run mkdir -p ${ROOT}/usr/local/include/ClearSilver/cs
    run cp -f   cs/cs.h  ${ROOT}/usr/local/include/ClearSilver/cs/
    run cp -f   libs/libneo_cs.a ${ROOT}/usr/local/lib/
    # Test is failing in x86_64, because the test-code is not considered the integer range.
    run sudo make install
    run sudo mkdir -p /usr/local/include/ClearSilver/cs
    run sudo cp -f   cs/cs.h  /usr/local/include/ClearSilver/cs/
    run sudo cp -f   libs/libneo_cs.a /usr/local/lib/
    run popd
    run ~/.capkg/config/capkg.sh generate -p clearsilver -v ${VERSION}  -i /usr -s usr/local
}

build_clearsilver

