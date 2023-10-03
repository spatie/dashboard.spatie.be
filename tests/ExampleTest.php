<?php

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample(): void
    {
        $this->visit('/')
            ->see('Laravel');
    }
}
