<?php
namespace Acme\Support\Utility;

/**
 * 「n回ごとに何かをしたい」カウンタ
 *
 * 主にバッチ処理のログ出力のため
 */
class NotifiableCounter {

	/** @var int */
	private $count = 0;

	/** @var int */
	private $per;
	/** @var int[] */
	private $startups;

	/** @var callable */
	private $callback;

	/**
	 * @param callable $callback 行いたい処理。arg1に現在の回数を取る。
	 * @param int $per 何回ごとにするか
	 * @param int[] $startups ピンポイントで出力したい回がある場合
	 */
	public function __construct(callable $callback, $per = 500, $startups = [5, 10, 50, 100, 500, 1000, 5000, 10000, 50000, 100000]) {
		$this->startups = $startups;
		$this->per = $per;
		$this->callback = $callback;
	}

	/**
	 * 処理1回ごとに呼ぶこと
	 * @return mixed callback次第
	 */
	public function increment() {
		$i = ++$this->count;

		if (0 === ($i % $this->per)) {
			return call_user_func_array($this->callback, [$i]);
		}
		if (in_array($i, $this->startups, true)) {
			return call_user_func_array($this->callback, [$i]);
		}
	}

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->count;
	}
}
