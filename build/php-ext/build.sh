#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

while [ -n "$1" ]; do
    if [ "$1" = "all" ];then
	OPT_APC=1
	OPT_CLEARSILVER=1
	OPT_ZMQ=1
	OPT_ZOOKEEPER=1
	OPT_MONGO=1
	OPT_UUID=1
	OPT_MEMCACHED=1
	break
    fi
    eval OPT_`echo $1 | tr '[a-z]' '[A-Z]'`=1
    shift
done;


echo apc         $OPT_APC
echo clearsilver $OPT_CLEARSILVER
echo zmq         $OPT_ZMQ
echo zookeeper   $OPT_ZOOKEEPER
echo mongo       $OPT_MONGO
echo uuid        $OPT_UUID
echo memcached   $OPT_MEMCACHED


PHP_VERSION='5.3.8'
PHP_NAME='php-'${PHP_VERSION}

function build_php_ext3 {
    export PATH=/usr/local/${PHP_NAME}/bin:$PATH
    EXT=$1
    OPTIONS=$2
    CAPKCF_OPTIONS=$3
    # MK dst
    run rm -rf root_${EXT}
    run mkdir root_${EXT}
    #
    run pushd ${EXT}
    run /usr/local/${PHP_NAME}/bin/phpize --clean
    run /usr/local/${PHP_NAME}/bin/phpize
    run ./configure \
	--with-php-config=/usr/local/${PHP_NAME}/bin/php-config \
	${OPTIONS}
    run make
    run make install INSTALL_ROOT=${ROOT}/root_${EXT}
    CONFD=${ROOT}/root_${EXT}/usr/local/${PHP_NAME}/lib/conf.d/
    run mkdir -p ${CONFD}
    if [ -f ${ROOT}/${EXT}.ini ]; then
	run cp -f ${ROOT}/${EXT}.ini ${CONFD}/${EXT}.ini
    fi
    run_edit ${CONFD}/${EXT}.ini "extension=${EXT}.so" 1
    # install
    run sudo cp -rfT ${ROOT}/root_${EXT}/usr/local /usr/local
#    run sudo make install
#    run sudo cp ${CONFD}/${EXT}.ini /usr/local/${PHP_NAME}/lib/conf.d/
    run popd
    run cp -rfT root_${EXT}/usr usr
    run eval  ~/.capkg/config/capkg.sh generate -p php${PHP_VERSION}-${EXT}  -i /usr/local -s root_${EXT}/usr/local/${PHP_NAME} ${CAPKCF_OPTIONS}
}

# APC
if [ "${OPT_APC}" = "1" ];then
    VERSION='3.1.7'
    ARCHIVE=http://pecl.php.net/get/APC-%s.tgz
    ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
    download ${ARCHIVE} 

    NAME=`basename ${ARCHIVE} .tgz`
    run tar xzvf ${NAME}.tgz
    run rm -rf apc
    run mv ${NAME} apc
    build_php_ext3 apc --enable-apc
fi

# CLEARSILVER
if [ "${OPT_CLEARSILVER}" = "1" ];then
    VERSION='0.4'
    ARCHIVE=http://www.geodata.soton.ac.uk/software/php_clearsilver/php-clearsilver-%s.tar.gz
    ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
    download ${ARCHIVE} 

    NAME=`basename ${ARCHIVE} .tar.gz`
    run tar xzvf ${NAME}.tar.gz
    run rm -rf clearsilver
    run mv ${NAME} clearsilver
    build_php_ext3 clearsilver  --with-clearsilver=/usr/local 
fi

if [ "${OPT_ZMQ}" = "1" ];then
    VERSION='1.0.2'
    git_download http://github.com/mkoppanen/php-zmq.git $VERSION
    run rm -rf zmq
    mv -T php-zmq zmq
    build_php_ext3 zmq  --with-zmq=/usr/local "--require='zeromq 2.1.9 999.999.999'"
fi    

if [ "${OPT_MONGO}" = "1" ];then
    VERSION='1.2.2a'
#    git_download http://github.com/mongodb/mongo-php-driver.git $VERSION
#    run patch -p 0 <mongo1.2.2.non-wait.patch
#    run patch -p 0 <mongo1.2.2.sock-leak.patch
    git_download http://github.com/cockatoo-org/mongo-php-driver.git $VERSION
    run rm -rf mongo
    run mv -T mongo-php-driver mongo
    build_php_ext3 mongo
fi    

if [ "${OPT_MEMCACHED}" = "1" ];then
    VERSION='2.0.0b2'
    ARCHIVE=http://pecl.php.net/get/memcached-%s.tgz
    ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
    download ${ARCHIVE} 

    NAME=`basename ${ARCHIVE} .tgz`
    run tar xzvf ${NAME}.tgz
    run rm -rf memcached
    run mv ${NAME} memcached
    build_php_ext3 memcached '--enable-memcached --enable-memcached-json --with-libmemcached-dir=/usr/local'  "--require='libmemcached 1.0.0 1.0.999'"
fi

if [ "${OPT_ZOOKEEPER}" = "1" ];then
    VERSION='v0.2.1'
    git_download http://github.com/andreiz/php-zookeeper.git $VERSION
#   patch -i php_zookeeper.patch
    run rm -rf zookeeper
    run mv php-zookeeper zookeeper
    build_php_ext3 zookeeper --with-libzookeeper-dir=/usr/local "--require='libzookeeper 3.3.2 999.999.999'"
fi


