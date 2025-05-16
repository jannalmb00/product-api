<?php

namespace App\Models;

use App\Core\PDOService;
use App\Helpers\DateTimeHelper;

class AccessModel extends BaseModel
{

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    // TODO: impemnt a public method that recieves a log message and insert in to the DB table
    //* NOTE: @see: the structure of the ws_table that was included in the .zip file

    // Log records must include information about the acc used to access the resource, IP address, resource URI HTTP method used date and time, etc.

    public function insertLog(array $logData): mixed
    {  //        echo "7 GOES HERE";

        if (!isset($logData['logged_at'])) {
            $logData['logged_at'] = DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M_S);
          //  dd( $logData['logged_at'] );

        }
        return $this->insert('ws_log', $logData);
    }

}
