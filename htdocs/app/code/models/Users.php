<?php

namespace App\Code\Models;

use App\Code\ModelAdapter;
use SebastianBergmann\Exporter\Exception;

class Users extends ModelAdapter
{

    const PBKDF2_HASH_ALGORITHM = "sha256";

    const PBKDF2_ITERATIONS = 1000;

    const PBKDF2_SALT_BYTE_SIZE = 24;

    const PBKDF2_HASH_BYTE_SIZE = 24;

    const HASH_SECTIONS = 4;

    const HASH_ALGORITHM_INDEX = 0;

    const HASH_ITERATION_INDEX = 1;

    const HASH_SALT_INDEX = 2;

    const HASH_PBKDF2_INDEX = 3;

    protected $tableAbbr = 'us';

    public function addUser(array $data = array())
    {
        $this->setData($data);
        if (!$this->selectByEmail($this->getEmail())) {
            $password = $this->getPassword();
            $this->setPassword($this->createHash($password));

            return $this->save();
        }

        return false;
    }

    public function selectByEmail($email = null)
    {
        if (empty($email))
            return null;

        return $this->selectOneBy('email', $email);
    }

    protected function createHash($password = null)
    {
        $passwordSalt = base64_encode(
            mcrypt_create_iv(self::PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM)
        );

        return self::PBKDF2_HASH_ALGORITHM . ":"
        . self::PBKDF2_ITERATIONS . ":" . $passwordSalt . ":" .
        base64_encode(
            $this->pbkdf2(
                self::PBKDF2_HASH_ALGORITHM,
                $password,
                $passwordSalt,
                self::PBKDF2_ITERATIONS,
                self::PBKDF2_HASH_BYTE_SIZE,
                true
            ));
    }

    protected function pbkdf2($algorithm, $password, $salt, $count, $keyLength, $rawOutput = false)
    {
        $algorithm = mb_strtolower($algorithm);
        if (!in_array($algorithm, hash_algos(), true))
            throw new Exception('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
        if ($count <= 0 || $keyLength <= 0)
            throw new Exception('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);

        if (function_exists("hash_pbkdf2")) {
            if (!$rawOutput) {
                $keyLength = $keyLength * 2;
            }

            return hash_pbkdf2($algorithm, $password, $salt, $count, $keyLength, $rawOutput);
        }

        $hashLength = mb_strlen(hash($algorithm, "", true));
        $blockCount = ceil($keyLength / $hashLength);

        $output = "";
        for ($i = 1; $i <= $blockCount; $i++) {
            $last = $salt . pack("N", $i);
            $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
            for ($j = 1; $j < $count; $j++) {
                $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
            }
            $output .= $xorsum;
        }

        if ($rawOutput)
            return substr($output, 0, $keyLength);
        else
            return bin2hex(substr($output, 0, $keyLength));
    }

    public function updateUser(array $data = array())
    {
        if (!empty($data['password'])) {
            $data['password'] = $this->createHash($data['password']);
        } else {
            unset($data['password']);
        }

        $this->setData($data);

        return $this->save();
    }

    public function validateUser($email, $password)
    {
        if (!($user = $this->selectByEmail($email)) ||
            !$this->validatePassword($password, $user->getPassword())
        ) {
            $this->setErrorMessage('Username or password is incorrect!');

            return false;
        }

        return $user;
    }

    protected function validatePassword($password, $correctHash)
    {
        $params = explode(":", $correctHash);
        if (count($params) < self::HASH_SECTIONS)
            return false;
        $pbkdf2 = base64_decode($params[self::HASH_PBKDF2_INDEX]);

        return $this->passwordCompare(
            $pbkdf2,
            $this->pbkdf2(
                $params[self::HASH_ALGORITHM_INDEX],
                $password,
                $params[self::HASH_SALT_INDEX],
                (int)$params[self::HASH_ITERATION_INDEX],
                mb_strlen($pbkdf2),
                true
            )
        );
    }

    protected function passwordCompare($actual, $expected)
    {
        $diff = mb_strlen($actual) ^ mb_strlen($expected);
        for ($i = 0; $i < strlen($actual) && $i < mb_strlen($expected); $i++) {
            $diff |= ord($actual[$i]) ^ ord($expected[$i]);
        }

        return $diff === 0;
    }
}