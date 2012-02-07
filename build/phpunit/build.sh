# sudo su
# export http_proxy=??
# export PATH=/usr/local/php/bin:$PATH
# pear channel-discover pear.phpunit.de
# pear channel-discover components.ez.no
# pear channel-discover pear.symfony-project.com
# pear install phpunit/PHPUnit

tar xzvf PHPUnit-3.5.12.tgz 
pushd PHPUnit-3.5.12
patch -p 0  < ../PHPUnit-aggregate.patch 
popd

sudo rm -rf /usr/local/php/PHPUnit
sudo cp -r  PHPUnit-3.5.12/PHPUnit /usr/local/php/

tar xzvf xdebug-2.1.1.tgz 
pushd xdebug-2.1.1
phpize
./configure --enable-xdebug --with-php-config=/usr/local/php/bin/php-config 
make
sudo make install
popd
EXTENSION_DIR=`/usr/local/php/bin/php-config --extension-dir`
echo 'zend_extension='${EXTENSION_DIR}/xdebug.so > xdebug.ini
CONF_DIR=`/usr/local/php/bin/php -i | grep 'Scan this dir for additional .ini files => ' | sed -e 's/.*=> //'`
sudo cp xdebug.ini $CONF_DIR
