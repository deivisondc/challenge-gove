<?php

namespace Tests\Unit;

use App\DTO\UpdateNotificationDTO;
use App\Enums\NotificationStatus;
use App\Jobs\UpdateNotification;
use App\Models\Contact;
use App\Models\Notification;
use App\Repositories\NotificationRepository;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Bus;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Utils;

class NotificationServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_should_return_notification_by_file(): void
    {
        $status = NotificationStatus::QUEUED;
        $date = '2024-02-01';

        $returnMock = new Notification();
        $returnMock->id = 5;
        $returnMock->contact_id = 10;
        $returnMock->file_import_id = 15;
        $returnMock->scheduled_for = $date;
        $returnMock->status = $status->name;
        $repositoryResult = Utils::mockPaginate($returnMock->toArray(), [
            [ "label" => "prev" ],
            [ "label" => 1 ],
            [ "label" => "next" ],
        ], 1, 1);
        $repoStub = $this->createMock(NotificationRepository::class);
        $repoStub->method("filter")->willReturn($repositoryResult);

        $service = new NotificationService($repoStub);
        $result = $service->getByFile(1, []);

        $this->assertEquals([
            "id" => 5,
            "contact_id" => 10,
            "file_import_id" => 15,
            "scheduled_for" => $date,
            "status" => $status->name,
        ], $result['data']);
        $this->assertCount(1, $result['links']);
    }

    public function test_should_return_empty_data_when_no_notification_by_file(): void
    {
        $returnMock = [];
        $repositoryResult = Utils::mockPaginate($returnMock, [
            [ "label" => "prev" ],
            [ "label" => 1 ],
            [ "label" => "next" ],
        ], 1, 1);
        $repoStub = $this->createMock(NotificationRepository::class);
        $repoStub->method("filter")->willReturn($repositoryResult);

        $service = new NotificationService($repoStub);
        $result = $service->getByFile(1, []);

        $this->assertEquals([], $result['data']);
        $this->assertCount(1, $result['links']);
    }

    public function test_should_get_all_until_today(): void
    {
        $status = NotificationStatus::QUEUED;

        $returnMock = new Notification();
        $returnMock->id = 5;
        $returnMock->contact_id = 10;
        $returnMock->file_import_id = 15;
        $returnMock->scheduled_for = '2024-01-01';
        $returnMock->status = $status->name;
        $repoStub = $this->createMock(NotificationRepository::class);
        $repoStub->method("allUntilToday")->willReturn([$returnMock]);

        $service = new NotificationService($repoStub);
        $result = $service->getAllUntilToday($status);

        $this->assertEquals([$returnMock], $result);
    }

    public function test_should_update_status(): void
    {
        $status = NotificationStatus::SUCCESS;

        $paramMock = new Notification();
        $paramMock->id = 5;
        $paramMock->contact_id = 10;
        $paramMock->file_import_id = 15;
        $paramMock->scheduled_for = '2024-01-01';
        $paramMock->status = NotificationStatus::QUEUED->name;

        $returnMock = new Notification();
        $returnMock->id = 5;
        $returnMock->contact_id = 10;
        $returnMock->file_import_id = 15;
        $returnMock->scheduled_for = '2024-01-01';
        $returnMock->status = $status->name;

        $repoStub = $this->createMock(NotificationRepository::class);
        $repoStub->method("updateStatus")->willReturn($returnMock);

        $service = new NotificationService($repoStub);
        $result = $service->updateStatus($paramMock, $status);

        $this->assertEquals($returnMock, $result);
    }

    public function test_should_update_notification_and_send_to_job(): void
    {
        $status = NotificationStatus::SUCCESS;

        $returnMock = new Notification();
        $returnMock->id = 5;
        $returnMock->contact_id = 10;
        $returnMock->file_import_id = 15;
        $returnMock->scheduled_for = '2024-01-01';
        $returnMock->status = $status->name;

        $dto = UpdateNotificationDTO::make(
            $returnMock->scheduled_for,
            NotificationStatus::SUCCESS,
        );

        $repoStub = $this->instance(
            NotificationRepository::class,
            Mockery::mock(NotificationRepository::class, function (MockInterface $mock) use($returnMock) {
                $mock->shouldReceive('update')->once()->andReturn($returnMock);
            })
        );

        Bus::fake();

        $service = new NotificationService($repoStub);
        $service->update($returnMock, $dto);

        Bus::assertDispatched(UpdateNotification::class);
    }

    public function test_should_update_notifications_in_batch(): void
    {
        $status = NotificationStatus::SUCCESS;

        $contactMock = new Contact();
        $contactMock->id = 1;
        $contactMock->name = "Test";
        $contactMock->contact = "Test Contact";

        $paramMock = new Notification();
        $paramMock->id = 5;
        $paramMock->contact_id = 10;
        $paramMock->file_import_id = 15;
        $paramMock->scheduled_for = '2024-01-01';
        $paramMock->status = $status->name;
        $paramMock->contact = $contactMock;

        $mock = $this->instance(
            NotificationRepository::class,
            Mockery::mock(NotificationRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('updateStatusInBatch')->once();
            })
        );

        $service = new NotificationService($mock);
        $service->updateStatusInBatch([$paramMock], $status);

        $mock->shouldHaveReceived('updateStatusInBatch')->once();
        $this->assertTrue(true);
    }

    public function test_should_dispatch_notification_job()
    {
        $paramMock = new Notification();
        $paramMock->id = 5;
        $paramMock->contact_id = 10;
        $paramMock->file_import_id = 15;
        $paramMock->scheduled_for = '2024-01-01';
        $paramMock->status = NotificationStatus::CANCELED->name;

        $repoStub = $this->instance(
            NotificationRepository::class,
            Mockery::mock(NotificationRepository::class)
        );

        Bus::fake();

        $service = new NotificationService($repoStub);
        $service->dispatchJobForOneNotification($paramMock);

        Bus::assertDispatched(UpdateNotification::class);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }
}
