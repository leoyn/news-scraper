import rssparser
import db

import requests
import re
import urllib.parse

def get_rss_feed_urls(site_url):
    try:
        r = requests.get(site_url, allow_redirects=True)
        expr = '(?:<link (?:[^>]+ )?type="application\/rss\+xml" (?:[^>]+ )?href="([^"]+)"(?:[^>]+)?\/?>)|(?:link (?:[^>]+ )?href="([^"]+)" (?:[^>]+ )?type="application\/rss\+xml"(?:[^>]+)?\/?>)'
        expression = re.compile(expr)

        urls = []

        for url in expression.findall(r.text):
            if len(url[1]) > 0:
                url = url[1]
            else:
                url = url[0]

            if url.startswith("http") == False:
                parsed_site_url = urllib.parse.urlparse(site_url)
                if url.startswith("/"):
                    url = url[1:]

                urls.append(parsed_site_url.scheme + "://" +  parsed_site_url.netloc + parsed_site_url.path + url)
            else:
                urls.append(url)
            print(url)

        print("✅ Success: " + site_url)

        return urls
    except:
        print("❌ Error:   " + site_url)

        return []

def scrape_site(site_url):
    urls = get_rss_feed_urls(site_url)

    for url in urls:
        try:
            r = requests.get(url, allow_redirects=True)
            rssparser.parse(r.text)
        except:
            pass


def scrape_all(filename):
    f = open(filename, "r")
    site_urls = f.read().split("\n")
    f.close()

    for site_url in site_urls:
        if len(site_url) > 0:
            scrape_site(site_url)

scrape_all("linklist.txt")
