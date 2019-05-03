<!DOCTYPE html>
<html>
<head>
    <title>Lindley Examples</title>
</head>
<body>
    <h1>Lindley Examples</h1>
    <ul>
        <?php foreach ( $examples as $example ) : ?>
            <li>
                <a href="<?php echo $example; ?>">
                    <?php echo $example; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
