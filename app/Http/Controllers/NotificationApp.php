<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class NotificationApp extends Controller
{
    public function Send(){


      $ConfigNotification = [
        "tokens" => [
            "cDiWa5w5SEGYL0vFyiQuqd:APA91bG-EsGtwJ6q720hbVoHJ5-KgY6r4QizLjzL3WDzMZ9Wqcy8sQ7COnVDA9rvdwV3MxXVz46BX6WfNX8la1B7N7hNxBjd3A6wwtlzwa1N6TI6Y3uBS0c7mPssugIztcXX3rIjOEIO",
            "cDiWa5w5SEGYL0vFyiQuqd:APA91bG-EsGtwJ6q720hbVoHJ5-KgY6r4QizLjzL3WDzMZ9Wqcy8sQ7COnVDA9rvdwV3MxXVz46BX6WfNX8la1B7N7hNxBjd3A6wwtlzwa1N6TI6Y3uBS0c7mPssugIztcXX3rIjOEIO"
        ],

        "tittle" => "Para Leo, de Camila",
        "body"   => "Nesecito Verte, Respondeme",
        "data"   => ['type' => 'refferers']

      ];

      $code = SendNotifications($ConfigNotification);

      echo "aassa";

    }
}
