<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ASPSMS PHP Library Adapter for CodeIgniter - DEMO</title>
        <?php if (empty($status)): ?>
        <meta http-equiv="refresh" content="2;URL='<?php echo current_site(); ?>'" />
        <?php endif; ?>
        <style>
        td {
            vertical-align: top;
            font-family: monospace;
        }
        </style>
    </head>
    <body>
        <div style="margin:15px;border: 5px solid lightgreen; padding: 10px">
            <table cellspacing=15>
                <thead>
                    <tr>
                        <th>Reference Nr</th>
                        <th>Status</th>
                        <th>SubmissionDate</th>
                        <th>DeliveryDate</th>
                        <th>Reason</th>
                        <th>Other</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($status)): ?>
                    <tr>
                        <td colspan="5">No Delivery information for tracking-nr <em><?php echo $trackingnr; ?></em></td>
                    </tr>
                    <?php else: foreach($status as $stat): ?>
                    <tr>
                        <td><em><?php echo $stat['nr']; ?></em></td>
                        <td>
                            <em><?php echo $stat['status']; ?></em><br/>
                            <b><?php echo lang('delivery_'.intval($stat['status']),'asdf'); ?></b>
                        </td>
                        <td>
                            <em><?php echo $stat['submissionDate']; ?></em><br/>
                            <b><?php
                            $dt = DateTime::createFromFormat("dmYHis", $stat['submissionDate']);
                            echo $dt->format(DateTime::ATOM);
                            ?></b>
                        </td>
                        <td>
                            <em><?php echo $stat['deliveryDate']; ?></em><br/>
                            <b><?php
                            $dt = DateTime::createFromFormat("dmYHis", $stat['deliveryDate']);
                            echo $dt->format(DateTime::ATOM);
                            ?></b>
                        </td>
                        <td>
                            <em><?php echo $stat['reason']; ?></em><br/>
                            <b><?php
                            switch (intval($stat['status']))
                            {
                                case 0: // delivered
                                case 1: // buffered
                                    break;
                                
                                case -1: // pending / rejected
                                case 2: // not delivered
                                    echo lang('reason_'.intval($stat['reason']));
                            }
                            ?></b>
                        </td>
                        <td><em><?php echo $stat['other']; ?></em></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <p>
            <a href="<?php echo site_url('aspsms_demo'); ?>">Back to overview</a>
        </p>
    </body>
</html>