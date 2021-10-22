<?php

use FOF30\Factory\SwitchFactory;

defined('_JEXEC') or die;
class apiController extends nlsCards {

    public function __construct($username_token, $password_token) {
        parent::__construct($username_token, $password_token);
        JLog::addLogger(
            array(
                 // Sets file name
                 'text_file' => 'colman.log.php'
            ),
            // Sets messages of all log levels to be sent to the file.
            JLog::ALL,
            // The log category/categories which should be recorded in this file.
            // In this case, it's just the one category from our extension.
            // We still need to put it inside an array.
            array('colman')
        );
    }

    public function downloadFileAction($param) {
        $result = $this->getFileInfo($param["fileId"], $this->user_id, true);
        $name = $result->FileGetByFileIdResult->Name;
        $content = $result->FileGetByFileIdResult->FileContent;
        $size = $result->FileGetByFileIdResult->Size;
        $type = trim($result->FileGetByFileIdResult->Type);

        if (is_null($size)) {
            if (function_exists('mb_strlen')) {
                $size = mb_strlen($content, '8bit');
            } else {
                $size = strlen($content);
            }
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $name . "." . $type . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        header('Content-Length: ' . $size);

        echo $content;
        exit;
    }

    public function myAction($params) {
        $component = $params['component'];
        if ($component) return $this->getComponentData($component);
        die;
    }

    public function getComponentData($component) {
        switch ($component) {
            case 'resumeCount':
                $result = $this->resumeGetCount($this->user_id);
                break;
            case 'applayedJobsCount':
                $result = $this->applayedJobGetCount($this->user_id);
                break;
            case 'fileCount':
                $result = $this->fileGetCount($this->user_id);
                break;
            case 'newJobsCount':
                $result = $this->newJobsGetCount($this->user_id);
                break;
            case 'totalJobsCount':
                $result = $this->totalJobsCount($this->user_id);
                break;
            default:
                # code...
                break;
        }
        echo json_encode($result);
        die;
    }

    public function getJobContentAction($params) {
        $from = intval($params['lastId']) * intval($params['countPerPage'] - 1) + 1;
        $to = $from + intval($params['countPerPage'] - 1);

        if ($params['cont_type'] === "filled-jobs") {
            $start = microtime(true);
            JLog::add('filled-jobs: start: ' . $start, JLog::INFO, 'colman');
            $listData = $this->getFilledJobsList($this->user_id, $from, $to);
            $end = microtime(true);
            JLog::add('filled-jobs: end: ' . $end, JLog::INFO, 'colman');
            JLog::add('filled-jobs: end: ' . ($end - $start), JLog::INFO, 'colman');
        } elseif ($params['cont_type'] === "new-jobs") {
            $start = microtime(true);
            JLog::add('new-jobs: start: ' . $start, JLog::INFO, 'colman');
            $listData = $this->getNewJobsList($params['lastId'] * ($params['countPerPage'] - 1), $params['countPerPage']);
            $end = microtime(true);
            JLog::add('new-jobs: end: ' . $end, JLog::INFO, 'colman');
            JLog::add('new-jobs: end: ' . ($end - $start), JLog::INFO, 'colman');
        } elseif ($params['cont_type'] === "cv") {
            $start = microtime(true);
            JLog::add('cv: start: ' . $start, JLog::INFO, 'colman');
            $listData = $this->getCvList($this->user_id);
            $end = microtime(true);
            JLog::add('cv: end: ' . $end, JLog::INFO, 'colman');
            JLog::add('cv: end: ' . ($end - $start), JLog::INFO, 'colman');
        } elseif ($params['cont_type'] === "files") {
            $start = microtime(true);
            JLog::add('files: start: ' . $start, JLog::INFO, 'colman');
            $listData = $this->getFiles($this->user_id, $from, $to);
            $end = microtime(true);
            JLog::add('files: end: ' . $end, JLog::INFO, 'colman');
            JLog::add('files: end: ' . ($end - $start), JLog::INFO, 'colman');
        }
        JLog::add('---------------', JLog::INFO, 'colman');

        echo json_encode($listData);
        die;
    }

    public function deleteFileAction($param) {

        $this->fileDelete($param["fileId"], $this->user_id);
        if ($param["cont_type"] == "cv") {
            $response = $this->resumeGetCount($this->user_id);
        } else {
            $response = $this->fileGetCount($this->user_id);
        }

        echo json_encode($response);
        die;
    }

    public function uploadFileAction($params) {
        $fileContent = file_get_contents($params['uploadFile']['tmp_name']);
        $filename = pathinfo($params['uploadFile']['name'], PATHINFO_FILENAME);
        $filetype = pathinfo($params['uploadFile']['name'], PATHINFO_EXTENSION);

        $response = $this->InsertNewFile($fileContent, $filename, $filetype, $params['isCV']=="true"?"cv":"file");
        echo json_encode($response);
        die;
    }

    public function getJobDescription($params) {
        $listData = $this->jobGetById($params['jobId']);
        
        echo json_encode($listData);
        die;
    }

}
