<?php
namespace AmiSMB\BBKDisplay\Helpers;

use DateTime;
use Psr\Log\LogLevel;

/**
 * Class LogHelper
 */
class LogHelper
{
  private $_logFile;
  private $_prefix;
  private $_logLevel = LogLevel::INFO;
  private static $_levels = [
    LogLevel::EMERGENCY,
    LogLevel::ALERT,
    LogLevel::CRITICAL,
    LogLevel::ERROR,
    LogLevel::WARNING,
    LogLevel::NOTICE,
    LogLevel::INFO,
    LogLevel::DEBUG,
  ];

  const CONSOLE = 1;
  const STDERR = 2;

  /**
   * Construct the LogHelper object setting logfile and prefix creation
   *
   * @param string $logFile The absolute path to the log file
   * @param string $prefix  A prefix to put on every log message
   */
  public function __construct($logFile, $prefix = "")
  {
    $this->_logFile = $logFile;
    $this->setPrefix($prefix);
  }

  /**
   * Set the log level
   *
   * @param $logLevel
   */
  public function setLevel($logLevel)
  {
    $this->_logLevel = $logLevel;
  }

  /**
   * Sets the prefix to use
   *
   * @param $prefix
   */
  public function setPrefix($prefix)
  {
    $this->_prefix = $prefix;
  }

  /**
   * Sets the log file to use
   *
   * @param $logFile
   */
  public function setLogFile($logFile)
  {
    $this->_logFile = $logFile;
  }

  /**
   * Output a line depending upon the log level to the correct log file
   *
   * @param $line
   * @param $logLevel
   */
  private function _outputLine($line, $logLevel)
  {
    if($this->_logFile == LogHelper::CONSOLE)
    {
      switch($logLevel)
      {
        case LogLevel::ALERT:
        case LogLevel::CRITICAL:
        case LogLevel::ERROR:
        case LogLevel::EMERGENCY:
          $stderr = fopen('php://stderr', 'w');
          fputs($stderr, $line);
          fputs($stderr, "\n");
          fclose($stderr);
          break;
        default:
          echo $line, "\n";
          break;
      }
    }
    else if($this->_logFile == LogHelper::STDERR)
    {
      error_log($line);
    }
    else if($fp = fopen($this->_logFile, 'a'))
    {
      fputs($fp, $line);
      fputs($fp, "\n");
      fclose($fp);
    }
  }

  /**
   * Sets the log levels that are allowed to be output
   *
   * @param $level
   *
   * @return bool
   */
  protected function _levelAllowed($level)
  {
    return array_search($level, self::$_levels) <=
      array_search($this->_logLevel, self::$_levels);
  }

  /**
   * Outputs the log message only if this log level is allowed
   * Adds set prefix and timestamp with nanosecond granularity
   * along with log level and message
   *
   * @param $logLevel
   * @param $msg
   */
  public function write($logLevel, $msg)
  {
    if($this->_levelAllowed($logLevel))
    {
      $now = DateTime::createFromFormat(
        'U.u',
        sprintf("%.6F", microtime(true))
      );
      $prefix = '[' . $now->format('D M d H:i:s.v Y') . '] ';
      if($this->_prefix != "")
      {
        $prefix .= $this->_prefix . ' ';
      }

      $prefix .= strtoupper($logLevel . ': ');

      $this->_outputLine(
        $prefix . rtrim(str_replace("\n", '\n', $msg)),
        $logLevel
      );
    }
  }

  /**
   * Outputs info log level message
   *
   * @param $msg
   */
  public function info($msg)
  {
    $this->write(LogLevel::INFO, $msg);
  }

  /**
   * Outputs error log level message
   *
   * @param $msg
   */
  public function error($msg)
  {
    $this->write(LogLevel::ERROR, $msg);
  }

  /**
   * Outputs a debug log level message
   *
   * @param $msg
   */
  public function debug($msg)
  {
    $this->write(LogLevel::DEBUG, $msg);
  }

  /**
   * Formats PHP exception log level with error code, message, filename
   * and stack trace for output into a log file
   *
   * @param \Exception $e
   */
  public function exception(\Exception $e)
  {
    $this->write(
      LogLevel::ERROR,
      'EXCEPTION: Code - [' . $e->getCode() . '], Message - '
      . $e->getMessage() . ', File - (' . $e->getFile() . ':' . $e->getLine() . ')'
      . PHP_EOL . ', Trace - ' . str_replace("\n", PHP_EOL, $e->getTraceAsString())
    );
  }
}
