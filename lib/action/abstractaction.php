<?php

namespace Bim\Gui\Action;

abstract class AbstractAction
{
    protected function redirect(string $action, string $message, string $messageType = '')
    {
        LocalRedirect('/bitrix/admin/bim_migrations.php?action=' . $action . '&messageType=' . $messageType . '&message=' .$message);
    }
}
