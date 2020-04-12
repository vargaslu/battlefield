<?php

namespace Tests\Game\Battleship;

require_once __DIR__ . '/../../src/positioning/Direction.php';

use InvalidArgumentException;
use Game\Battleship\Direction;
use PHPUnit\Framework\TestCase;

class DirectionTest extends TestCase {

    public function testHorizontalFromJson() {
        $data['direction'] = 0;
        self::assertEquals(Direction::HORIZONTAL, Direction::fromJson($data));

        $data['direction'] = 'H';
        self::assertEquals(Direction::HORIZONTAL, Direction::fromJson($data));

        $data['direction'] = 'h';
        self::assertEquals(Direction::HORIZONTAL, Direction::fromJson($data));
    }

    public function testVerticalFromJson() {
        $data['direction'] = 1;
        self::assertEquals(Direction::VERTICAL, Direction::fromJson($data));

        $data['direction'] = 'V';
        self::assertEquals(Direction::VERTICAL, Direction::fromJson($data));

        $data['direction'] = 'v';
        self::assertEquals(Direction::VERTICAL, Direction::fromJson($data));
    }

    public function testExceptionWhenValueIsNotExpected() {
        $this->expectException(InvalidArgumentException::class);
        $data['direction'] = 'a';
        Direction::fromJson($data);
    }
}
