<?php
use Muvon\KISS\Variant;
use PHPUnit\Framework\TestCase;

class VariantTest extends TestCase {
  public function testReadUintFromHex() {
    $hex = '80897a';
    $this->assertEquals(
      2000000,
      Variant::readUint($hex)
    );

    $hex = 'dde5b31880897a';
    $this->assertEquals(
      51180253,
      Variant::readUint($hex)
    );
  }

  public function testPackUintToHex() {
    $value = 2000000;
    $this->assertEquals(
      '80897a',
      Variant::packUint($value)
    );

    $value = 51180253;
    $this->assertEquals(
      'dde5b318',
      Variant::packUint($value)
    );
  }

  public function testReadIntFromHex() {
    $hex = '80897a';
    $this->assertEquals(
      2000000,
      Variant::readUint($hex)
    );

    $hex = 'dde5b31880897a';
    $this->assertEquals(
      51180253,
      Variant::readUint($hex)
    );
  }

  public function testPackIntTohex() {
    $value = -2000000;
    $this->assertEquals(
      '8092f401',
      Variant::packInt($value)
    );

    $value = -51180253;
    $this->assertEquals(
      'bacbe730',
      Variant::packInt($value)
    );

    $value = 51180253;
    $this->assertEquals(
      '80c0e730',
      Variant::packInt($value)
    );
  }
}