<?php namespace Chekote\NounStore\Assert;

use Chekote\NounStore\Assert;
use Chekote\NounStore\Key;
use Chekote\NounStore\Store;
use Chekote\NounStore\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

abstract class AssertTest extends TestCase
{
    /** @var Assert */
    protected $assert;

    /** @var Store|ObjectProphecy */
    protected $store;

    /** @var Key|ObjectProphecy */
    protected $key;

    /** @var Prophet */
    protected $prophet;

    /**
     * Sets up the environment before each test.
     */
    public function setUp()
    {
        $prophet = new Prophet();

        $this->key = $prophet->prophesize()->willExtend(Key::class);
        $this->store = $prophet->prophesize()->willExtend(Store::class);

        /** @noinspection PhpParamsInspection */
        $this->assert = new Assert($this->store->reveal(), $this->key->reveal());

        $this->prophet = $prophet;
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown()
    {
        $this->prophet->checkPredictions();

        $this->key = null;
        $this->store = null;
        $this->assert = null;
    }
}
