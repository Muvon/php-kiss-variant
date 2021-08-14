<?php
namespace Muvon\KISS;

use InvalidArgumentException;

class VarInt {
  public static function readUint(string $hex, int $offset = 0, $max_len = 0): array {
    $x = 0;
    $s = 0;
    $i = $offset;
    $max_i = $max_len * 2 + $offset;
    while (isset($hex[$i])) {
      $b = intval(hexdec($hex[$i] . $hex[$i + 1]));
      if ($b < 0x80) {
        if ($max_len > 0 && ($i > $max_i || $i === $max_i && $b > 1)) {
          throw new InvalidArgumentException('The number overflows allowed limits of max byte len = ' . $max_len);
        }
        $result = gmp_or($x, gmp_shiftl($b, $s));
        return [gmp_cmp($result, PHP_INT_MAX) >= 0 ? gmp_strval($result) : gmp_intval($result), $i + 2];
      }
      $x = gmp_strval(gmp_or($x, gmp_shiftl(gmp_strval(gmp_and($b, 0x7f)), $s)));
      $s += 7;
      $i += 2;
    }

    return [0, 0];
  }

  public static function readBool(string $hex, int $offset = 0): array {
    [$flag, $next_offset] = static::readUint($hex, $offset);
    return [!!$flag, $next_offset];
  }

  public static function packUint(string $value): string {
    $h = '';
    $i = 0;

    while ($value >= 0x80) {
      $h .= sprintf(
        '%02x',
        ($value < PHP_INT_MAX ? ($value & 0xff | 0x80) : gmp_strval(gmp_or(gmp_and($value, 0xff), 0x80)))
      );
      $value = $value < PHP_INT_MAX ? $value >> 7 : gmp_shiftr($value, 7);
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

function gmp_shiftl($x,$n) { // shift left
  $bin_val = gmp_strval(gmp_init($x, 10), 2) . str_repeat('0', $n);
  return gmp_strval(gmp_init($bin_val, 2));
}

function gmp_shiftr($x,$n) { // shift right
  $bin_val = substr(gmp_strval(gmp_init($x, 10), 2), 0, -$n);
  return gmp_strval(gmp_init($bin_val, 2));
}