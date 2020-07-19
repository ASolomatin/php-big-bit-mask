[packagist-icon]:           https://img.shields.io/packagist/v/asolomatin/php-big-bit-mask.svg
[packagist-downloads-icon]: https://img.shields.io/packagist/dt/asolomatin/php-big-bit-mask.svg
[packagist-url]:            https://packagist.org/packages/asolomatin/php-big-bit-mask

[test-icon]:                https://travis-ci.com/ASolomatin/php-big-bit-mask.svg?branch=master
[test-url]:                 https://travis-ci.com/github/ASolomatin/php-big-bit-mask

[coverage-icon]:            https://coveralls.io/repos/github/ASolomatin/php-big-bit-mask/badge.svg?branch=master
[coverage-url]:             https://coveralls.io/github/ASolomatin/php-big-bit-mask?branch=master

[license-icon]:             https://img.shields.io/github/license/ASolomatin/php-big-bit-mask
[license-url]:              https://github.com/ASolomatin/php-big-bit-mask/blob/master/LICENSE

# PHP big bit mask

[![Packagist][packagist-icon]][packagist-url]
[![Packagist downloads][packagist-downloads-icon]][packagist-url]
[![Travis-CI][test-icon]][test-url]
[![Coverage Status][coverage-icon]][coverage-url]
[![GitHub][license-icon]][license-url]

----------------------------------------

When bits is not enough ...

This library implements an fully compatible PHP version of [big-bit-mask](https://github.com/ASolomatin/big-bit-mask) - the bitmask serializable into a base64-like, url-safe string.

## Other platform compatibility

| Platform | Repository | Package |
|-|-|-|
| JavaScript / TypeScript | [big-bit-mask](https://github.com/ASolomatin/big-bit-mask) | [NPM](https://www.npmjs.com/package/big-bit-mask) |
| .NET | [BigBitMask.NET](https://github.com/ASolomatin/BigBitMask.NET) | [NuGet](https://www.nuget.org/packages/BigBitMask.NET/) |

## Install
```sh
> composer require asolomatin/php-big-bit-mask
```

## Usage

### Namespace
```php
use asolomatin\BigBitMask\BitMask;
```

### What next?

Now we can create new empty bitmask
```php
$bitmask = new BitMask();
```
or load it from string
```php
$bitmask = new BitMask("CE3fG_gE-56");

//Let's see what inside now
$content = "";
for ($i = 0; $i < 11 * 6; $i++) { // Each character contains 6 bits, as in base64
    $content .= $bitmask[$i] ? "1" : "0";
}
echo $content.PHP_EOL;
```
output: `010000001000111011111110011000111111000001001000011111100111010111`

Then we can change some bits and get back our string representation
```php
$bitmask[65] = false;
$bitmask[64] = false;
$bitmask[63] = false;
$bitmask[61] = false;

$bitmask[19] = false;
$bitmask[5] = true;

echo strval($bitmask).PHP_EOL;
```
output: `iE3dG_gE-5`

#### But what if I want to have a named flags?

You can extend BitMask class with your model:
```php
class MyCoolCheckboxes extends BitMask
{
    const CHECKBOX_0 = 0;
    const CHECKBOX_1 = 1;
    const CHECKBOX_2 = 2;
    const CHECKBOX_3 = 3;
    const CHECKBOX_4 = 4;
    const CHECKBOX_5 = 5;
    const CHECKBOX_6 = 6;
    const CHECKBOX_7 = 7;
    const CHECKBOX_8 = 8;
    const CHECKBOX_9 = 9;

    // Some magic
    public function __set(string $name, bool $value) { call_user_func([$this, "set".ucfirst($name)], $value); }
    public function __get(string $name): bool { return call_user_func([$this, "set".ucfirst($name)]); }

    public function getCheckbox0(): bool { return $this[self::CHECKBOX_0]; }
    public function setCheckbox0(bool $value) { $this[self::CHECKBOX_0] = $value; }

    public function getCheckbox1(): bool { return $this[self::CHECKBOX_1]; }
    public function setCheckbox1(bool $value) { $this[self::CHECKBOX_1] = $value; }

    public function getCheckbox2(): bool { return $this[self::CHECKBOX_2]; }
    public function setCheckbox2(bool $value) { $this[self::CHECKBOX_2] = $value; }

    public function getCheckbox3(): bool { return $this[self::CHECKBOX_3]; }
    public function setCheckbox3(bool $value) { $this[self::CHECKBOX_3] = $value; }

    public function getCheckbox4(): bool { return $this[self::CHECKBOX_4]; }
    public function setCheckbox4(bool $value) { $this[self::CHECKBOX_4] = $value; }

    public function getCheckbox5(): bool { return $this[self::CHECKBOX_5]; }
    public function setCheckbox5(bool $value) { $this[self::CHECKBOX_5] = $value; }

    public function getCheckbox6(): bool { return $this[self::CHECKBOX_6]; }
    public function setCheckbox6(bool $value) { $this[self::CHECKBOX_6] = $value; }

    public function getCheckbox7(): bool { return $this[self::CHECKBOX_7]; }
    public function setCheckbox7(bool $value) { $this[self::CHECKBOX_7] = $value; }

    public function getCheckbox8(): bool { return $this[self::CHECKBOX_8]; }
    public function setCheckbox8(bool $value) { $this[self::CHECKBOX_8] = $value; }

    public function getCheckbox9(): bool { return $this[self::CHECKBOX_9]; }
    public function setCheckbox9(bool $value) { $this[self::CHECKBOX_9] = $value; }
}

$checkboxes = new MyCoolCheckboxes();
$checkboxes->checkbox5 = true;
$checkboxes->checkbox7 = true;
$checkboxes->checkbox8 = true;

echo strval($checkboxes).PHP_EOL;
```
output: `gG`

----------------------------------------

## License

**[MIT][license-url]**

Copyright (C) 2020 Aleksej Solomatin