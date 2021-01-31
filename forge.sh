#
# REQUIRES:
#       - server (the forge server instance)
#       - event (the forge event instance)
#       - sudo_password (random password for sudo)
#       - db_password (random password for database user)
#       - callback (the callback URL)
#       - recipe_id (recipe id to run at the end)
#

export DEBIAN_FRONTEND=noninteractive

# Ping Forge With Provisioning Updates

function provisionPing {
  curl --insecure --data "status=$2&server_id=$1" https://forge.laravel.com/provisioning/callback/status
}
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root."

   exit 1
fi

provisionPing 434789 1

apt_wait () {
    while fuser /var/lib/dpkg/lock >/dev/null 2>&1 ; do
        echo "Waiting: dpkg/lock is locked..."
        sleep 5
    done

    while fuser /var/lib/dpkg/lock-frontend >/dev/null 2>&1 ; do
        echo "Waiting: dpkg/lock-frontend is locked..."
        sleep 5
    done

    while fuser /var/lib/apt/lists/lock >/dev/null 2>&1 ; do
        echo "Waiting: lists/lock is locked..."
        sleep 5
    done

    if [ -f /var/log/unattended-upgrades/unattended-upgrades.log ]; then
        while fuser /var/log/unattended-upgrades/unattended-upgrades.log >/dev/null 2>&1 ; do
            echo "Waiting: unattended-upgrades is locked..."
            sleep 5
        done
    fi
}

echo "Checking apt-get availability..."

apt_wait

sudo sed -i "s/#precedence ::ffff:0:0\/96  100/precedence ::ffff:0:0\/96  100/" /etc/gai.conf

# Configure Swap Disk

provisionPing 434789 2

if [ -f /swapfile ]; then
    echo "Swap exists."
else
    fallocate -l 1G /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo "/swapfile none swap sw 0 0" >> /etc/fstab
    echo "vm.swappiness=30" >> /etc/sysctl.conf
    echo "vm.vfs_cache_pressure=50" >> /etc/sysctl.conf
fi

provisionPing 434789 3

# Upgrade The Base Packages

export DEBIAN_FRONTEND=noninteractive

apt-get update

apt_wait

apt-get upgrade -y

apt_wait

# Add A Few PPAs To Stay Current

apt-get install -y --force-yes software-properties-common

# apt-add-repository ppa:fkrull/deadsnakes-python2.7 -y
# apt-add-repository ppa:nginx/mainline -y
apt-add-repository ppa:ondrej/nginx -y
# apt-add-repository ppa:chris-lea/redis-server -y
apt-add-repository ppa:ondrej/apache2 -y

	apt-add-repository ppa:ondrej/php -y

# Setup MariaDB Repositories

# 

# Update Package Lists

apt_wait

apt-get update
# Base Packages

apt_wait

add-apt-repository universe

apt-get install -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" -y --force-yes build-essential curl pkg-config fail2ban gcc g++ git libmcrypt4 libpcre3-dev \
make python3 python3-pip sendmail supervisor ufw zip unzip whois zsh ncdu awscli uuid-runtime acl libpng-dev libmagickwand-dev

# Install Python Httpie

pip3 install httpie

# Disable Password Authentication Over SSH

sed -i "/PasswordAuthentication yes/d" /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "PasswordAuthentication no" | sudo tee -a /etc/ssh/sshd_config

# Restart SSH

ssh-keygen -A
service ssh restart

# Set The Hostname If Necessary


echo "fastvps" > /etc/hostname
sed -i 's/127\.0\.0\.1.*localhost/127.0.0.1	fastvps.localdomain fastvps localhost/' /etc/hosts
hostname fastvps


# Set The Timezone

# ln -sf /usr/share/zoneinfo/UTC /etc/localtime
ln -sf /usr/share/zoneinfo/UTC /etc/localtime

# Create The Root SSH Directory If Necessary

if [ ! -d /root/.ssh ]
then
	mkdir -p /root/.ssh
	touch /root/.ssh/authorized_keys
fi

# Check Permissions Of /root Directory

chown root:root /root
chown -R root:root /root/.ssh

chmod 700 /root/.ssh
chmod 600 /root/.ssh/authorized_keys

# Setup Forge User

useradd forge
mkdir -p /home/forge/.ssh
mkdir -p /home/forge/.forge
adduser forge sudo

# Setup Bash For Forge User

chsh -s /bin/bash forge
cp /root/.profile /home/forge/.profile
cp /root/.bashrc /home/forge/.bashrc

# Set The Sudo Password For Forge

PASSWORD=$(mkpasswd -m sha-512 PUonOvDlR2vlMXcaycCq)
usermod --password $PASSWORD forge

# Build Formatted Keys & Copy Keys To Forge


cat > /root/.ssh/authorized_keys << EOF
# Laravel Forge
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCrHC8YiTPd23ietzxHp07CoCXRhY5VGjbfXnhjMdeyR8YsGbGE5XYz5Q7qbdY04rHx1+sYoHczQHWb3xBUAcMk0gGpbxnBJJd14hIgNQnBRg4gndqXIA0rnBp10Ti1k5TAPrI6OdACy+lWYlvTQ6wfVxlDULd9buJmIpv8BcDL0TPhOiXbF1RsFzmhFAustku/publIRmB6otSvEoqvgCNfzhEIrNzO8n0kXRK/am6zbp40OdzQCb9Bcb8kP3aHvSDdyfzdNlm3KgnvaRWnMhrVEdEcQ6m+swKrjcIrWmKkIQn5/EYRB6uU31EJmGLYZYeGN7FUptbxdUMReIRpVsZ worker@forge.laravel.com


EOF


cp /root/.ssh/authorized_keys /home/forge/.ssh/authorized_keys

# Create The Server SSH Key

ssh-keygen -f /home/forge/.ssh/id_rsa -t rsa -N ''

# Copy Source Control Public Keys Into Known Hosts File

ssh-keyscan -H github.com >> /home/forge/.ssh/known_hosts
ssh-keyscan -H bitbucket.org >> /home/forge/.ssh/known_hosts
ssh-keyscan -H gitlab.com >> /home/forge/.ssh/known_hosts

# Configure Git Settings

git config --global user.name "shenia"
git config --global user.email "boboilodarchenko@gmail.com"

# Add The Reconnect Script Into Forge Directory

cat > /home/forge/.forge/reconnect << EOF
#!/usr/bin/env bash

echo "# Laravel Forge" | tee -a /home/forge/.ssh/authorized_keys > /dev/null
echo \$1 | tee -a /home/forge/.ssh/authorized_keys > /dev/null

echo "# Laravel Forge" | tee -a /root/.ssh/authorized_keys > /dev/null
echo \$1 | tee -a /root/.ssh/authorized_keys > /dev/null

echo "Keys Added!"
EOF

# Setup Forge Home Directory Permissions

chown -R forge:forge /home/forge
chmod -R 755 /home/forge
chmod 700 /home/forge/.ssh/id_rsa

# Setup UFW Firewall

ufw allow 22
ufw allow 80
ufw allow 443
ufw --force enable

# Allow FPM Restart

echo "forge ALL=NOPASSWD: /usr/sbin/service php8.0-fpm reload" > /etc/sudoers.d/php-fpm
echo "forge ALL=NOPASSWD: /usr/sbin/service php7.4-fpm reload" > /etc/sudoers.d/php-fpm
echo "forge ALL=NOPASSWD: /usr/sbin/service php7.3-fpm reload" >> /etc/sudoers.d/php-fpm
echo "forge ALL=NOPASSWD: /usr/sbin/service php7.2-fpm reload" >> /etc/sudoers.d/php-fpm
echo "forge ALL=NOPASSWD: /usr/sbin/service php7.1-fpm reload" >> /etc/sudoers.d/php-fpm
echo "forge ALL=NOPASSWD: /usr/sbin/service php7.0-fpm reload" >> /etc/sudoers.d/php-fpm
echo "forge ALL=NOPASSWD: /usr/sbin/service php5.6-fpm reload" >> /etc/sudoers.d/php-fpm
echo "forge ALL=NOPASSWD: /usr/sbin/service php5-fpm reload" >> /etc/sudoers.d/php-fpm

# Allow Nginx Reload

echo "forge ALL=NOPASSWD: /usr/sbin/service nginx *" >> /etc/sudoers.d/nginx

# Allow Supervisor Reload

echo "forge ALL=NOPASSWD: /usr/bin/supervisorctl *" >> /etc/sudoers.d/supervisor

apt_wait

    provisionPing 434789 4

    #
# REQUIRES:
#       - server (the forge server instance)
#

# Install Base PHP Packages

apt-get install -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" -y --force-yes php7.4-cli php7.4-fpm php7.4-dev \
php7.4-pgsql php7.4-sqlite3 php7.4-gd \
php7.4-curl php7.4-memcached \
php7.4-imap php7.4-mysql php7.4-mbstring \
php7.4-xml php7.4-zip php7.4-bcmath php7.4-soap \
php7.4-intl php7.4-readline php7.4-msgpack php7.4-igbinary php7.4-gmp

# Install Composer Package Manager

if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

# Misc. PHP CLI Configuration

sudo sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.4/cli/php.ini
sudo sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.4/cli/php.ini
sudo sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/7.4/cli/php.ini
sudo sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/7.4/cli/php.ini
sudo sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/7.4/cli/php.ini

# Ensure PHPRedis Extension Is Available

echo "Configuring PHPRedis"
echo "extension=redis.so" > /etc/php/7.4/mods-available/redis.ini
yes '' | apt install php-redis

# Ensure Imagick Is Available

echo "Configuring Imagick"

apt-get install -y --force-yes libmagickwand-dev
echo "extension=imagick.so" > /etc/php/7.4/mods-available/imagick.ini
yes '' | apt install php-imagick

# Configure FPM Pool Settings

sed -i "s/^user = www-data/user = forge/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/^group = www-data/group = forge/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;listen\.owner.*/listen.owner = forge/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;listen\.group.*/listen.group = forge/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;listen\.mode.*/listen.mode = 0666/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;request_terminate_timeout.*/request_terminate_timeout = 60/" /etc/php/7.4/fpm/pool.d/www.conf

# Ensure Sudoers Is Up To Date

LINE="ALL=NOPASSWD: /usr/sbin/service php7.4-fpm reload"
FILE="/etc/sudoers.d/php-fpm"
grep -qF -- "forge $LINE" "$FILE" || echo "forge $LINE" >> "$FILE"

# Configure Sessions Directory Permissions

chmod 733 /var/lib/php/sessions
chmod +t /var/lib/php/sessions

# Write Systemd File For Linode




    update-alternatives --set php /usr/bin/php7.4

provisionPing 434789 5

    #
# REQUIRES:
#       - server (the forge server instance)
#		- site_name (the name of the site folder)
#

# Install Nginx & PHP-FPM
apt-get install -y --force-yes nginx

systemctl enable nginx.service

# Generate dhparam File

openssl dhparam -out /etc/nginx/dhparams.pem 2048

# Tweak Some PHP-FPM Settings

sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.4/fpm/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.4/fpm/php.ini
sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/7.4/fpm/php.ini
sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/7.4/fpm/php.ini
sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php/7.4/fpm/php.ini

# Configure FPM Pool Settings

sed -i "s/^user = www-data/user = forge/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/^group = www-data/group = forge/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;listen\.owner.*/listen.owner = forge/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;listen\.group.*/listen.group = forge/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;listen\.mode.*/listen.mode = 0666/" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;request_terminate_timeout.*/request_terminate_timeout = 60/" /etc/php/7.4/fpm/pool.d/www.conf

# Configure Primary Nginx Settings

sed -i "s/user www-data;/user forge;/" /etc/nginx/nginx.conf
sed -i "s/worker_processes.*/worker_processes auto;/" /etc/nginx/nginx.conf
sed -i "s/# multi_accept.*/multi_accept on;/" /etc/nginx/nginx.conf
sed -i "s/# server_names_hash_bucket_size.*/server_names_hash_bucket_size 128;/" /etc/nginx/nginx.conf

# Configure Gzip

cat > /etc/nginx/conf.d/gzip.conf << EOF
gzip_comp_level 5;
gzip_min_length 256;
gzip_proxied any;
gzip_vary on;
gzip_http_version 1.1;

gzip_types
application/atom+xml
application/javascript
application/json
application/ld+json
application/manifest+json
application/rss+xml
application/vnd.geo+json
application/vnd.ms-fontobject
application/x-font-ttf
application/x-web-app-manifest+json
application/xhtml+xml
application/xml
font/opentype
image/bmp
image/svg+xml
image/x-icon
text/cache-manifest
text/css
text/plain
text/vcard
text/vnd.rim.location.xloc
text/vtt
text/x-component
text/x-cross-domain-policy;

EOF

# Disable The Default Nginx Site

rm /etc/nginx/sites-enabled/default
rm /etc/nginx/sites-available/default
service nginx restart

# Install A Catch All Server

cat > /etc/nginx/sites-available/000-catch-all << EOF
server {
    return 404;
}
EOF

ln -s /etc/nginx/sites-available/000-catch-all /etc/nginx/sites-enabled/000-catch-all

# Restart Nginx & PHP-FPM Services

# Restart Nginx & PHP-FPM Services

#service nginx restart
service nginx reload

if [ ! -z "\$(ps aux | grep php-fpm | grep -v grep)" ]
then
    service php8.0pm restart > /dev/null 2>&1
    service php7.4-fpm restart > /dev/null 2>&1
    service php7.3-fpm restart > /dev/null 2>&1
    service php7.2-fpm restart > /dev/null 2>&1
    service php7.1-fpm restart > /dev/null 2>&1
    service php7.0-fpm restart > /dev/null 2>&1
    service php5.6-fpm restart > /dev/null 2>&1
    service php5-fpm restart > /dev/null 2>&1
fi

# Add Forge User To www-data Group

usermod -a -G www-data forge
id forge
groups forge


apt_wait

curl --silent --location https://deb.nodesource.com/setup_12.x | bash -

apt-get update

sudo apt-get install -y --force-yes nodejs

npm install -g pm2
npm install -g gulp
npm install -g yarn

    provisionPing 434789 6

    #
# REQUIRES:
#		- server (the forge server instance)
#		- db_password (random password for mysql user)
#

# Set The Automated Root Password

export DEBIAN_FRONTEND=noninteractive

wget -c https://dev.mysql.com/get/mysql-apt-config_0.8.15-1_all.deb
dpkg --install mysql-apt-config_0.8.15-1_all.deb

debconf-set-selections <<< "mysql-community-server mysql-community-server/data-dir select ''"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password uRSGciKlwYcIKA96PPXa"
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password uRSGciKlwYcIKA96PPXa"

apt-get update

# Install MySQL

apt-get install -y mysql-community-server
apt-get install -y mysql-server

# Configure Password Expiration

echo "default_password_lifetime = 0" >> /etc/mysql/mysql.conf.d/mysqld.cnf

# Set Character Set

echo "" >> /etc/mysql/my.cnf
echo "[mysqld]" >> /etc/mysql/my.cnf
echo "default_authentication_plugin=mysql_native_password" >> /etc/mysql/my.cnf
echo "skip-log-bin" >> /etc/mysql/my.cnf

# Configure Max Connections

RAM=$(awk '/^MemTotal:/{printf "%3.0f", $2 / (1024 * 1024)}' /proc/meminfo)
MAX_CONNECTIONS=$(( 70 * $RAM ))
REAL_MAX_CONNECTIONS=$(( MAX_CONNECTIONS>70 ? MAX_CONNECTIONS : 100 ))
sed -i "s/^max_connections.*=.*/max_connections=${REAL_MAX_CONNECTIONS}/" /etc/mysql/my.cnf

# Configure Access Permissions For Root & Forge Users

sed -i '/^bind-address/s/bind-address.*=.*/bind-address = */' /etc/mysql/mysql.conf.d/mysqld.cnf
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE USER 'root'@'185.105.225.76' IDENTIFIED BY 'uRSGciKlwYcIKA96PPXa';"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE USER 'root'@'%' IDENTIFIED BY 'uRSGciKlwYcIKA96PPXa';"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "GRANT ALL PRIVILEGES ON *.* TO root@'185.105.225.76' WITH GRANT OPTION;"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "GRANT ALL PRIVILEGES ON *.* TO root@'%' WITH GRANT OPTION;"
service mysql restart

mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE USER 'forge'@'185.105.225.76' IDENTIFIED BY 'uRSGciKlwYcIKA96PPXa';"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE USER 'forge'@'%' IDENTIFIED BY 'uRSGciKlwYcIKA96PPXa';"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "GRANT ALL PRIVILEGES ON *.* TO 'forge'@'185.105.225.76' WITH GRANT OPTION;"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "GRANT ALL PRIVILEGES ON *.* TO 'forge'@'%' WITH GRANT OPTION;"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "FLUSH PRIVILEGES;"

# Create The Initial Database If Specified

mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE DATABASE forge CHARACTER SET utf8 COLLATE utf8_unicode_ci;"

    # If MySQL Fails To Start, Re-Install It

    service mysql restart

    if [[ $? -ne 0 ]]; then
        echo "Purging previous MySQL8 installation..."

        sudo apt-get purge mysql-server mysql-community-server
        sudo apt-get autoclean && sudo apt-get clean

        #
# REQUIRES:
#		- server (the forge server instance)
#		- db_password (random password for mysql user)
#

# Set The Automated Root Password

export DEBIAN_FRONTEND=noninteractive

wget -c https://dev.mysql.com/get/mysql-apt-config_0.8.15-1_all.deb
dpkg --install mysql-apt-config_0.8.15-1_all.deb

debconf-set-selections <<< "mysql-community-server mysql-community-server/data-dir select ''"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password uRSGciKlwYcIKA96PPXa"
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password uRSGciKlwYcIKA96PPXa"

apt-get update

# Install MySQL

apt-get install -y mysql-community-server
apt-get install -y mysql-server

# Configure Password Expiration

echo "default_password_lifetime = 0" >> /etc/mysql/mysql.conf.d/mysqld.cnf

# Set Character Set

echo "" >> /etc/mysql/my.cnf
echo "[mysqld]" >> /etc/mysql/my.cnf
echo "default_authentication_plugin=mysql_native_password" >> /etc/mysql/my.cnf
echo "skip-log-bin" >> /etc/mysql/my.cnf

# Configure Max Connections

RAM=$(awk '/^MemTotal:/{printf "%3.0f", $2 / (1024 * 1024)}' /proc/meminfo)
MAX_CONNECTIONS=$(( 70 * $RAM ))
REAL_MAX_CONNECTIONS=$(( MAX_CONNECTIONS>70 ? MAX_CONNECTIONS : 100 ))
sed -i "s/^max_connections.*=.*/max_connections=${REAL_MAX_CONNECTIONS}/" /etc/mysql/my.cnf

# Configure Access Permissions For Root & Forge Users

sed -i '/^bind-address/s/bind-address.*=.*/bind-address = */' /etc/mysql/mysql.conf.d/mysqld.cnf
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE USER 'root'@'185.105.225.76' IDENTIFIED BY 'uRSGciKlwYcIKA96PPXa';"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE USER 'root'@'%' IDENTIFIED BY 'uRSGciKlwYcIKA96PPXa';"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "GRANT ALL PRIVILEGES ON *.* TO root@'185.105.225.76' WITH GRANT OPTION;"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "GRANT ALL PRIVILEGES ON *.* TO root@'%' WITH GRANT OPTION;"
service mysql restart

mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE USER 'forge'@'185.105.225.76' IDENTIFIED BY 'uRSGciKlwYcIKA96PPXa';"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE USER 'forge'@'%' IDENTIFIED BY 'uRSGciKlwYcIKA96PPXa';"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "GRANT ALL PRIVILEGES ON *.* TO 'forge'@'185.105.225.76' WITH GRANT OPTION;"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "GRANT ALL PRIVILEGES ON *.* TO 'forge'@'%' WITH GRANT OPTION;"
mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "FLUSH PRIVILEGES;"

# Create The Initial Database If Specified

mysql --user="root" --password="uRSGciKlwYcIKA96PPXa" -e "CREATE DATABASE forge CHARACTER SET utf8 COLLATE utf8_unicode_ci;"
    fi

apt_wait

provisionPing 434789 7

# Install & Configure Redis Server

apt-get install -y redis-server
sed -i 's/bind 127.0.0.1/bind 0.0.0.0/' /etc/redis/redis.conf
service redis-server restart
systemctl enable redis-server

yes '' | pecl install -f redis

# Ensure PHPRedis extension is available
if pecl list | grep redis >/dev/null 2>&1;
then
echo "Configuring PHPRedis"
echo "extension=redis.so" > /etc/php/7.4/mods-available/redis.ini
yes '' | apt install php7.4-redis

fi

apt_wait

provisionPing 434789 8

# Install & Configure Memcached

apt-get install -y memcached
sed -i 's/-l 127.0.0.1/-l 0.0.0.0/' /etc/memcached.conf
service memcached restart

# Configure Supervisor Autostart

systemctl enable supervisor.service
service supervisor start

# Disable protected_regular

sudo sed -i "s/fs.protected_regular = .*/fs.protected_regular = 0/" /usr/lib/sysctl.d/protect-links.conf

sysctl --system

# Setup Unattended Security Upgrades

apt_wait

provisionPing 434789 9

apt-get install -y --force-yes unattended-upgrades

cat > /etc/apt/apt.conf.d/50unattended-upgrades << EOF
Unattended-Upgrade::Allowed-Origins {
    "Ubuntu focal-security";
};
Unattended-Upgrade::Package-Blacklist {
    //
};
EOF

cat > /etc/apt/apt.conf.d/10periodic << EOF
APT::Periodic::Update-Package-Lists "1";
APT::Periodic::Download-Upgradeable-Packages "1";
APT::Periodic::AutocleanInterval "7";
APT::Periodic::Unattended-Upgrade "1";
EOF

curl --insecure --data "event_id=18423086&server_id=434789&sudo_password=PUonOvDlR2vlMXcaycCq&db_password=uRSGciKlwYcIKA96PPXa&recipe_id=" https://forge.laravel.com/provisioning/callback/app
