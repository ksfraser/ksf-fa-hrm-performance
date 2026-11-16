<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Performance\Tests\Unit;
use PHPUnit\Framework\TestCase;
use Ksfraser\FrontAccounting\HRM\Performance\DTO\PerformanceDTO;
class PerformanceDtoTest extends TestCase
{
    public function testDtoRoundTripsThroughArray(): void {
        $dto = new PerformanceDTO(5, 10, 'Sue Jones', 7, 'Kevin Smith', '2026-H1', '4.50', 'Improve delivery', 'Exceeded targets', 'reviewed');
        $arr = $dto->toArray();
        $this->assertSame(5, $arr['review_id']);
        $this->assertSame(10, $arr['person_id']);
        $this->assertSame('Sue Jones', $arr['person_name']);
        $this->assertSame(7, $arr['reviewer_id']);
        $this->assertSame('Kevin Smith', $arr['reviewer_name']);
        $this->assertSame('2026-H1', $arr['review_period']);
        $this->assertSame('4.50', $arr['rating']);
        $this->assertSame('reviewed', $arr['state']);
        $dto2 = PerformanceDTO::fromArray($arr);
        $this->assertSame($dto->review_id, $dto2->review_id);
        $this->assertSame($dto->rating, $dto2->rating);
        $this->assertSame($dto->state, $dto2->state);
    }
    public function testDisplayLabelUsesPersonWhenSet(): void {
        $dto = new PerformanceDTO(1, 2, 'Sue Jones', 3, '', '2026-H1', '4.00');
        $this->assertStringContainsString('Sue Jones', $dto->getDisplayLabel());
    }
    public function testDisplayLabelFallsBackToId(): void {
        $dto = new PerformanceDTO(9, 2, '', 0, '', '2026-H1', '3.00');
        $this->assertStringContainsString('Performance Review #9', $dto->getDisplayLabel());
    }
}
