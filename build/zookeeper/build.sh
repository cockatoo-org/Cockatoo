#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='3.3.4'
ARCHIVE=http://mirror.metrocast.net/apache//zookeeper/zookeeper-%s/zookeeper-%s.tar.gz
ARCHIVE=`printf ${ARCHIVE} ${VERSION} ${VERSION}`
download ${ARCHIVE}

NAME='zookeeper-'${VERSION}

run rm -rf ${NAME}
run rm -rf usr
run tar xzvf ${NAME}.tar.gz

function zookeeper {
    run rm -rf ${ROOT}/server
    run mkdir -p ${ROOT}/server/${NAME}
    run cp  ${NAME}/zookeeper-${VERSION}.jar  ${ROOT}/server/${NAME}
    run cp -r ${NAME}/bin  ${ROOT}/server/${NAME}/
    run cp -r ${NAME}/lib  ${ROOT}/server/${NAME}/
    run cp -r ${NAME}/conf ${ROOT}/server/${NAME}/
    run ~/.capkg/config/capkg.sh generate -p zookeeper${VERSION}  -i /usr/local -s ${ROOT}/server/${NAME}
}

function build_zookeeper {
    run pushd ${NAME}/src/c
    run ./configure 
    run make install DESTDIR=${ROOT}
    run sudo make install
    run popd
    run ~/.capkg/config/capkg.sh generate -p libzookeeper -v ${VERSION}  -i /usr/local -s usr/
}
zookeeper
build_zookeeper


