<?php
namespace backend/commands;

class ExchangeJobController extends Controller
{
    public function actionUpdateExchange()
    {
        // Your cron job logic here
        echo "Cron job running...\n";

        return ExitCode::OK;
    }
}
?>