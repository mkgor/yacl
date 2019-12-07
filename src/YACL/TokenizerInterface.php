<?php


namespace YACL;


interface TokenizerInterface
{
    public function run(string $data): array;
}