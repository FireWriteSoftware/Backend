<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use mysqli;

class EnvironmentController extends BaseController
{
    public function update_mysql(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'hostname' => 'required',
            'port' => 'required|integer|min:1|max:99999',
            'database' => 'required',
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        try {
            $tmp_con = new mysqli($input['hostname'], $input['username'], $input['password'], $input['database'], $input['port']);

            if ($tmp_con->connect_error) {
                return $this->sendError('Error while connecting to the database.', [
                    'db_response' => $tmp_con->connect_error
                ]);
            }

            mysqli_close($tmp_con);
            $tmp_con = null;
        } catch (Exception $e) {
            return $this->sendError('Error while connecting to the database.', [
                'db_response' => $tmp_con->connect_error
            ]);
        }

        // HOSTNAME
        config(['database.connections.mysql.host' => $input['hostname']]);
        $this->putPermanentEnv('DB_HOST', $input['hostname']);

        // PORT
        config(['database.connections.mysql.port' => $input['port']]);
        $this->putPermanentEnv('DB_PORT', $input['port']);

        // DATABASE
        config(['database.connections.mysql.database' => $input['database']]);
        $this->putPermanentEnv('DB_DATABASE', $input['database']);

        // USERNAME
        config(['database.connections.mysql.username' => $input['username']]);
        $this->putPermanentEnv('DB_USERNAME', $input['username']);

        // PASSWORD
        config(['database.connections.mysql.password' => $input['password']]);
        $this->putPermanentEnv('DB_PASSWORD', $input['password']);

        return $this->sendResponse([
            "config" => config('database.connections.mysql'),
        ], "Successfully changed mysql connection");
    }

    public function update_mail(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'hostname' => 'required',
            'port' => 'required|integer|min:1|max:99999',
            'username' => 'required',
            'password' => 'required',
            'encryption' => 'required',
            'from_address' => 'required',
            'from_name' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        // MAILER
        config(['mail.mailers.smtp.host' => $input['hostname']]);
        $this->putPermanentEnv('MAIL_HOST', $input['hostname']);

        // PORT
        config(['mail.mailers.smtp.port' => $input['port']]);
        $this->putPermanentEnv('MAIL_PORT', $input['port']);

        // USERNAME
        config(['mail.mailers.smtp.username' => $input['username']]);
        $this->putPermanentEnv('MAIL_USERNAME', $input['username']);

        // PASSWORD
        config(['mail.mailers.smtp.password' => $input['password']]);
        $this->putPermanentEnv('MAIL_PASSWORD', $input['password']);

        // ENCRYPTION
        config(['mail.mailers.smtp.encryption' => $input['encryption']]);
        $this->putPermanentEnv('MAIL_ENCRYPTION', $input['encryption']);

        // FROM MAIL
        config(['mail.from.address' => $input['from_address']]);
        $this->putPermanentEnv('MAIL_FROM_ADDRESS', $input['from_address']);

        // FROM NAME
        config(['mail.from.name' => $input['from_name']]);
        $this->putPermanentEnv('MAIL_FROM_NAME', $input['from_name']);

        return $this->sendResponse([
            "config" => config('mail'),
        ], "Successfully changed mail settings");
    }

    function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('='.env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}
