# php-kiss-varint

KISS implementation of varint encode and decoder for int/uint

## Basic usage

```php
use Muvon\KISS\VarInt;

VarInt::packInt($value); // Pack value to a signed variable integer, little-endian
VarInt::packUint($value); // Pack value to signed variable integer, little-endian
VarInt::readInt($hex); // Unpack hex string as signed integer
VarInt::readUint($hex); // Unpack hax string as unsigned integer
```
