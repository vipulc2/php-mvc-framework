

<h1>Products</h1>

<?php

foreach($products as $product) { ?>

    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    <p><?php echo htmlspecialchars($product['description']); ?></p>

<?php

}

?>
    
</body>
</html>