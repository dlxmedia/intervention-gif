<?php

namespace Intervention\Gif\Decoder;

use Intervention\Gif\Exception\DecoderException;
use Intervention\Gif\ImageDescriptor;

class ImageDescriptorDecoder extends AbstractPackedBitDecoder
{
    /**
     * Decode given string to current instance
     *
     * @param  string $source
     * @return ImageDescriptor
     */
    public function decode(): ImageDescriptor
    {
        $descriptor = new ImageDescriptor;

        $descriptor->setPosition(
            $this->decodeMultiByte($this->getFirstTwoBytes()),
            $this->decodeMultiByte($this->getNextBytes(2))
        );

        $descriptor->setSize(
            $this->decodeMultiByte($this->getNextBytes(2)),
            $this->decodeMultiByte($this->getNextBytes(2))
        );

        $packedField = $this->getNextByte();

        $descriptor->setLocalColorTableExistance(
            $this->decodeLocalColorTableExistance($packedField)
        );

        $descriptor->setLocalColorTableSorted(
            $this->decodeLocalColorTableSorted($packedField)
        );

        $descriptor->setLocalColorTableSize(
            $this->decodeLocalColorTableSize($packedField)
        );

        $descriptor->setInterlaced(
            $this->decodeInterlaced($packedField)
        );

        return $descriptor;
    }

    /**
     * Get first two bytes without image separator
     *
     * @return string
     */
    protected function getFirstTwoBytes(): string
    {
        $byte = $this->getNextByte();
        if ($byte === ImageDescriptor::SEPARATOR) {
            return $this->getNextBytes(2);
        }

        return $byte.$this->getNextByte();
    }

    /**
     * Decode local color table existance
     *
     * @return bool
     */
    protected function decodeLocalColorTableExistance(string $byte): bool
    {
        return $this->hasPackedBit($byte, 0);
    }

    /**
     * Decode local color table sort method
     *
     * @return bool
     */
    protected function decodeLocalColorTableSorted(string $byte): bool
    {
        return $this->hasPackedBit($byte, 2);
    }

    /**
     * Decode local color table size
     *
     * @return int
     */
    protected function decodeLocalColorTableSize(string $byte): int
    {
        return bindec($this->getPackedBits($byte, 5, 3));
    }

    /**
     * Decode interlaced flag
     *
     * @return bool
     */
    protected function decodeInterlaced(string $byte): bool
    {
        return $this->hasPackedBit($byte, 1);
    }
}