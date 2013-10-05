<?php

/**
 * Class authenticateGoogleAnalyticsTask runs piGoogleAnalyticsAuthenticator service in CLI
 *
 * @author Jarek Rencz <jrencz@polibuda.info>
 */
class authenticateGoogleAnalyticsTask extends dmContextTask {

  /**
   * @var string namespace
   */
  protected $namespace;

  /**
   * @var string name
   */
  protected $name;

  /**
   * @var string brief description
   */
  protected $briefDescription;

  /**
   * {@inheritdoc}
   */
  public function configure()
  {
    $this->namespace        = 'pi';
    $this->name             = 'authenticate-google-analytics';
    $this->briefDescription = 'Checks if currently stored GA token is valid and revalidates it in case if it\'s not';

    $this->addOptions(
      array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'admin'),
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        new sfCommandOption('user', null, sfCommandOption::PARAMETER_OPTIONAL, 'Google analytics username', 'dev'),
        new sfCommandOption('pass', null, sfCommandOption::PARAMETER_OPTIONAL, 'Google analytics password', 'dev'),
      ));
  }

  /**
   * {@inheritdoc}
   */
  public function execute($arguments = array(), $options = array()) {
    $this->timerStart("GA-auth");
    $this->withDatabase();

    $gaAuth = $this->get('gaAuth');

    if ($options['user'] && $options['pass']) {
      $gaAuth->setCredentials($options['user'], $options['pass']);
    }

    if ($gaAuth->tryToConnect()) {
      $this->logSection("GA-auth", "Token fetched");
    }
    $this->logTimersTotal();
  }
}
