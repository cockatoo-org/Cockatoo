#!/usr/bin/env bash
ROOT=`dirname $0`
cd ${ROOT}
ROOT=`pwd`
source ${ROOT}/../build.env

function build_jdk {
    NAME=$1
    VERSION=`basename $1 | sed 's/[a-zA-Z_]//g'`
    run rm -rf usr
    run mkdir -p        ${ROOT}/usr/local/java
    run cp -r ${NAME}   ${ROOT}/usr/local/java/
    run ln -sfT ${NAME} ${ROOT}/usr/local/java/jdk
    run sudo mkdir -p        /usr/local/java
    run sudo cp -r ${ROOT}/usr/local/java   /usr/local/
    run ~/.capkg/config/capkg.sh generate -p jdk -v ${VERSION} -i /usr -s usr/local/
}

for T in `find . -maxdepth 1 -type d  | grep '^./jdk'`;
  do
    echo "[$T] directory is found !"
    echo 'Are you sure ? [Y/N]'
    read INPUT
    if [ "$INPUT" != "Y" ];then
	continue
    fi
    build_jdk $T
    exit 0;
done
echo 'Please get JDK archives from '
echo '   "http://www.oracle.com/technetwork/java/javase/downloads/"'
echo '   and extract it here.'
exit 1;
