#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

#VERSION='2.1.11'
VERSION='3.2.2'
ARCHIVE=http://download.zeromq.org/zeromq-%s.tar.gz
ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
download ${ARCHIVE}

NAME='zeromq-'${VERSION}

run rm -rf ${NAME}
run rm -rf usr
run tar xzvf ${NAME}.tar.gz

# sudo yum install e2fsprogs-devel

function build_zeromq {
    run pushd ${NAME}
    run ./configure 
    run make install DESTDIR=${ROOT}
    run sudo make install
    run popd
    if [ "${WITH_CAPKG}" != "" ]; then
	run eval ~/.capkg/config/capkg.sh generate -p zeromq -v ${VERSION}  -i /usr -s usr/local
    fi
}

build_zeromq
