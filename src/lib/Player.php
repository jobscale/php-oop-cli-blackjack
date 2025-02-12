<?php

namespace Blackjack;

/**
 * プレイヤークラス
 */
abstract class Player
{
    public const HIT = 'hit';
    public const STAND = 'stand';
    public const BURST = 'burst';

    public const WIN = 'win';
    public const LOSE = 'lose';
    public const DRAW = 'draw';

    public const NO_SPLIT = 0; // 宣言なし
    public const SPLIT_FIRST = 1; // スプリット宣言 1 手目
    public const SPLIT_SECOND = 2; // スプリット宣言 2 手目


    /**
     * コンストラクタ
     *
     * @param string $name プレイヤー名
     * @param int $chips チップ残高
     * @param int $bets ベットした額
     * @param array<int,array<string,int|string>> $hand 手札
     * @param int $scoreTotal プレイヤーの現在の得点
     * @param int $countAce プレイヤーの引いた A の枚数
     * @param string $status プレイヤーの状態
     * @param int $splitStatus スプリット宣言の状態
     */
    public function __construct(
        private string $name,
        private int $chips = 100,
        private int $bets = 0,
        private array $hand = [],
        private int $scoreTotal = 0,
        private int $countAce = 0,
        private string $status = self::HIT,
        private int $splitStatus = self::NO_SPLIT
    ) {
    }

    /**
     * プレイヤー名を返す
     *
     * @return string $this->name プレイヤー名
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * チップ残高を返す
     *
     * @return int $this->chips チップ残高
     */
    public function getChips(): int
    {
        return $this->chips;
    }

    /**
     * ベットした額を返す
     *
     * @return int $this->bets ベットした額
     */
    public function getBets(): int
    {
        return $this->bets;
    }

    /**
     * 手札を返す
     *
     * @return array<int,array<string,int|string>> $this->hand 手札
     */
    public function getHand(): array
    {
        return $this->hand;
    }

    /**
     * 得点を返す
     *
     * @return int $this->scoreTotal 得点
     */
    public function getScoreTotal(): int
    {
        return $this->scoreTotal;
    }

    /**
     * プレイヤーの状態を返す
     *
     * @return string $this->status プレイヤーの状態
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * スプリット宣言の状態を返す
     *
     * @return int $this->splitStatus スプリット宣言の状態
     */
    public function getSplitStatus(): int
    {
        return $this->splitStatus;
    }

    /**
     * 1枚カードを手札に加える
     *
     * @param array<int,array<string,int|string>> $card
     * @return void
     */
    public function addACardToHand(array $card): void
    {
        $this->hand = array_merge($this->hand, $card);
    }

    /**
     * プレイヤーの現在の得点を計算する
     *
     * @return void
     */
    public function calcScoreTotal(): void
    {
        $this->scoreTotal = 0;
        $this->countAce = 0;
        foreach ($this->hand as $card) {
            if ($card['num'] === 'A') {
                ++$this->countAce;
            }
            $this->scoreTotal += $card['score'];
        }
        $this->calcAceScore();
    }

    /**
     * A の点数については、デフォルト 11 でカウントされており、得点が21点を超えている場合は、 1 でカウントする
     *
     * @return void
     */
    private function calcAceScore(): void
    {
        for ($i = 0; $i < $this->countAce; $i++) {
            if ($this->scoreTotal > 21) {
                $this->scoreTotal -= 10;
            }
        }
    }

    /**
     * ベット額を変更する
     *
     * @param int $bets
     * @return void
     */
    public function changeBets(int $bets): void
    {
        $this->bets = $bets;
    }

    /**
     * チップ残高を変更する
     *
     * @param int $chips
     * @return void
     */
    public function changeChips(int $chips): void
    {
        $this->chips = $chips;
    }

    /**
     * 手札を変更する
     *
     * @param array<int,array<string,int|string>> $hand 手札
     * @return void
     */
    public function changeHand(array $hand): void
    {
        $this->hand = $hand;
    }

    /**
     * プレイヤーの状態を変更する
     *
     * @param string $status
     * @return void
     */
    public function changeStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * スプリット宣言の状態を変更する
     *
     * @param int $splitStatus
     * @return void
     */
    public function changeSplitStatus(int $splitStatus): void
    {
        $this->splitStatus = $splitStatus;
    }

    /**
     * １ゲーム終了後に初期化をする
     *
     * @return void
     */
    public function reset()
    {
        $this->bets = 0;
        $this->hand = [];
        $this->scoreTotal = 0;
        $this->countAce = 0;
        $this->status = self::HIT;
    }
}
