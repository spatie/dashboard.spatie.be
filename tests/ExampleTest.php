<?php

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     */
    public function testBasicExample(): void
    {
        $this->visit('/')
            ->see('Laravel');
    }
}
