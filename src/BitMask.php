<?php

declare(strict_types=1);

namespace asolomatin\BigBitMask;

use ArrayAccess;
use BadMethodCallException;
use OutOfRangeException;
use UnexpectedValueException;

class BitMask implements ArrayAccess
{
    /** @var string[] */
    private static $alpha = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
        'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
        'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '_',
    ];

    /** @var int[string] */
    private static $reverse = [
        'A' => 0x00, 'B' => 0x01, 'C' => 0x02, 'D' => 0x03, 'E' => 0x04, 'F' => 0x05, 'G' => 0x06, 'H' => 0x07,
        'I' => 0x08, 'J' => 0x09, 'K' => 0x0A, 'L' => 0x0B, 'M' => 0x0C, 'N' => 0x0D, 'O' => 0x0E, 'P' => 0x0F,
        'Q' => 0x10, 'R' => 0x11, 'S' => 0x12, 'T' => 0x13, 'U' => 0x14, 'V' => 0x15, 'W' => 0x16, 'X' => 0x17,
        'Y' => 0x18, 'Z' => 0x19, 'a' => 0x1A, 'b' => 0x1B, 'c' => 0x1C, 'd' => 0x1D, 'e' => 0x1E, 'f' => 0x1F,
        'g' => 0x20, 'h' => 0x21, 'i' => 0x22, 'j' => 0x23, 'k' => 0x24, 'l' => 0x25, 'm' => 0x26, 'n' => 0x27,
        'o' => 0x28, 'p' => 0x29, 'q' => 0x2A, 'r' => 0x2B, 's' => 0x2C, 't' => 0x2D, 'u' => 0x2E, 'v' => 0x2F,
        'w' => 0x30, 'x' => 0x31, 'y' => 0x32, 'z' => 0x33, '0' => 0x34, '1' => 0x35, '2' => 0x36, '3' => 0x37,
        '4' => 0x38, '5' => 0x39, '6' => 0x3A, '7' => 0x3B, '8' => 0x3C, '9' => 0x3D, '-' => 0x3E, '_' => 0x3F,
    ];

    /** @var int[] */
    private $blocks;

    /**
     * @param string $mask [optional] String representation of the mask.
     *
     */
    public function __construct(string $mask = null)
    {
        if (!is_string($mask) || !strlen($mask)) {
            $this->blocks = [];
        } else {
            $this->blocks = array_map(function (string $block) {
                if (!isset(self::$reverse[$block])) {
                    throw new UnexpectedValueException("Format exception: unsupported token \"${block}\"");
                }
                return self::$reverse[$block];
            }, str_split($mask));
            $this->trimZeros();
        }
    }

    /**
     * Returns maximum bits count that can be set without increasing internal buffer
     *
     * @return int
     */
    public function getBitCapacity(): int
    {
        return sizeof($this->blocks) * 6;
    }

    /**
     * @param int $bit
     * @return bool
     */
    public function offsetExists($bit): bool
    {
        return $this[$bit];
    }

    /**
     * @param int $bit
     */
    public function offsetUnset($bit)
    {
        $this[$bit] = false;
    }

    /**
     * @param int $bit
     */
    public function offsetGet($bit)
    {

        if (!is_int($bit)) {
            throw new BadMethodCallException("Argument exception: \$bit must be an integer");
        }
        if ($bit < 0) {
            throw new OutOfRangeException("Argument exception: \$bit must be greater or equals to zero");
        }

        $block = (int)floor($bit / 6);

        return sizeof($this->blocks) <= $block ? false : !!($this->blocks[$block] >> ($bit % 6) & 0x1);
    }

    /**
     * @param int $bit
     * @param bool $newValue
     */
    public function offsetSet($bit, $newValue)
    {
        if (!is_int($bit)) {
            throw new BadMethodCallException("Argument exception: \$bit must be an integer");
        }
        if ($bit < 0) {
            throw new OutOfRangeException("Argument exception: \$bit must be greater or equals to zero");
        }

        $block = (int)floor($bit / 6);

        if (!$newValue && sizeof($this->blocks) <= $block) {
            return false;
        }

        $bit %= 6;

        for ($i = sizeof($this->blocks); $i <= $block; $i++) {
            array_push($this->blocks, 0);
        }

        $this->blocks[$block] = $newValue ?
            $this->blocks[$block] | 0x1 << $bit :
            $this->blocks[$block] & ~(0x1 << $bit);

        if (!$newValue && sizeof($this->blocks) - 1 == $block) {
            $this->trimZeros();
        }
    }

    public function __toString()
    {
        return implode(array_map(function (int $block) {
            return self::$alpha[$block];
        }, $this->blocks));
    }

    private function trimZeros()
    {
        for ($i = sizeof($this->blocks) - 1; $i >= 0; $i--) {
            if ($this->blocks[$i]) {
                break;
            }
        }
        $clear = sizeof($this->blocks) - (++$i);
        if ($clear) {
            array_splice($this->blocks, $i, $clear);
        }
    }
}
