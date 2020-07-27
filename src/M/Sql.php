<?php
namespace M;

use RuntimeException;


class Sql
{
    private $connection = null;

    public function connect($host, $username = null, $password = null, $database = null)
    {
        $this->connection = mysqli_connect($host, $username, $password, $database);
        if (!$this->connection) {
            throw new RuntimeException("Failed to connect");
        }
    }

    public function read($sql)
    {
        $res = mysqli_query($this->connection, $sql);
        if ($res === false) {
            throw new RuntimeException(sprintf(
                "Failed to query (%s): %s",
                $sql,
                mysqli_error($this->connection)
                )
            );
        }
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    public function write($sql)
    {
        $res = mysqli_query($this->connection, $sql);
        if ($res === false) {
            throw new RuntimeException(sprintf(
                "Failed to query (%s): %s",
                $sql,
                mysqli_error($this->connection)
                )
            );
        }

        return mysqli_affected_rows($this->connection);
    }

}
