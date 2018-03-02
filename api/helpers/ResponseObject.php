<?php

namespace api\helpers;

/**
 * Class ResponseObject
 * @package api\helpers
 */
class ResponseObject
{
    /**
     * @var
     */
    public $result;
    /**
     * @var int
     */
    private $status = 1;
    /**
     * @var string
     */
    private $message = '';
    /**
     * @var array
     */
    private $obj = ['error' => []];

    /**
     * ResponseObject constructor.
     */
    public function __construct()
    {
        $this->result['status'] = $this->status;
        $this->result['message'] = $this->message;
        $this->result['obj'] = $this->obj;
    }

    /**
     * @param $msg
     */
    public function setMessage($msg)
    {
        $this->result['status'] = 0;
        $this->result['message'] = $msg;
    }

    /**
     * @param $errors
     */
    public function setErrors($errors)
    {
        $this->result['status'] = 0;

        if (is_array($errors)) {
            foreach ($errors as $error) {
                $this->message .= implode(', ', $error) . ' ';
            }
        } else {
            $this->message = $errors;
        }

        $this->result['message'] = $this->message;
        $this->result['obj']['error'] = $errors;
    }

    /**
     * @param $error
     */
    public function setError($error)
    {
        $this->result['status'] = 0;
        $this->result['message'] = $error;
        $this->result['obj']['error'][] = $error;
    }

    /**
     * @param $name
     * @param $errors
     */
    public function setErrorByName($name, $errors)
    {
        $this->result['status'] = 0;

        if (is_array($errors)) {
            foreach ($errors as $error) {
                $this->message .= implode(', ', $error) . ' ';
            }
        } else {
            $this->message = $errors;
        }

        $this->result['message'] = $this->message;
        $this->result['obj']['error'][$name][] = $errors;
    }

    /**
     * @param $data
     * @param bool $int
     */
    public function addData($data, $int = false)
    {
        if (!$int) {
            $data = $data ?: [];
        }

        $this->result['obj']['items'] = $data;
    }

    /**
     * @param $name
     * @param $data
     */
    public function setDataByName($name, $data)
    {
        $this->result['obj']['items'][$name][] = $data ?: [];
    }

    /**
     * @param $name
     * @param $data
     */
    public function setDataAsObjectByName($name, $data)
    {
        $this->result['obj']['items'][$name] = $data ?: [];
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->result['obj']['error']);
    }
} 