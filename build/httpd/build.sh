#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='2.4.4'

ARCHIVE=http://archive.apache.org/dist/httpd/httpd-%s.tar.gz
FILE_MD5=http://archive.apache.org/dist/httpd/httpd-%s.tar.gz.md5

APR=http://archive.apache.org/dist/apr/apr-1.4.6.tar.gz
APR_UTIL=http://archive.apache.org/dist/apr/apr-util-1.5.2.tar.gz
APR_ICONV=http://archive.apache.org/dist/apr/apr-iconv-1.2.1.tar.gz

# GET BINARIES
ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
FILE_MD5=`printf ${FILE_MD5} ${VERSION}`
download ${ARCHIVE}
download ${FILE_MD5}

run md5sum -c `basename ${FILE_MD5}`

NAME=`basename ${ARCHIVE} .tar.gz`
run rm -rf usr
run rm -rf ${NAME}
run tar xzvf ${NAME}.tar.gz

download ${APR}
download ${APR_ICONV}
download ${APR_UTIL}

APR_NAME=`basename ${APR} .tar.gz`
run rm -rf ${APR_NAME}
run tar xzvf ${APR_NAME}.tar.gz

APR_ICONV_NAME=`basename ${APR_ICONV} .tar.gz`
run rm -rf ${APR_ICONV_NAME}
run tar xzvf ${APR_ICONV_NAME}.tar.gz

APR_UTIL_NAME=`basename ${APR_UTIL} .tar.gz`
run rm -rf ${APR_UTIL_NAME}
run tar xzvf ${APR_UTIL_NAME}.tar.gz

function build_apr(){
    pushd ${APR_NAME}
    run ./configure \
	--prefix=/usr/local/apr
    run make
    run make install DESTDIR=${ROOT}
    run sudo make install
    run popd
}
function build_apr_util(){
    pushd ${APR_UTIL_NAME}
    run ./configure \
	--prefix=/usr/local/apr \
	--with-apr=/usr/local/apr \
#	--with-apr-iconv=../${APR_ICONV_NAME}

    run make
    run make install DESTDIR=${ROOT}
    run sudo make install
    run popd
}

function build_httpd(){
    run pushd ${NAME}
    run ./configure \
	--prefix=/usr/local/apache-${VERSION} \
	--with-mpm=prefork \
	--enable-ssl \
	--enable-mods-shared=all \
	--enable-cache \
	--enable-disk-cache \
	--enable-mem-cache \
	--enable-proxy \
	--with-apr=/usr/local/apr \
	--with-apr-util=/usr/local/apr
	# --with-ssl=/usr/local/ssl \
    run make
    run make install DESTDIR=${ROOT}
    run ln -sfT apache-${VERSION} ${ROOT}/usr/local/apache2
    run_edit ${ROOT}/envvars 'LD_LIBRARY_PATH="/usr/local/lib:/usr/local/ssl/lib:/usr/local/apache-'${VERSION}'/lib:$LD_LIBRARY_PATH"' 
    run_edit ${ROOT}/envvars 'export LD_LIBRARY_PATH'
    run cp -fT ${ROOT}/envvars ${ROOT}/usr/local/apache-${VERSION}/bin/envvars
    run mkdir -p ${ROOT}/conf/usr/local/apache-${VERSION}/conf/conf.d
    run cp ${ROOT}/httpd.conf ${ROOT}/conf/usr/local/apache-${VERSION}/conf/
    # install
    run sudo cp -rT ${ROOT}/usr/local /usr/local
    run sudo cp -rT ${ROOT}/conf/usr/local /usr/local
    run popd
}
function build_proxy(){
    run pushd ${NAME}
    run ./configure \
	--prefix=/usr/local/proxy-${VERSION} \
	--enable-ssl \
	--enable-mods-shared=all \
	--enable-cache \
	--enable-disk-cache \
	--enable-mem-cache \
	--enable-proxy \
	--with-apr=/usr/local/apr \
	--with-apr-util=/usr/local/apr
	# --with-ssl=/usr/local/ssl \
    # install
    run make
    run make install DESTDIR=${ROOT}
    run popd
}

build_apr
build_apr_util
build_httpd
#build_proxy
if [ "${WITH_CAPKG}" != "" ]; then
    run eval ~/.capkg/config/capkg.sh generate -p httpd${VERSION} -i /usr -s usr/local
    run eval ~/.capkg/config/capkg.sh generate -p httpd${VERSION}-conf -i /usr/local -s conf/usr/local/apache-${VERSION} "--require='httpd${VERSION} 0.0.1 0.0.999'"
fi

# SELinux
# sudo /usr/sbin/semanage port -a -t http_port_t -p tcp 80
# Edit /etc/sysconfig/iptables.conf
# -A RH-Firewall-1-INPUT -m state --state NEW -m tcp -p tcp --dport 80 -j ACCEPT
# sudo /etc/init.d/iptables condrestart
