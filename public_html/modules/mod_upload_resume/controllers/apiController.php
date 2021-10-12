<?php

defined('_JEXEC') or die;

class apiController extends nlsCards {

    public function __construct($username_token, $password_token) {
        parent::__construct($username_token, $password_token);
    }

    public function uploadResume($params) {

        $attachments = [];
        $i = 0;


        $dropbox_name = $params['resume-modul-dropbox-fileName'];
        $dropbox_url = $params['resume-modul-dropbox-fileUrl'];
        if(!empty($dropbox_name) && !empty($dropbox_url)) {
            $otherFileContent = file_get_contents($dropbox_url);

            if ($otherFileContent != "") {
                $attachments[$i]['name'] = $dropbox_name;
                $attachments[$i]['content'] = $otherFileContent;
                $i++;
                
                 $filearr = explode(".", $dropbox_name);
                $addNewFileResult = $this->InsertNewFile($otherFileContent, $filearr[0], $filearr[1], "cv");
            }
        }

        $skydrive_name = $params['resume-modul-skydrive-fileName'];
        $skydrive_url = $params['resume-modul-skydrive-fileUrl'];
        
        if(!empty($skydrive_name) && !empty($skydrive_url)) {
            $otherFileContent = file_get_contents($skydrive_url);

            if ($otherFileContent != "") {
                $attachments[$i]['name'] = $skydrive_name;
                $attachments[$i]['content'] = $otherFileContent;
                $i++;
                
                $filearr = explode(".", $skydrive_name);
                $addNewFileResult = $this->InsertNewFile($otherFileContent, $filearr[0], $filearr[1], "cv");
            }
        }

        $local_name = $params["resume-modul-localFile"]["name"];
        $local_url = $params["resume-modul-localFile"]["tmp_name"];
        
        if(!empty($local_name) && !empty($local_url)) {
            $otherFileContent = file_get_contents($local_url);

            if ($otherFileContent != "") {
                $attachments[$i]['name'] = $local_name;
                $attachments[$i]['content'] = $otherFileContent;
                $i++;
                
                  $filearr = explode(".", $local_name);
                $addNewFileResult = $this->InsertNewFile($otherFileContent, $filearr[0], $filearr[1], $params["apply-job-upload-file-type"]);
            }
        }

//        $from_mail = nsoftConfig::$fromMail;
//        $mailto = nsoftConfig::$toMail;
//        $fromName = nsoftConfig::$fromName;
//        $subject = "Name: " . $params["resume-modul-fullname"] . ", ID: " . $params["resume-modul-idnumber"];
//        $message = ""; //$userId;
//
//        $response = $this->sendEmail($from_mail, $mailto, $fromName, $subject, $message, $attachments);
        echo json_encode($addNewFileResult);
        die;
    }

}
