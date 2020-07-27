<?php
namespace M;

use PHPUnit\Framework\TestCase;


final class SqlTest extends TestCase
{

    protected static $db = null;
    protected static $tempDB = null;

    public static function setUpBeforeClass(): void
    {
        self::$tempDB = "test_" . date("Y_m_d_His");
        self::$db = new Sql();
        self::$db->connect("localhost", "root", "", "");
        self::$db->write("DROP DATABASE IF EXISTS " . self::$tempDB);
        self::$db->write("CREATE DATABASE " . self::$tempDB);
    }

    public static function teardownAfterClass(): void
    {
        self::$db->write("DROP DATABASE IF EXISTS " . self::$tempDB);
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf("M\Sql", self::$db);
    }

    public function testRead(): void
    {
        $expected = [["Database (mysql)" => "mysql"]];
        $returned = self::$db->read("SHOW DATABASES LIKE 'mysql'");
        $this->assertEquals($expected, $returned);
    }

    public function testWriteTempTable()
    {
        $expected = 0;
        $returned = self::$db->write("CREATE TABLE " . self::$tempDB . ".tempTable (id INT UNSIGNED NOT NULL PRIMARY KEY)");
        $this->assertEquals($expected, $returned);
    }

}
