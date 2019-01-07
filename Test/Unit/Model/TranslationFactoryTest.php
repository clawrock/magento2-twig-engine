<?php

namespace ClawRock\TwigEngine\Test\Unit\Model;

use ClawRock\TwigEngine\Model\TranslationFactory;
use PHPUnit\Framework\TestCase;

class TranslationFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new TranslationFactory();
        $this->assertInstanceOf(\Magento\Framework\Phrase::class, $factory->create('%1 test', ['unit']));
    }
}
