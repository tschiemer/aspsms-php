<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ASPSMS PHP Library Adapter for CodeIgniter - DEMO</title>
        <?php if ($redirect): ?>
        <meta http-equiv="refresh" content="<?php echo $redirect; ?>;URL='<?php echo site_url('aspsms_demo'); ?>'" />
        <?php endif; ?>
    </head>
    <body>
        <div style="margin:15px;border: 5px solid lightgreen; padding: 10px">
            <ul style="list-style: none; margin: 0px; padding: 0px;">
                <?php foreach($messages as $message): ?>
                <li style="margin: 0px;"><?php echo $message; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <p>
            <a href="<?php echo site_url('aspsms_demo'); ?>">Back to overview</a>
        </p>
    </body>
</html>