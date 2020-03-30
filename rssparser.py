import db

import xml.etree.ElementTree as ET
import dateutil.parser
import _thread

def parse(text):
    tree = ET.ElementTree(ET.fromstring(text))
    root = tree.getroot()

    for item in root.iter("item"):
        publicationDate = dateutil.parser.parse(item.find("pubDate").text)

        article = {
            "guid": item.find("guid").text,
            "title": item.find("title").text,
            "description": item.find("description").text,
            "url": item.find("link").text,
            "publicationDate": publicationDate
        }

        db.save_article(article)
