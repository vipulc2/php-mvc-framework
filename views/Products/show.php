<h1><?php echo $product["name"]; ?></h1>

<p><?php echo $product["description"]; ?></p>

<p><a href="/products/<?= $product["id"] ?>/edit">Edit</a></p>

<p><a href="/products/<?= $product["id"] ?>/delete">Delete</a></p>


</body>

</html>