<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<title>News</title>
	</head>
	<body>
<?php

	function sprint($text) {
		echo htmlspecialchars($text);
	}

	$db = new mysqli("127.0.0.1", "news", "<password>", "news");

	$page = intval($_GET["page"]);
	$searchQuery = $_GET["query"];

	if($page < 1) $page = 1;

	$articlesPerPage = 50;

	$limit = $articlesPerPage * $page;
	$offset = $articlesPerPage * ($page - 1);

	$selector = "WHERE MATCH(title, description) AGAINST(? IN BOOLEAN MODE) OR ? = ''";

	$query = $db->prepare("SELECT title, description, url, publicationDate FROM article $selector ORDER BY publicationDate DESC LIMIT ? OFFSET ?");

	$query->bind_param("ssii", $searchQuery, $searchQuery, $limit, $offset);
	$query->bind_result($title, $description, $url, $publicationDate);
	$query->execute();

?>
	<form method="GET">
		<input type="search" value="<?php sprint($searchQuery) ?>" name="query">
		<input type="submit" value="Suchen">
	</form>
<?php
	while($query->fetch()) {
		$datetime = DateTime::createFromFormat("Y-m-d H:i:s", $publicationDate);
?>
	<div style="border: 1px solid #000; margin: 10px; padding: 5px 20px">
		<h3><?php sprint($title) ?></h3>
		<img src="<?php sprint(parse_url($url, PHP_URL_SCHEME)) ?>://<?php sprint(parse_url($url, PHP_URL_HOST)) ?>/favicon.ico" style="width: 15px; height: 15px">
		<a style="max-width: 250px; display: inline-block; white-space: nowrap; text-overflow: ellipsis; overflow: hidden" href="<?php sprint($url) ?>"><?php print($url) ?></a>
		<p style="white-space: pre-line;overflow-wrap: break-word;"><?php sprint($description) ?></p>
		<p style="font-size: 15px; font-weight: bold"><?php sprint($datetime->format("H:i:s") . " Uhr - " . $datetime->format("d.m.Y")) ?></p>
	</div>
<?php
	}

	$query = $db->prepare("SELECT COUNT(guid) FROM article $selector");
	$query->bind_param("ss", $searchQuery, $searchQuery);
	$query->bind_result($itemCount);

	$query->execute();
	$query->fetch();

	$pageCount = ceil($itemCount / 50);
?>
	<hr>
	<p><?php echo $itemCount ?> Artikel gefunden</p>
	<p>Seite <?php echo $page ?> von <?php echo $pageCount ?></p>
<?php
	if($page > 1) {
?>
	<a href="?query=<?php sprint($searchQuery) ?>&page=<?php echo $page - 1 ?>"> << Vorrige Seite</a>
<?php
	}

	if($pageCount > $page) {
?>
        <a href="?query=<?php sprint($searchQuery) ?>&page=<?php echo $page + 1 ?>">Nächste Seite >></a>
<?php
	}
?>
	</body>
</html>