<?php

namespace ClawRock\TwigEngine\Model;

class TranslationFactory
{
    /**
     * @param string $text
     * @param array  $arguments
     * @return \Magento\Framework\Phrase
     */
    public function create(string $text, array $arguments = []): \Magento\Framework\Phrase
    {
        return new \Magento\Framework\Phrase($text, $arguments);
    }
}
