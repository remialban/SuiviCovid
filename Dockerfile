FROM debian

RUN apt-get update

RUN apt install -y wget gnupg2 ca-certificates apt-transport-https software-properties-common gnupg gnupg1 gnupg2 git -y

RUN wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add - 
RUN echo "deb https://packages.sury.org/php/ buster main" | tee /etc/apt/sources.list.d/php.list 

RUN apt update
RUN apt install php8.0 -y
RUN apt install libapache2-mod-php8.0 php8.0-mysql php8.0-curl php8.0-gd php8.0-intl php8.0-sqlite3 php8.0-gmp php8.0-mbstring php8.0-xml php8.0-zip -y

RUN echo "ServerName 127.0.0.1" >> /etc/apache2/apache2.conf

WORKDIR /var/www
RUN git clone https://github.com/remialban/SuiviCovid.git suivicovid
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/bin/composer

WORKDIR /var/www/suivicovid

RUN composer install
RUN php bin/console doctrine:database:create
RUN php bin/console doctrine:schema:update --force

RUN rm -Rf /etc/apache2/sites-enabled
RUN mkdir /etc/apache2/sites-enabled
RUN mv apache.conf /etc/apache2/sites-enabled/000-symfony.conf
RUN chmod a+rwxX /var/www/suivicovid/var/data.db

CMD apachectl -D FOREGROUND
