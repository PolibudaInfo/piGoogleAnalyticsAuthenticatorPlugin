<?php

/**
 * Class piGoogleAnalyticsAuthenticator handles automated Google Analytics authentication
 * by fetching fresh GA token
 * It makes a request based on Google user and password set in constructor or fetched from config
 *
 * @author Jarek Rencz <jrencz@polibuda.info>
 */
class piGoogleAnalyticsAuthenticator {

  /**
   * @var dmGapi
   */
  private $gAPI;

  /**
   * @var string
   */
  private $user;

  /**
   * @var string
   */
  private $pass;

  /**
   * @var bool
   */
  private $gapiConnected;

  /**
   * @param dmGapi        $gAPI
   * @param string|null   $user
   * @param string|null   $pass
   */
  public function __construct($gAPI, $user = null, $pass = null)
  {
    $this->gAPI = $gAPI;

    $this->setCredentials($user, $pass);
  }

  /**
   * @param string|null $user
   * @param string|null $pass
   *
   * @return $this
   */
  public function setCredentials($user = null, $pass = null)
  {
    $this->user = ($user) ? $user : sfConfig::get('app_ga_user');
    $this->pass = ($pass) ? $pass : sfConfig::get('app_ga_pass');

    return $this;
  }

  /**
   * Próbuje autentykować po tokenie, jeśli nie ma tokenu, to próbuje zalogować używając nazwy i hasła
   *
   * @return bool
   */
  public function tryToConnect()
  {
    $this->gapiConnected = false;

    try
    {
      $this->gAPI->authenticate(null, null, dmConfig::get('ga_token'));
      $this->gapiConnected = true;
    }
    catch(dmGapiException $e)
    {
      try {
        dmConfig::set(
          'ga_token',
          $this->gAPI->authenticate(
            $this->user,
            $this->pass
          )->getAuthToken()
        );

        $this->gapiConnected = true;
      }
      catch(dmGapiException $e)
      {
        // bad token & bad fallback user/pass
      }
    }

    return $this->gapiConnected;
  }
}
