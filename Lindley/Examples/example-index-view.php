<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <style>
        span.go-back {
            font-size: 0.6em;
        }
    </style>
</head>
<body>
    <h1>
        Lindley Examples
        <span class="go-back">
            <a href="../">index</a>
        </span>
    </h1>
    <h2><?php echo $header; ?></h2>
    <div id="index-examples">
        <?php foreach ( $examples as $ix => $example ) : ?>
            <div class="example">
                <a href="?exampleNo=<?php echo ++$ix; ?>">
                    <?php echo $example['description']; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ( ! empty( $afterIndexHTML ) ) : ?>
        <div id="after-index">
            <?php echo $afterIndexHTML; ?>
        </div>
    <?php endif; ?>
</body>
</html>
