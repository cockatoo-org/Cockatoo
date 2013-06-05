#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

VERSION='1.3.15'

ARCHIVE=http://nginx.org/download/nginx-%s.tar.gz
FILE_PGP=http://nginx.org/download/nginx-%s.tar.gz.asc



# GET BINARIES
ARCHIVE=`printf ${ARCHIVE} ${VERSION}`
FILE_PGP=`printf ${FILE_PGP} ${VERSION}`
download ${ARCHIVE}
download ${FILE_PGP}

# gpg --keyserver wwwkeys.us.pgp.net --recv-keys A1C052F8
# gpg --edit-key A1C052F8
# > trust
# 5
#run gpg `basename ${FILE_PGP}`

NAME=`basename ${ARCHIVE} .tar.gz`
run rm -rf ${NAME}
run rm -rf usr
run tar xzvf ${NAME}.tar.gz

function build_nginx(){
    run pushd ${NAME}
    run ./configure                        \
	--prefix=/usr/local/nginx-${VERSION} \
	--without-http_ssi_module                 \
	--without-http_autoindex_module           \
	--without-http_geo_module                 \
	--without-http_map_module                 \
	--without-http_memcached_module           \
	--without-http_empty_gif_module           \
	--without-http_browser_module             \
	--with-http_ssl_module             \
	--with-http_spdy_module            \
	--with-http_gzip_static_module     \
	--with-http_stub_status_module     \
	--with-pcre                        \
	--with-pcre-jit                    

#	--with-http_realip_module          \
#	--with-http_xslt_module            \
#	--with-http_sub_module             \
#	--with-http_dav_module             \
#	--with-http_gunzip_module          \
#	--with-http_random_index_module    \
#	--with-http_secure_link_module     \
#	--with-http_degradation_module     \
#	--with-http_addition_module        \

    run make
    run make install DESTDIR=${ROOT}
    run ln -sfT nginx-${VERSION} ${ROOT}/usr/local/nginx
    run mkdir -p ${ROOT}/conf/usr/local/nginx-${VERSION}/conf/
    run cp ${ROOT}/nginx.conf ${ROOT}/conf/usr/local/nginx-${VERSION}/conf/
    # install
    run sudo cp -rT ${ROOT}/usr/local /usr/local
    run sudo cp -rT ${ROOT}/conf/usr/local /usr/local
    run popd
}

build_nginx
if [ "${WITH_CAPKG}" != "" ]; then
    run eval ~/.capkg/config/capkg.sh generate -p nginx${VERSION} -i /usr -s usr/local
    run eval ~/.capkg/config/capkg.sh generate -p nginx${VERSION}-conf -i /usr/local -s conf/usr/local/nginx-${VERSION} "--require='nginx${VERSION} 0.0.1 0.0.999'"
fi

# SELinux
# sudo /usr/sbin/semanage port -a -t http_port_t -p tcp 80
# Edit /etc/sysconfig/iptables.conf
# -A RH-Firewall-1-INPUT -m state --state NEW -m tcp -p tcp --dport 80 -j ACCEPT
# sudo /etc/init.d/iptables condrestart



#  --without-http_split_clients_module       \
#  --without-http_charset_module             \
#  --without-http_gzip_module                \
#  --without-http_userid_module              \
#  --without-http_access_module              \
#  --without-http_auth_basic_module          \
#  --without-http_referer_module             \
#  --without-http_rewrite_module             \
#  --without-http_proxy_module               \
#  --without-http_fastcgi_module             \
#  --without-http_uwsgi_module               \
#  --without-http_scgi_module                \
#  --without-http_limit_conn_module          \
#  --without-http_limit_req_module           \
#  --without-http_upstream_ip_hash_module    \
#  --without-http_upstream_least_conn_module \
#  --without-http_upstream_keepalive_module  \
