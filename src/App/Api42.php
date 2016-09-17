<?php

namespace App;

use Core\Helpers;

class Api42 {
  const AUTH_URI = "https://api.intra.42.fr/oauth/authorize";
  const TOKEN_URI = "https://api.intra.42.fr/oauth/token";
  const USER_URI = "https://api.intra.42.fr/v2/me";
  const REDIRECT_URI = "http://localhost:3000/sign-42";

  private $uid;
  private $secret;

  public function __construct($uid, $secret) {
    $this->uid = $uid;
    $this->secret = $secret;
  }

  public function authorize() {
    $fields = [
      "client_id" => $this->uid,
      "redirect_uri" => Api42::REDIRECT_URI,
      "response_type" => "code",
      "state" => $state
    ];
    header("Location: ".self::AUTH_URI."?".http_build_query($fields));
    exit;
  }

  public function getAccessToken($code) {
    $fields = [
      "grant_type" => "authorization_code",
      "client_id" => $this->uid,
      "client_secret" => $this->secret,
      "code" => $code,
      "redirect_uri" => self::REDIRECT_URI
    ];
    $cr = curl_init(self::TOKEN_URI);
    curl_setopt($cr, CURLOPT_POST, true);
    curl_setopt($cr, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($cr);
    curl_close($cr);
    if ($res === false)
      return false;
    $data = json_decode($res);
    if (isset($data->access_token))
      return $data->access_token;
    return false;
  }

  public function getUserData($token) {
    $cr = curl_init(self::USER_URI);
    curl_setopt($cr, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$token}"]);
    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($cr);
    curl_close($cr);
    if ($res === false)
      return false;
    $data = json_decode($res);
    if (isset($data->error))
      return false;
    return $data;
  }
}
