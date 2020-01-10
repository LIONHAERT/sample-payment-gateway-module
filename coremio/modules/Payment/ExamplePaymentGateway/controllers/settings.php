<?php
    if(!defined("CORE_FOLDER")) die();

    $lang           = $module->lang;
    $config         = $module->config;

    Helper::Load(["Money"]);

    $field1               = Filter::POST("field1"); // $_POST["field1"];
    $field2               = Filter::POST("field2"); // $_POST["field2"];


    $sets           = [];

    if($field1 != $config["settings"]["field1"])
        $sets["settings"]["field1"] = $field1;

    if($field2 != $config["settings"]["field2"])
        $sets["settings"]["field2"] = $field2;


    if($sets){
        $config_result  = array_replace_recursive($config,$sets);
        $array_export   = Utility::array_export($config_result,['pwith' => true]);

        $file           = dirname(__DIR__).DS."config.php";
        $write          = FileManager::file_write($file,$array_export);

        $adata          = UserManager::LoginData("admin");
        User::addAction($adata["id"],"alteration","changed-payment-module-settings",[
            'module' => $config["meta"]["name"],
            'name'   => $lang["name"],
        ]);
    }

    echo Utility::jencode([
        'status' => "successful",
        'message' => $lang["success1"],
    ]);