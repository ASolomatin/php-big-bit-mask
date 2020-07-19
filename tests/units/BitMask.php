<?php

declare(strict_types=1);

namespace asolomatin\BigBitMask\tests\units;

use mageekguy\atoum;
use asolomatin\BigBitMask as app;

class BitMask extends atoum
{
    public function testCtor()
    {
        $this->exception(function () {
            (function ($v) {
                new app\BitMask($v);
            })(125);
        });

        $this->object(new app\BitMask());

        $this->exception(function () {
            new app\BitMask("AB@CD");
        });

        $this->object(new app\BitMask("AB2CD"));
    }

    public function testGetter()
    {
        $bitmask = new app\BitMask();

        $this->exception(function () use ($bitmask) {
            $bitmask[true];
        });

        $this->exception(function () use ($bitmask) {
            $bitmask["5"];
        });

        $this->exception(function () use ($bitmask) {
            $bitmask[-1];
        });

        $this->boolean($bitmask[0])->isFalse();
        $this->boolean($bitmask[1])->isFalse();
        $this->boolean($bitmask[1000])->isFalse();

        $bitmask = new app\BitMask("CE");

        $this->boolean($bitmask[0])->isFalse();
        $this->boolean($bitmask[2])->isFalse();
        $this->boolean($bitmask[1000])->isFalse();

        $this->boolean($bitmask[1])->isTrue();
        $this->boolean($bitmask[8])->isTrue();
    }

    public function testSetter()
    {
        $bitmask = new app\BitMask();

        $this->exception(function () use ($bitmask) {
            $bitmask[true] = true;
        });

        $this->exception(function () use ($bitmask) {
            $bitmask["5"] = true;
        });

        $this->exception(function () use ($bitmask) {
            $bitmask[-1] = true;
        });

        $bitmask[0] = true;
        $bitmask[1] = true;
        $bitmask[1000] = true;

        $this->boolean($bitmask[0])->isTrue();
        $this->boolean($bitmask[1])->isTrue();
        $this->boolean($bitmask[1000])->isTrue();

        $bitmask[1000] = false;

        $this->boolean($bitmask[1000])->isFalse();
    }

    public function testToString()
    {
        $bitmask = new app\BitMask("CE");
        $this->string(strval($bitmask))->isEqualTo("CE");

        $bitmask = new app\BitMask("CEAAAA");
        $this->string(strval($bitmask))->isEqualTo("CE");

        $bitmask[1000] = true;
        $this->string(strval($bitmask))->isEqualTo("CEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"
            . "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQ");

        $bitmask[1000] = false;
        $this->string(strval($bitmask))->isEqualTo("CE");
    }

    public function testGetBitCapacity()
    {
        $bitmask = new app\BitMask("CE");
        $this->integer($bitmask->getBitCapacity())->isEqualTo(12);

        $bitmask[1000] = true;
        $this->integer($bitmask->getBitCapacity())->isEqualTo(1002);

        $bitmask[2000] = false;
        $this->integer($bitmask->getBitCapacity())->isEqualTo(1002);
    }

    public function testIssetAndUnset()
    {
        $bitmask = new app\BitMask("CE");

        $this->boolean(isset($bitmask[0]))->isFalse();
        $this->boolean(isset($bitmask[2]))->isFalse();
        $this->boolean(isset($bitmask[1000]))->isFalse();

        $this->boolean(isset($bitmask[1]))->isTrue();
        $this->boolean(isset($bitmask[8]))->isTrue();

        $bitmask[9] = true;
        unset($bitmask[1], $bitmask[8]);

        $this->boolean(isset($bitmask[1]))->isFalse();
        $this->boolean(isset($bitmask[8]))->isFalse();
        $this->boolean(isset($bitmask[9]))->isTrue();

        $this->boolean($bitmask[1])->isFalse();
        $this->boolean($bitmask[8])->isFalse();
        $this->boolean($bitmask[9])->isTrue();
    }
}
