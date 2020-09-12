<?php
use M\Sql;

use PHPUnit\Framework\TestCase;


final class SqlTest extends TestCase
{

    protected static $mysql = null;
    protected static $table = null;

    public static function setUpBeforeClass(): void
    {
        self::$table = "test_" . date("Y_m_d_His");
        self::$mysql = new Sql(getenv("TEST_DB"));
    }

    public static function tearDownAfterClass(): void
    {
        $returned = self::$mysql->write("DROP TABLE " . self::$table);
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf("M\Sql", self::$mysql);
    }

    public function testReadDatabases()
    {
        $expected = 0;
        $returned = self::$mysql->read("SHOW DATABASES");
        $this->assertGreaterThan($expected, $returned);
    }

    public function testCreateTable()
    {
        $expected = 0;
        $returned = self::$mysql->write("CREATE TABLE " . self::$table . " (id INT)");
        $this->assertEquals($expected, $returned);
    }

    public function testInsert()
    {
        $expected = 1;
        $returned = self::$mysql->write("INSERT INTO " . self::$table . " VALUES (1)");
        $this->assertEquals($expected, $returned);
    }

    public function testSelect()
    {
        $expected = 1;
        $returned = self::$mysql->read("SELECT * FROM " . self::$table);
        $this->assertEquals($expected, count($returned));
    }

    public function testDelete()
    {
        $expected = 1;
        $returned = self::$mysql->write("DELETE FROM " . self::$table);
        $this->assertEquals($expected, $returned);
    }

    public function testCheck()
    {
        $expected = 0;
        $returned = self::$mysql->read("SELECT * FROM " . self::$table);
        $this->assertEquals($expected, count($returned));
    }

    public function testReadTables()
    {
        $expected = 1;
        $returned = count(self::$mysql->read("SHOW TABLES"));
        $this->assertEquals($expected, $returned);
    }

}
