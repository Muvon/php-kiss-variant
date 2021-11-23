<?php
namespace Muvon\KISS;

use InvalidArgumentException;

class VarInt {
  public static function readUint(string $bin, int $offset = 0, $max_len = 0): array {
    $x = 0;
    $s = 0;
    $i = $offset;
    $max_i = $max_len + $offset;
    while (isset($bin[$i])) {
      $b = ord($bin[$i]);
      if ($b < 0x80) {
        if ($max_len > 0 && ($i > $max_i || $i === $max_i && $b > 1)) {
          throw new InvalidArgumentException('The number overflows allowed limits of max byte len = ' . $max_len);
        }
        $result = gmp_or($x, gmp_shiftl($b, $s));
        return [gmp_cmp($result, PHP_INT_MAX) >= 0 ? gmp_strval($result) : gmp_intval($result), $i + 1];
      }
      $x = gmp_strval(gmp_or($x, gmp_shiftl(gmp_strval(gmp_and($b, 0x7f)), $s)));
      $s += 7;
      ++$i;
    }

    return [0, 0];
  }

  public static function readBool(string $bin, int $offset = 0): array {
    [$flag, $next_offset] = static::readUint($bin, $offset);
    return [!!$flag, $next_offset];
  }

  public static function packUint(string $value): string {
    $h = '';
    $i = 0;

    while ($value >= 0x80) {
      $h .= chr($value < PHP_INT_MAX ? ($value & 0xff | 0x80) : gmp_strval(gmp_or(gmp_and($value, 0xff), 0x80)));
      $value = $value < PHP_INT_MAX ? $value >> 7 : gmp_shiftr($value, 7);
      $i++;
    }
    return $h . chr($value & 0xff);
  }

  public static function putUint(string &$bin, int $value): string {
    $bin .= static::packUint($value);
    return $bin;
  }

  public static function readInt(string $bin, int $offset = 0, $max_len = 0): array {
    [$ux, $offset] = static::readUint($bin, $offset, $max_len);
    $x = intval($ux >> 1);
    $b = $ux > PHP_INT_MAX ? gmp_intval(gmp_and($ux, 1)) : ($ux & 1);
    if ($b !== 0) {
      $x = ~$x;
    }

    return [$x, $offset];
  }

  public static function packInt(int $value): string {
    $ux = gmp_shiftl(static::uint64($value), 1);
    if ($value < 0) {
      if ($ux < PHP_INT_MAX) {
        $ux = ~$ux;
      } else {
        $ux = gmp_not($ux);
      }
    }

    return static::packUint($ux);
  }

  public static function putInt(string &$bin, int $value): string {
    $bin .= static::packInt($value);
    return $bin;
  }

  protected static function uint64(string $value): string {
    if ($value < 0) {
      return gmp_strval(gmp_add('18446744073709551616', $value));
    }
    return $value;
  }

  protected static function int64(string $value): string {
    if ($value >= PHP_INT_MAX) {
      return gmp_strval(gmp_sub($value, '18446744073709551616'));
    }
    return $value;
  }
}

function gmp_shiftl($x,$n) { // shift left
  $bin_val = gmp_strval(gmp_init($x, 10), 2) . str_repeat('0', $n);
  return gmp_strval(gmp_init($bin_val, 2));
}

function gmp_shiftr($x,$n) { // shift right
  $bin_val = substr(gmp_strval(gmp_init($x, 10), 2), 0, -$n);
  return gmp_strval(gmp_init($bin_val, 2));
}

function gmp_not($value) {
  $mask = str_repeat('1', strlen(gmp_strval(gmp_init($value, 10), 2)));
  return gmp_strval(gmp_xor(gmp_init($mask, 2), $value), 10);
}