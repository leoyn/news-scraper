import pymysql

connection = pymysql.connect("localhost", "news", "<password>", "news")

def save_article(article):
    try:
        with connection.cursor() as cursor:
            sql = "INSERT INTO article (guid, title, description, url, publicationDate) VALUES (%s, %s, %s, %s, %s)"
            cursor.execute(sql, (article["guid"], article["title"], article["description"], article["url"], article["publicationDate"].strftime("%Y-%m-%d %H:%M:%S")))

        connection.commit()
    except:
        pass
