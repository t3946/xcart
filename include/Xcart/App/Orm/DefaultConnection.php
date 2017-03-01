<?php
namespace Xcart\App\Orm;

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Connection as DBALConnection;
use Xcart\App\Main\Xcart;

class DefaultConnection extends DBALConnection
{
//
//    /**
//     * {@inheritdoc}
//     */
//    public function connect()
//    {
//        try {
//            return parent::connect();
//        }
//        catch (DBALException $e) {
//            $this->processException($e);
//        }
//
//        return null;
//    }

    /**
     * {@inheritdoc}
     */
    public function delete($tableExpression, array $identifier, array $types = array())
    {
        try {
            return parent::delete($tableExpression, $identifier, $types);
        }
        catch (DBALException $e) {
            $this->processException($e);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function update($tableExpression, array $data, array $identifier, array $types = array())
    {
        try {
            return parent::update($tableExpression, $data, $identifier, $types);
        }
        catch (DBALException $e) {
            $this->processException($e);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function insert($tableExpression, array $data, array $types = array())
    {
        try {
            return parent::insert($tableExpression, $data, $types);
        }
        catch (DBALException $e) {
            $this->processException($e);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement)
    {
        try {
            return parent::prepare($statement);
        }
        catch (DBALException $e) {
            $this->processException($e, $statement);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function executeQuery($query, array $params = array(), $types = array(), QueryCacheProfile $qcp = null)
    {
        try {
            return parent::executeQuery($query, $params, $types, $qcp);
        }
        catch (DBALException $e) {
            $this->processException($e);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function executeCacheQuery($query, $params, $types, QueryCacheProfile $qcp)
    {
        try {
            return parent::executeCacheQuery($query, $params, $types, $qcp);
        }
        catch (DBALException $e) {
            $this->processException($e);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $args = func_get_args();

        try {
            return call_user_func_array('parent::query', func_get_args());
        }
        catch (DBALException $e) {
            $this->processException($e, (!empty($args[0])?$args[0]:''));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function executeUpdate($query, array $params = array(), array $types = array())
    {
        try {
            return parent::executeUpdate($query, $params, $types);
        }
        catch (DBALException $e) {
            $this->processException($e, $query);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function exec($statement)
    {
        try {
            return parent::exec($statement);
        }
        catch (DBALException $e) {
            $this->processException($e, $statement);
        }

        return null;
    }

    /**
     * @param DBALException $exception
     * @param string $query
     */
    public function processException($exception, $query = '')
    {
        $msg = '';

        if (Xcart::app()->getIsWebMode()) {

            $login = Xcart::app()->request->session->get('login');

            $msg .= "Site        : ".(($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER["HTTP_HOST"]. $_SERVER['REQUEST_URI']."\n";
            $msg .= "Remote IP   : {$_SERVER['REMOTE_ADDR']}\n";
            $msg .= "Logged as   : {$login}\n";
        }

        if (!empty($query)) {

            $msg .= "SQL query   : {$query}\n";
        }

        $msg .= "Error code  : ".$exception->getCode()."\n";
        $msg .= "Description : ".$exception->getMessage() ."\n\n";
        $msg .= "Backtrace: \n";
        $msg .= $exception->getTraceAsString();

        $oMail = Xcart::app()->mail;
        $oMail->to = 'team@s3stores.com';
        $oMail->from = ('team@s3stores.com');
        $oMail->subject = 'S3 Stores, Inc.: SQL error notification';
        $oMail->body = $msg;
        $oMail->sendEmail();

        x_log_add('SQL', $msg);
    }
}