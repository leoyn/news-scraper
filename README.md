# News Scraper

Scrape news sites and other sites that have a valid rss feed referenced:
```html
<link rel="alternate" type="application/rss+xml" href="https://news.site/feed.rss">
````

## Setup

**Disclaimer: All path are relative to this repository. Mind the `cd news-scraper`.**

1. Install tools
```bash
git clone git@github.com:/leoyn/news-scraper
cd news-scraper

# install python
apt install python3 python3-pip

# install pip dependencies
pip3 install mypysql requests
```


2. Install MySQL (phpmyadmin is optional) and nginx or some other webserver that can run `php`.
```bash
apt install mysql nginx

# look up how to configure nginx hosts and php-fpm
# Copy index.php into your html directory

cp www/index.php /var/www/html/
```
3. Setup user and credentials: Create a user `news` which has at least `SELECT` and `INSERT` access. Now update your password of user `news` in `www/index.php` and `scraper/db.py` according to your database user password of `news`.
4. Import database file `db.sql` using `phpmyadmin` or run `mysql -u user -p news < db.sql`.
5. Setup crontab `crontab -e`. Insert the following code:
```bash
*/20 * * * * cd /path/to/repository/scraper/ && python3 main.py

# This will run checks every news site in linklist.txt every 20 minutes.
# Decrease to lower cpu and bandwidth usage
```
6. (Optional) Change news sites in `scraper/linklist.txt`.
