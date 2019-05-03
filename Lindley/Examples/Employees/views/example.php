<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <style>
        span.go-back {
            font-size: 0.6em;
        }

        #examples {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #examples td, #examples th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #examples tr:nth-child(even){background-color: #f2f2f2;}

        #examples tr:hover {background-color: #ddd;}

        #examples th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
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
    <h2>
        Chicago Employees
        <span class="go-back">
            <a href="?">index</a>
        </span>
    </h2>
    <h3><?php echo $exampleData['description']; ?></h3>

    <div id="examples">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Job titles</th>
                    <th>Department</th>
                    <th>Full or part time</th>
                    <th>Salary or hourly</th>
                    <th>Typical hours</th>
                    <th>Annual salary</th>
                    <th>Hourly rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $results as $ix => $result ) : ?>
                    <tr>
                        <td><?php echo ++$ix; ?></td>
                        <?php foreach ( $result as $key => $value ) : ?>
                            <td><?php echo $value; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php require( __DIR__ . '/disclaimer.php' ); ?>
</body>
</html>
