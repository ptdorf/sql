<?php
namespace M;


class Sql
{
    private $connection = null;
    private $config = [
        "host"     => "localhost",
        "username" => "my",
        "password" => "secret",
        "database" => "code",
    ];

    public function __construct($host, $username = null, $password = null, $database = null)
    {
        if (is_array($host)) {
            $config = $host;

        } elseif (strpos($host, "mysql://") === 0) {
            $parts = parse_url($host);
            $config["host"] = $parts["host"];
            $config["username"] = $parts["user"];
            $config["password"] = $parts["pass"];
            $config["database"] = trim($parts["path"], "/");

        } else {
            $config["host"] = $host;
            $config["username"] = $username;
            $config["password"] = $password;
            $config["database"] = $database;
        }

        $this->config = $config + $this->config;

        $this->connection = mysqli_connect(
            $this->config["host"],
            $this->config["username"],
            $this->config["password"],
            $this->config["database"]
        );

        if (!$this->connection) {
            throw new \RuntimeException("Failed to connect to " . $this->config["host"]);
        }
    }

    public function read($sql)
    {
        $res = mysqli_query($this->connection, $sql);
        if ($res === false) {
            throw new \RuntimeException(sprintf(
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
            throw new \RuntimeException(sprintf(
                "Failed to query (%s): %s",
                $sql,
                mysqli_error($this->connection)
                )
            );
        }

        return mysqli_affected_rows($this->connection);
    }

}
