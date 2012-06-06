Всички указания са дадени така, сякаш ще се изпълняват върху операционната система ГНУ/Линукс. С времето ще се появят и още, нужно е само някой да седне и да ги напише. :-) В уикито на библиотека е създадена [специална страница](http://wiki.chitanka.info/Install), която ще служи като сборен пункт за всички указания по инсталацията.


1. Необходим софтуер
====================

Ето какво ще ви е необходимо, за да пуснете софтуера на Моята библиотека:

 - Уеб сървър Apache с PHP (версия на PHP >= 5.3.2)
 - MySQL сървър (версия >= 4.1)


2. Разархивиране и настройка
============================

_Тази стъпка се отнася за [торента с динамичната версия](http://forum.chitanka.info/chitanka-download-own-server-t3178.html)._

Разархивирайте файла chitanka.tar.gz в избрана от вас директория. В нея ще се създаде нова директория с име chitanka. По-надолу ще се обръщам към тази нова директория с името /PATH/TO/chitanka.

Сега е нужно да разрешите на софтуера (сървъра) да пише в следните директории:
app/cache, app/logs, web/cache, web/thumb

Това става най-лесно през командния ред:

	cd /PATH/TO/chitanka
	chmod -R a+w app/cache app/logs web/cache web/thumb

Ако разполагате и с файла със съдържанието на библиотеката (текстове, изображения), го разархивирайте в директорията /PATH/TO/chitanka/web:

	tar zxvf chitanka-content.tar.gz -C /PATH/TO/chitanka/web


(TODO) _Да се напише как се инсталира чрез клониране по git._


3. База от данни
================

_Тази стъпка се отнася за торента с динамичната версия._

Създайте нова база от данни с име chitanka. Например така:

	mysql -u root -e "CREATE DATABASE chitanka"

Ако root има парола, ползвайте `mysql -u root -p`.

После вмъкнете съдържанието на файла db.sql в новата база:

	mysql -u root chitanka < db.sql

При желание може да създадете специален потребител с достъп само да тази база от данни.

Във файла app/config/parameters.yml е посочена конфигурацията за базата от данни. По подразбиране този файл съдържа:

	database_host:      localhost
	database_name:      chitanka
	database_user:      root
	database_password:

Това ще рече, че базата от данни се намира на локалния компютър и се нарича chitanka. За достъп до нея ще се ползва потребителя root, който няма парола.

Ако решите да ползвате друга конфигурация, напр. root с парола или пък съвсем друг потребител, просто въведете вашите данни във файла app/config/parameters.yml.

(TODO) _Да се направи възможност за изтегляне на базата и без торент._


4. Настройка на сървъра
=======================

Сега остава да се настрои нов виртуален хост при апача. Добавете това в конфигурацията му:

	<VirtualHost *:80>
		DocumentRoot /PATH/TO/chitanka/web
		ServerName chitanka.local
		<Directory "/PATH/TO/chitanka/web">
			AllowOverride All
			Allow from All
		</Directory>
		LogLevel warn
		ErrorLog /PATH/TO/LOG/chitanka.local.error.log
		CustomLog /PATH/TO/LOG/chitanka.local.access.log common
	</VirtualHost>

На мястото на /PATH/TO запишете съответните директории.

После в /etc/hosts добавете следното:

	127.0.0.1	chitanka.local

Така домейна chitanka.local ще се разпознава от системата ви.


5. Пускане
==========

Ако всичко е минало по план, отворете <http://chitanka.local> и разгледайте вашата версия на Моята библиотека.


6. Помощ с инсталацията
=======================

Ако имате проблеми с инсталацията или пък искате да помогнете за подобряването на тези указания, посетете страницата <http://wiki.chitanka.info/Install>.