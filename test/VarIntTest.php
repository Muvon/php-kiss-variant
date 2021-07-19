<?php
use Muvon\KISS\VarInt;
use PHPUnit\Framework\TestCase;

class VarIntTest extends TestCase {
  public function testReadUintFromHex() {
    $hex = '80897a';
    $this->assertEquals(
      2000000,
      VarInt::readUint($hex)
    );

    $hex = 'dde5b31880897a';
    $this->assertEquals(
      51180253,
      VarInt::readUint($hex)
    );
  }

  public function testPackUintToHex() {
    $value = 2000000;
    $this->assertEquals(
      '80897a',
      VarInt::packUint($value)
    );

    $value = 51180253;
    $this->assertEquals(
      'dde5b318',
      VarInt::packUint($value)
    );
  }

  public function testReadIntFromHex() {
    $hex = '80897a';
    $this->assertEquals(
      2000000,
      VarInt::readUint($hex)
    );

    $hex = 'dde5b31880897a';
    $this->assertEquals(
      51180253,
      VarInt::readUint($hex)
    );
  }

  public function testPackIntTohex() {
    $value = -2000000;
    $this->assertEquals(
      '8092f401',
      VarInt::packInt($value)
    );

    $value = -51180253;
    $this->assertEquals(
      'bacbe730',
      VarInt::packInt($value)
    );

    $value = 51180253;
    $this->assertEquals(
      '80c0e730',
      VarInt::packInt($value)
    );
  }
}