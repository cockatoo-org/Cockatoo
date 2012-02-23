#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='0.6.8'
ARCHIVE=http://nodejs.org/dist/v%s/node-v%s.tar.gz
ARCHIVE=`printf ${ARCHIVE} ${VERSION} ${VERSION}`
download ${ARCHIVE}

NAME=`basename ${ARCHIVE} .tar.gz`

run rm -rf usr
run rm -rf node_modules
run rm -rf ${NAME}
run tar xzvf ${NAME}.tar.gz

function build_nodejs {
    run pushd ${NAME}
    run ./configure \
	--prefix=/usr/local/nodejs-${VERSION}
    run make install DESTDIR=${ROOT}
    run ln -sfT nodejs-${VERSION} ${ROOT}/usr/local/nodejs
    run sudo make install
    run sudo ln -sfT nodejs-${VERSION} /usr/local/nodejs
    run popd
    run ~/.capkg/config/capkg.sh generate -p nodejs${VERSION} -i /usr -s usr/local
}

function build_npm_lib {
    export PATH=/usr/local/nodejs/bin:$PATH
    EXT=$1
    # 
    run rm -rf root_${EXT}
    run mkdir root_${EXT}
    #
    run pushd root_${EXT}
    run npm install $EXT
    run popd
    run eval ~/.capkg/config/capkg.sh generate -p ${EXT}_npm${VERSION} -i /usr/local/nodejs-${VERSION}/lib/ -s root_${EXT}/node_modules "--require='nodejs${VERSION} 0.0.1 0.0.999'"
}
build_nodejs
build_npm_lib jsdom
build_npm_lib getopt

