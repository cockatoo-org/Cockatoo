#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='2.2.21'
ARCHIVE=http://ftp.jaist.ac.jp/pub/apache//httpd/httpd-%s.tar.gz
FILE_MD5=http://www.apache.org/dist/httpd/httpd-%s.tar.gz.md5

# GET BINARIES
ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
FILE_MD5=`printf ${FILE_MD5} ${VERSION}`
download ${ARCHIVE}
download ${FILE_MD5}

run md5sum -c `basename ${FILE_MD5}`

NAME=`basename ${ARCHIVE} .tar.gz`
run rm -rf ${NAME}
run rm -rf usr
run tar xzvf ${NAME}.tar.gz

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
	--enable-proxy
	# --with-ssl=/usr/local/ssl \
    run make
    run make install DESTDIR=${ROOT}
    run ln -sfT apache-${VERSION} ${ROOT}/usr/local/apache2
    run_edit ${ROOT}/envvars 'LD_LIBRARY_PATH="/usr/local/lib:/usr/local/ssl/lib:/usr/local/apache-'${VERSION}'/lib:$LD_LIBRARY_PATH"' 
    run_edit ${ROOT}/envvars 'export LD_LIBRARY_PATH' 1
    run cp -fT ${ROOT}/envvars ${ROOT}/usr/local/apache-${VERSION}/bin/envvars
    run mkdir -p ${ROOT}/conf/usr/local/apache-${VERSION}/conf/conf.d
    run cp ${ROOT}/httpd.conf ${ROOT}/conf/usr/local/apache-${VERSION}/conf/
    # install
    run sudo cp -rT ${ROOT}/usr/local /usr/local
    run sudo cp -rT ${ROOT}/conf/usr/local /usr/local
#    run sudo make install
#    run sudo mkdir -p /usr/local/apache-${VERSION}/conf/conf.d
#    run sudo ln -sfT apache-${VERSION} /usr/local/apache2
#    run sudo cp -fT ${ROOT}/envvars    /usr/local/apache-${VERSION}/bin/envvars
#    run sudo cp -fT ${ROOT}/httpd.conf /usr/local/apache-${VERSION}/conf/httpd.conf
    run popd
}

build_httpd
run ~/.capkg/config/capkg.sh generate -p httpd${VERSION} -i /usr -s usr/local
run eval ~/.capkg/config/capkg.sh generate -p httpd${VERSION}-conf -i /usr/local -s conf/usr/local/apache-${VERSION} "--require='httpd${VERSION} 0.0.1 0.0.999'"

# SELinux
# sudo /usr/sbin/semanage port -a -t http_port_t -p tcp 80
# Edit /etc/sysconfig/iptables.conf
# -A RH-Firewall-1-INPUT -m state --state NEW -m tcp -p tcp --dport 80 -j ACCEPT
# sudo /etc/init.d/iptables condrestart
