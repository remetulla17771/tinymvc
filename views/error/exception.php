<?php /** @var Throwable $e */ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= get_class($e) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f5f5f5; }
        pre { background:#1e1e1e;color:#f1f1f1;padding:15px;border-radius:6px }
        .trace-item { border-left:4px solid #dc3545;padding-left:10px;margin-bottom:10px }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header bg-danger text-white">
            <h4><?= get_class($e) ?></h4>
        </div>

        <div class="card-body">
            <h5 class="text-danger"><?= htmlspecialchars($e->getMessage()) ?></h5>
            <p class="text-muted">
                <?= $e->getFile() ?> : <?= $e->getLine() ?>
            </p>

            <h6>Stack trace:</h6>

            <?php foreach ($e->getTrace() as $i => $trace): ?>
                <div class="trace-item">
                    <strong>#<?= $i ?></strong>
                    <?= $trace['file'] ?? '[internal]' ?>
                    :
                    <?= $trace['line'] ?? '' ?>
                    <br>
                    <?= ($trace['class'] ?? '') . ($trace['type'] ?? '') . ($trace['function'] ?? '') ?>()
                </div>
            <?php endforeach; ?>

            <pre><?= htmlspecialchars($e->getTraceAsString()) ?></pre>
        </div>
    </div>
</div>

</body>
</html>
