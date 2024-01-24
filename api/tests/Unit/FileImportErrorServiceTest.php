<?php

namespace Tests\Unit;

use App\Repositories\FileImportErrorRepository;
use App\Services\FileImportErrorService;
use PHPUnit\Framework\TestCase;
use Tests\Utils;

class FileImportErrorServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_should_retrieve_data(): void
    {
        $teste = Utils::mockPaginate([ "test" => 123 ], [
            [ "label" => "prev" ],
            [ "label" => 1 ],
            [ "label" => "next" ],
        ], 1, 1);
        $repoStub = $this->createMock(FileImportErrorRepository::class);
        $repoStub->method("filter")->willReturn($teste);

        $service = new FileImportErrorService($repoStub);
        $result = $service->getByFile(1, []);

        $this->assertEquals([ "test" => 123 ], $result['data']);
        $this->assertCount(1, $result['links']);
    }
}
