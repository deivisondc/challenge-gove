<?php

namespace Tests\Unit;

use App\DTO\UpdateFileImportDTO;
use App\Enums\FileImportStatus;
use App\Models\FileImport;
use App\Repositories\FileImportRepository;
use App\Services\FileImportService;
use PHPUnit\Framework\TestCase;
use Tests\Utils;

class FileImportServiceTest extends TestCase
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
        $repoStub = $this->createMock(FileImportRepository::class);
        $repoStub->method("filter")->willReturn($teste);

        $service = new FileImportService($repoStub);
        $result = $service->getAll([]);

        $this->assertEquals([ "test" => 123 ], $result['data']);
        $this->assertCount(1, $result['links']);
    }

    public function test_should_be_able_to_update_file_import_status(): void
    {
        $status = FileImportStatus::SUCCESS;

        $returnMock = new FileImport();
        $returnMock->id = 1;
        $returnMock->filename = 'mock.xlsx';
        $returnMock->status = $status->name;
        $repoStub = $this->createMock(FileImportRepository::class);
        $repoStub->method("update")->willReturn($returnMock);

        $service = new FileImportService($repoStub);
        $dto = UpdateFileImportDTO::make(1, 'mock.xlsx', $status);
        $result = $service->updateStatus($dto);

        $this->assertEquals($returnMock, $result);
    }

    // public function test_should_be_able_to_call_import_job(): void
    // {
    //     $returnMock = new FileImport();
    //     $returnMock->id = 1;
    //     $returnMock->filename = 'mock.xlsx';
    //     $returnMock->status = FileImportStatus::QUEUED->name;
    //     $repoStub = $this->createMock(FileImportRepository::class);
    //     $repoStub->method("save")->willReturn($returnMock);

    //     $service = new FileImportService($repoStub);
    //     $dto = UpdateFileImportDTO::make(1, 'mock.xlsx', $status);
    //     $result = $service->updateStatus($dto);

    //     $this->assertEquals($returnMock, $result);
    // }
}
