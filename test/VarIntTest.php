<?php
use Muvon\KISS\VarInt;
use PHPUnit\Framework\TestCase;

class VarIntTest extends TestCase {
  public function testReadUintFromHex() {
    $hex = '80897a';
    $this->assertEquals(
      [2000000, 6],
      VarInt::readUint($hex)
    );

    $hex = 'dde5b31880897a';
    $this->assertEquals(
      [51180253, 8],
      VarInt::readUint($hex)
    );

    $hex = 'd5c7c8a1a6ca9ee4bdab04';
    $this->assertEquals(
      ['5123423423180343223253', 22],
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

    $value = '5123423423180343223253';
    $this->assertEquals(
      'd5c7c8a1a6ca9ee4bdab04',
      VarInt::packUint($value)
    );
  }

  // public function testReadIntFromHex() {
  //   $hex = '80897a';
  //   $this->assertEquals(
  //     2000000,
  //     VarInt::readInt($hex)
  //   );

  //   $hex = 'dde5b31880897a';
  //   $this->assertEquals(
  //     51180253,
  //     VarInt::readInt($hex)
  //   );
  // }

  // public function testPackIntTohex() {
  //   $value = -2000000;
  //   $this->assertEquals(
  //     '8092f401',
  //     VarInt::packInt($value)
  //   );

  //   $value = -51180253;
  //   $this->assertEquals(
  //     'bacbe730',
  //     VarInt::packInt($value)
  //   );

  //   $value = 51180253;
  //   $this->assertEquals(
  //     '80c0e730',
  //     VarInt::packInt($value)
  //   );
  // }
}