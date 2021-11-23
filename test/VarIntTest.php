<?php
use Muvon\KISS\VarInt;
use PHPUnit\Framework\TestCase;

class VarIntTest extends TestCase {
  public function testReadUintFromHex() {
    $hex = '80897a';
    $this->assertEquals(
      [2000000, 3],
      VarInt::readUint(hex2bin($hex))
    );

    $hex = 'dde5b31880897a';
    $this->assertEquals(
      [51180253, 4],
      VarInt::readUint(hex2bin($hex))
    );

    $hex = 'd5c7c8a1a6ca9ee4bdab04';
    $this->assertEquals(
      ['5123423423180343223253', 11],
      VarInt::readUint(hex2bin($hex))
    );
  }

  public function testPackUintToHex() {
    $value = 2000000;
    $this->assertEquals(
      hex2bin('80897a'),
      VarInt::packUint($value)
    );

    $value = 51180253;
    $this->assertEquals(
      hex2bin('dde5b318'),
      VarInt::packUint($value)
    );

    $value = '5123423423180343223253';
    $this->assertEquals(
      hex2bin('d5c7c8a1a6ca9ee4bdab04'),
      VarInt::packUint($value)
    );
  }

  public function testReadIntFromHex() {
    $hex = 'ff91f401';
    $this->assertEquals(
      [-2000000, 4],
      VarInt::readInt(hex2bin($hex))
    );

    $hex = 'f601';
    $this->assertEquals(
      [123, 2],
      VarInt::readInt(hex2bin($hex))
    );

    $hex = '04';
    $this->assertEquals(
      [2, 1],
      VarInt::readInt(hex2bin($hex))
    );
  }

  public function testPackIntTohex() {
    $value = -2000000;
    $this->assertEquals(
      hex2bin('ff91f401'),
      VarInt::packInt($value)
    );

    $value = 2;
    $this->assertEquals(
      hex2bin('04'),
      VarInt::packInt($value)
    );

    $value = 123;
    $this->assertEquals(
      hex2bin('f601'),
      VarInt::packInt($value)
    );

    $value = 51180253;
    $this->assertEquals(
      hex2bin('bacbe730'),
      VarInt::packInt($value)
    );
  }
}