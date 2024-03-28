<?php
require_once '../admin.php';

echo '<h2>Журнал аудита</h2>';

$audit = getAudit();

if(empty($audit)) {
    echo 'Журнал пуст';
} else {
    foreach ($audit as $log) {
        $admin = getAdminById($log['admin_id']);
        $details = getAuditDetailsByLogId($log['id']);
        echo '
        <div class="log-entry">
            <p>
                <span><b>' . $log['id'] . '</b></span>
                <span>' . $admin['username'] . '</span>
                <span>' . $log['action'] . '</span>
                <span>' . $log['timestamp'] . '</span>
                <button class="details-btn">Показать подробности</button>
            </p>
            <div class="details" style="display: none;">
                <table class="change-table">
                    <tr>
                        <th>Before</th>
                        <th>After</th>
                    </tr>
                    <tr class="change-row">
                        <td>' . $details['old_value'] . '</td>
                        <td>' . $details['new_value'] . '</td>
                    </tr>
                </table>
            </div>
        </div>';
    }

}

?>
<style>
    <?php include '../style.css';?>
</style>
<script>
    <?php include '../audit.js';?>
</script>
