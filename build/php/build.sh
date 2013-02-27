#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='5.4.11'
HTTPD_VERSION='2.2.22'
        
ARCHIVE=http://jp1.php.net/get/php-%s.tar.gz/from/jp2.php.net/mirror/
FNAME=php-%s.tar.gz
FNAME=`printf ${FNAME} ${VERSION}`
ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
download ${ARCHIVE} ${FNAME}


NAME="php-${VERSION}"

function build_php(){
    # sudo yum install libxslt-devel libicu-devel 
    run rm -rf ${NAME}
    run tar xzvf ${NAME}.tar.gz
    # MK dst
    run rm -rf root_php
    run mkdir root_php
    run rm -rf root_httpd
    run mkdir root_httpd
    #
    run pushd ${NAME}
    run export EXTRA_LIBS=-lresolv
    run export PHP_MYSQLND_ENABLED=yes
    run ./configure --prefix=/usr/local/${NAME} \
	--with-pear \
	--with-apxs2=/usr/local/apache-${HTTPD_VERSION}/bin/apxs \
	--with-config-file-path=/usr/local/${NAME}/lib \
	--with-config-file-scan-dir=/usr/local/${NAME}/lib/conf.d \
	--enable-libgcc \
	--enable-mbstring \
	--enable-pcntl \
	--enable-sockets \
	--with-zlib=/usr \
	--with-curl=/usr \
#	--with-openssl=/usr/local/ssl \
#	--with-mcrypt=/usr/local \
#	--with-curl=/usr/local \
#	--with-pear=/usr/local/${NAME} \
#	--enable-debug 
    run mkdir -p ${ROOT}/root_php/usr/local/apache-${HTTPD_VERSION}/conf/conf.d/
    run mkdir -p ${ROOT}/root_php/usr/local/apache-${HTTPD_VERSION}/modules/
    run cp ${ROOT}/../httpd/httpd.conf  ${ROOT}/root_php/usr/local/apache-${HTTPD_VERSION}/conf/httpd.conf
    run cp ${ROOT}/httpd-php.conf ${ROOT}/root_php/usr/local/apache-${HTTPD_VERSION}/conf/conf.d/
    run make
    run make install INSTALL_ROOT=${ROOT}/root_php
    run popd

    # php-cli
    run ln -sfT  ${NAME} ${ROOT}/root_php/usr/local/php
    run mkdir -p ${ROOT}/root_php/usr/local/${NAME}/lib
    run mkdir -p ${ROOT}/root_php/usr/local/${NAME}/lib/conf.d
    run cp ${ROOT}/php.ini ${ROOT}/root_php/usr/local/${NAME}/lib
    # php-httpd
    run mkdir -p ${ROOT}/root_httpd/usr/local/${NAME}
    run ln -sfT  ${NAME} ${ROOT}/root_httpd/usr/local/php
    run cp -r ${ROOT}/root_php/usr/local/${NAME}/lib ${ROOT}/root_httpd/usr/local/${NAME}/

    run mkdir -p ${ROOT}/root_httpd/usr/local/
    run mv ${ROOT}/root_php/usr/local/apache-${HTTPD_VERSION} ${ROOT}/root_httpd/usr/local/

    run rm ${ROOT}/root_httpd/usr/local/apache-${HTTPD_VERSION}/conf/httpd.conf*
    run mkdir -p ${ROOT}/root_httpd/usr/local/apache-${HTTPD_VERSION}/conf/conf.d
    run cp ${ROOT}/httpd-php.conf ${ROOT}/root_httpd/usr/local/apache-${HTTPD_VERSION}/conf/conf.d/httpd-php.conf


    # install
    run sudo cp -rT ${ROOT}/root_php/usr/local /usr/local
    run sudo cp -rT ${ROOT}/root_httpd/usr/local /usr/local
#    run sudo make install
#    run sudo cp ${ROOT}/httpd-php.conf /usr/local/apache-${HTTPD_VERSION}/conf/conf.d/
#    run sudo cp ${ROOT}/php.ini /usr/local/${NAME}/lib
#    run sudo mkdir -p /usr/local/${NAME}/lib/conf.d/
#    run sudo ln -sfT ${NAME} /usr/local/php

    if [ "${WITH_CAPKG}" != "" ]; then
	run eval ~/.capkg/config/capkg.sh generate -p php${VERSION}-cli    -i /usr -s root_php/usr/local
	run eval ~/.capkg/config/capkg.sh generate -p php${VERSION}-httpd  -i /usr -s root_httpd/usr/local 
    fi

    run cp -rfT ${ROOT}/root_php/usr ${ROOT}/usr
}
function build_php_ext {
    run export PATH=/usr/local/${NAME}/bin:$PATH
    EXT=$1
    OPTIONS=$2
    MAKE_OPTIONS=$3
    CAPKCF_OPTIONS=$4
    # MK dst
    run rm -rf root_${EXT}
    run mkdir root_${EXT}
    # 
    run pushd ${NAME}/ext/${EXT}
    run /usr/local/${NAME}/bin/phpize
    run ./configure \
	--with-php-config=/usr/local/${NAME}/bin/php-config \
	${OPTIONS}
    run make  ${MAKE_OPTIONS}
    run make install INSTALL_ROOT=${ROOT}/root_${EXT}
    CONFD=${ROOT}/root_${EXT}/usr/local/${NAME}/lib/conf.d/
    run mkdir -p ${CONFD}
    run_edit ${CONFD}/${EXT}.ini "extension=${EXT}.so"
    # install 
    run sudo cp -rfT ${ROOT}/root_${EXT}/usr/local /usr/local
#    run sudo make install
#    run sudo cp ${CONFD}/${EXT}.ini /usr/local/${NAME}/lib/conf.d/
    run popd
    run cp -rfT ${ROOT}/root_${EXT}/usr ${ROOT}/usr
    if [ "${WITH_CAPKG}" != "" ]; then
	run eval ~/.capkg/config/capkg.sh generate -p php${VERSION}-${EXT}  -i /usr/local -s root_${EXT}/usr/local/${NAME} ${CAPKCF_OPTIONS}
    fi
}
build_php
build_php_ext bcmath
build_php_ext calendar
build_php_ext intl
build_php_ext sysvmsg
build_php_ext sysvsem
build_php_ext sysvshm --enable-sysvshm
build_php_ext zip
# You can chose.
# --with-qdbm 
# --with-gdbm 
# --with-ndbm 
# --with-db4 
# --with-db3
# --with-db2
# --with-db1 
# --with-dbm
# --without-cdb
build_php_ext dba	--enable-dba
build_php_ext mysql     --with-mysql=mysqlnd
build_php_ext mysqli    --with-mysqli=mysqlnd
# deal with mysql4 ...
# build_php_ext pdo_mysql --with-pdo-mysql=/usr/local/mysql-5.0.45 CFLAGS='-I../../' "--require='libmsql5 5.0.0 5.999.999'"
build_php_ext pdo_mysql --with-pdo-mysql=mysqlnd CFLAGS='-I../../'

#build_php_ext gd --with-lib-dir=/usr/lib64 --with-jpeg-dir=/usr --with-png-dir=/usr --with-zlib-dir=/usr

# SELinux
# sudo chcon -cv -u system_u -r object_r -t textrel_shlib_t /usr/local/apache2/modules/libphp5.so 

