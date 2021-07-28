<?php
namespace Muvon\KISS;

class VarInt {
  public static function readUint(string $hex, int $offset = 0): array {
    $x = 0;
    $s = 0;
    $i = $offset;
    $max_i = 19 + $offset;
    while (isset($hex[$i])) {
      $b = intval(hexdec($hex[$i] . $hex[$i + 1]));
      if ($b < 0x80) {
        if ($i > $max_i || $i === $max_i && $b > 1) {
          return [$x, $i + 2];
        }
        return [($x | $b << $s), $i + 2];
      }
      $x |= ($b & 0x7f) << $s;
      $s += 7;
      $i += 2;
    }

    return [0, 0];
  }

  public static function packUint(int $value): string {
    $h = '';
    $i = 0;
    while ($value >= 0x80) {
      $h .= sprintf('%02x', ($value & 0xff) | 0x80);
      $value >>= 7;
      $i++;
    }
    return $h . sprintf('%02x', $value & 0xff);
  }

  public static function putUint(string &$hex, int $value): string {
    $hex .= static::packUint($value);
    return $hex;
  }

  // public static function readInt(string $hex): ?int {
  //   $ux = static::readUint($hex);
  //   $x = $ux >> 1;
  //   if ($ux & 1 !== 0) {
  //     $x = ~$x;
  //   }

  //   return $x;
  // }

  // public static function packInt(int $value): string {
  //   $ux = ($value < 0 ? abs($value) : $value + 0x10000000000000000) << 1;
  //   if ($value < 0) {
  //     $ux = ~$ux;
  //   }
  //   return static::packUint($ux);
  // }


  // public static function putInt(string &$hex, int $value): string {
  //   $hex .= static::packInt($value);
  //   return $hex;
  // }
}