<?php

namespace Tests\Unit;

use App\Models\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    /** @test */
    public function it_checks_if_module_is_inactive()
    {
        // Create a module instance with the status set to 'inactive'
        $module = new Module(['status' => 'inactive']);

        // Assert that the isInactive method returns true
        $this->assertTrue($module->isInactive());

        // Now set the status to 'active' and assert that isInactive returns false
        $module->status = 'active';
        $this->assertFalse($module->isInactive());
    }
}
