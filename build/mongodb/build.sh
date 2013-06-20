#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`

function build_mongodb {
    VERSION='2.4.4'
    if [ "$ARCH" = "i686" ];then
	ARCHIVE=http://fastdl.mongodb.org/linux/mongodb-linux-i686-%s.tgz
    else
	ARCHIVE=http://fastdl.mongodb.org/linux/mongodb-linux-x86_64-%s.tgz
    fi
    ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
    download ${ARCHIVE}

    NAME=`basename ${ARCHIVE} .tgz`
    run rm -rf usr
    run rm -rf ${NAME}
    run tar xzvf ${NAME}.tgz
    run mkdir -p usr/local/mongo/data
    run mkdir -p usr/local/mongo/conf
    run mkdir -p usr/local/mongo/logs
#    run cp mongoctrl     usr/local/mongo/
#    run cp replkey       usr/local/mongo/conf/
#    run cp mongod.conf   usr/local/mongo/conf/
#    run cp init.js.local usr/local/mongo/conf/init.js
    run mv ${NAME} usr/local/mongo/${NAME}
    run ln -sfT ${NAME}/bin usr/local/mongo/bin
    # run sudo mkdir -p /usr/local/mongo 
    # run sudo cp -r usr/local/mongo /usr/local/
    if [ "${WITH_CAPKG}" != "" ]; then
	run eval ~/.capkg/config/capkg.sh generate -p mongodb${VERSION} -i /usr -s usr/local/
    fi
}
build_mongodb

OBSOLUTE=<<_EOF_


function build_pcre {
    VERSION='8.21'
    ARCHIVE=ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-%s.tar.gz
    ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
    NAME=`basename ${ARCHIVE} .tar.gz`
    rm -rf ${NAME}
    run tar xzvf ${NAME}.tgz

    pushd ${NAME}
    run ./configure --prefix=/usr/local \
	--enable-utf8 \
	--enable-unicode-properties \
	--enable-dependency-tracking \
	--enable-rebuild-chartables 
    run make install DESTDIR=${ROOT}
    run sudo make install
    popd
}
function build_v8 {
    VERSION='3.8.9'
    ARCHIVE=http://v8.googlecode.com/svn/tags/%s/
    ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
    NAME='v8-'${VERSION}
    run svn export ${ARCHIVE} ${NAME}
    rm -rf ${NAME}

    pushd ${NAME}
    run rm -rf cache
    run mkdir -p cache
    run scons --clean
    run scons \
	libv8.a d8 \
	mode=release \
	sample=shell \
	cache=cache \
	verbose=off \
	library=static \
	liveobjectlist=on \
	disassembler=off \
	msvcrt=static \
	objectprint=off \
	msvcltcg=off \
	sourcesignatures=MD5 \
	console=readline \
	prof=off \
	soname=off \
	visibility=hidden \
	gdbjit=off \
	regexp=native \
	arch=x64 \
	toolchain=gcc \
	protectheap=off \
	debuggersupport=off \
	snapshot=off \
	os=linux \
	vmstate=on \
	inspector=on \
	profilingsupport=on 
        # env=KEY:VALUE,...
        # importenv=NAME,...
        # objectprint=on \
	run mkdir -p ${ROOT}/usr/local/lib
	run cp libv8.a ${ROOT}/usr/local/lib/
	run mkdir -p ${ROOT}/usr/local/bin
	run cp d8 ${ROOT}/usr/local/bin/d8
	run mkdir -p ${ROOT}/usr/local/include/
	run sudo cp include/*.h ${ROOT}/usr/local/include/
	run sudo cp libv8.a /usr/local/lib/
	run sudo cp d8 /usr/local/bin/d8
	run sudo cp include/*.h /usr/local/include/
    popd
}


function build_boost {
    NAME='boost_1_45_0'

    rm -rf ${NAME}
    rm -rf usr
    run tar xzvf ${NAME}.tar.gz

    pushd ${NAME}
    run ./bootstrap.sh
    run ./bjam -toolset=gcc \
	link=static,shared \
	address-model=64 \
	--prefix=/usr/local \
	--layout=tagged \
	--without-mpi \
	--without-graph \
	--without-graph_parallel \
	--without-math \
	--without-python \
	--without-wave \
	--without-test \
	release stage
    run mkdir -p ${ROOT}/usr
    run cp -r stage ${ROOT}/usr/local
    run sudo ./bjam -toolset=gcc \
	link=static,shared \
	address-model=64 \
	--prefix=/usr/local \
	--layout=tagged \
	--without-mpi \
	--without-graph \
	--without-graph_parallel \
	--without-math \
	--without-python \
	--without-wave \
	--without-test \
	release install
    popd
}

function build_mongodb {
    VERSION='2.0.2'
    ARCHIVE=http://downloads.mongodb.org/src/mongodb-src-r%s.tar.gz
    ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
    NAME=`basename ${ARCHIVE} .tgz`
    rm -rf ${NAME}
    run tar xzvf ${NAME}.tgz

#    sudo yum install libpcap-devel
    pushd ${NAME}
    # ln -sf /usr/lib64/libpcap.so.0 /usr/lib64/libpcap.so
    # scons --prefix=/usr/local/mongodb --extrapath=/usr/local/pcre,/usr/local/js,/usr/local/boost,/usr/local/readline install    
    scons \
	--64 \
	--release \
	--prefix=/usr/local/monogdb \
	--libpath=/usr/local/lib \
	--usev8 \
	--extrapath=/usr/local \
	--staticlibpath=/usr/local/lib,/usr/lib64 \
	--staticlib=v8,readline,ncurses \
	all
#	--staticlib=v8,readline,ncurses,pcre,pcrecpp \
# --mm \
# --asio \ <= ソース的に終わってるノーメンテ状態？
    popd
}

# build_pcre
# build_v8
# build_mongodb
# build_boost

#---------------------
# V8
# Edit SConstruct
# D8_FLAGS = {
#  'gcc': {
#    'console:readline': {
#      'LIBS': ['readline']
# D8_FLAGS = {
#  'gcc': {
#    'console:readline': {
#      'LIBS': ['readline','ncurses']

_EOF_
